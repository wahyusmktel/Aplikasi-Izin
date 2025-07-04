<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal Pelajaran: {{ $rombel->kelas->nama_kelas }} ({{ $rombel->tahun_ajaran }})
        </h2>
    </x-slot>

    <div class="py-12" x-data="jadwalEditor()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('kurikulum.jadwal-pelajaran.store', $rombel->id) }}" method="POST">
                @csrf

                <!-- Panel Kontrol (Brush) -->
                <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                    <h3 class="font-semibold text-lg mb-2">Pengaturan Jadwal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="selectedMapel" class="block text-sm font-medium text-gray-700">Pilih Mata
                                Pelajaran</label>
                            <select id="selectedMapel" x-model.number="selectedMapelId"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Pilih Mapel --</option>
                                <template x-for="mapel in availableMapel" :key="mapel.id">
                                    <option :value="mapel.id"
                                        x-text="mapel.nama_mapel + ' (Sisa ' + mapel.sisa_jam + ' Jam)'"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label for="selectedGuru" class="block text-sm font-medium text-gray-700">Pilih Guru</label>
                            <select id="selectedGuru" x-model.number="selectedGuruId"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Pilih Guru --</option>
                                @foreach ($guru as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <x-primary-button type="submit">{{ __('Simpan Seluruh Jadwal') }}</x-primary-button>
                        </div>
                    </div>
                </div>

                <!-- Grid Jadwal -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border p-2 w-24">Waktu</th>
                                        @foreach ($days as $day)
                                            <th class="border p-2">{{ $day }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jamSlots as $jamKe => $slot)
                                        <tr>
                                            <td class="border p-2 text-center text-sm">
                                                <span class="font-bold">Jam Ke-{{ $jamKe }}</span><br>
                                                <span>{{ $slot['mulai'] }} - {{ $slot['selesai'] }}</span>
                                            </td>
                                            @foreach ($days as $day)
                                                <td class="border p-1 align-top h-24 w-40"
                                                    :class="getCellClass('{{ $day }}', {{ $jamKe }})"
                                                    data-cell="{{ $day }}-{{ $jamKe }}">
                                                    <label
                                                        class="flex flex-col justify-center items-center h-full w-full cursor-pointer">
                                                        <input type="checkbox" class="rounded"
                                                            :disabled="isSlotDisabled('{{ $day }}', {{ $jamKe }})"
                                                            @change="toggleSlot('{{ $day }}', {{ $jamKe }}, $event)">

                                                        <div x-show="jadwal['{{ $day }}-{{ $jamKe }}']"
                                                            class="text-center mt-1">
                                                            <p class="text-xs font-bold"
                                                                x-text="getMapelName('{{ $day }}', {{ $jamKe }})">
                                                            </p>
                                                            <p class="text-xs"
                                                                x-text="getGuruName('{{ $day }}', {{ $jamKe }})">
                                                            </p>
                                                        </div>

                                                        <input type="hidden"
                                                            :name="'jadwal[{{ $day }}][{{ $jamKe }}][mata_pelajaran_id]'"
                                                            :value="jadwal['{{ $day }}-{{ $jamKe }}']
                                                                ?.mata_pelajaran_id">
                                                        <input type="hidden"
                                                            :name="'jadwal[{{ $day }}][{{ $jamKe }}][master_guru_id]'"
                                                            :value="jadwal['{{ $day }}-{{ $jamKe }}']
                                                                ?.master_guru_id">
                                                    </label>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function jadwalEditor() {
            return {
                // Data dari PHP
                mataPelajaran: @json($mataPelajaran),
                guru: @json($guru),
                jadwal: @json($jadwalFormatted),

                // State
                selectedMapelId: '',
                selectedGuruId: '',

                init() {
                    // Inisialisasi checkbox berdasarkan data jadwal yang ada
                    this.$nextTick(() => {
                        document.querySelectorAll('input[type=checkbox]').forEach(cb => {
                            const [day, jamKe] = cb.closest('td').dataset.cell.split('-');
                            if (this.jadwal[`${day}-${jamKe}`]) {
                                cb.checked = true;
                            }
                        });
                    });
                },

                // ==================================
                //           BAGIAN PERBAIKAN
                // ==================================
                // Computed property untuk mapel yang tersedia di dropdown
                get availableMapel() {
                    // Hanya tampilkan mapel yang sisa jamnya lebih dari 0
                    return this.mataPelajaran.filter(mapel => mapel.sisa_jam > 0);
                },
                // ==================================
                //         BATAS PERBAIKAN
                // ==================================

                // Logika saat checkbox di-klik
                toggleSlot(day, jamKe, event) {
                    const key = `${day}-${jamKe}`;
                    const slotData = this.jadwal[key];

                    if (slotData) { // Proses uncheck
                        const mapelId = slotData.mata_pelajaran_id;
                        this.jadwal[key] = null;
                        this.getMapelById(mapelId).sisa_jam++;
                    } else { // Proses check
                        if (!this.selectedMapelId || !this.selectedGuruId) {
                            alert('Pilih Mata Pelajaran dan Guru terlebih dahulu!');
                            event.target.checked = false;
                            return;
                        }
                        const selectedMapel = this.getMapelById(this.selectedMapelId);
                        if (selectedMapel.sisa_jam <= 0) {
                            alert('Jumlah jam untuk mata pelajaran ini sudah habis.');
                            event.target.checked = false;
                            return;
                        }

                        this.jadwal[key] = {
                            mata_pelajaran_id: this.selectedMapelId,
                            master_guru_id: this.selectedGuruId,
                            mata_pelajaran: selectedMapel,
                            guru: this.getGuruById(this.selectedGuruId)
                        };
                        selectedMapel.sisa_jam--;
                    }
                },

                // Logika untuk men-disable checkbox
                isSlotDisabled(day, jamKe) {
                    const key = `${day}-${jamKe}`;
                    const slotData = this.jadwal[key];

                    if (slotData) return false; // Selalu bisa di-uncheck
                    if (!this.selectedMapelId) return true; // Disable jika belum pilih mapel

                    const selectedMapel = this.getMapelById(this.selectedMapelId);
                    return selectedMapel.sisa_jam <= 0;
                },

                // Helper untuk mendapatkan data
                getMapelById(id) {
                    return this.mataPelajaran.find(m => m.id == id);
                },
                getGuruById(id) {
                    return this.guru.find(g => g.id == id);
                },
                getMapelName(day, jamKe) {
                    return this.jadwal[`${day}-${jamKe}`]?.mata_pelajaran?.nama_mapel || '';
                },
                getGuruName(day, jamKe) {
                    return this.jadwal[`${day}-${jamKe}`]?.guru?.nama_lengkap || '';
                },

                // Helper untuk styling
                getCellClass(day, jamKe) {
                    const mapelId = this.jadwal[`${day}-${jamKe}`]?.mata_pelajaran_id;
                    if (!mapelId) return 'bg-white hover:bg-gray-50';
                    const colors = ['bg-blue-100', 'bg-green-100', 'bg-yellow-100', 'bg-purple-100', 'bg-pink-100',
                        'bg-indigo-100'
                    ];
                    return colors[mapelId % colors.length];
                }
            }
        }
    </script>
</x-app-layout>
