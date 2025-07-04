<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal Pelajaran: {{ $rombel->kelas->nama_kelas }} ({{ $rombel->tahun_ajaran }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('kurikulum.jadwal-pelajaran.store', $rombel->id) }}" method="POST">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse border border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border p-2">Jam Ke</th>
                                        <th class="border p-2">Waktu</th>
                                        @foreach ($days as $day)
                                            <th class="border p-2">{{ $day }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jamSlots as $jamKe => $slot)
                                        <tr>
                                            <td class="border p-2 text-center font-semibold">{{ $jamKe }}</td>
                                            <td class="border p-2 text-center text-sm">{{ $slot['mulai'] }} -
                                                {{ $slot['selesai'] }}</td>
                                            @foreach ($days as $day)
                                                @php
                                                    $currentJadwal = $jadwal->get($day . '-' . $jamKe);
                                                @endphp
                                                <td class="border p-1">
                                                    <select
                                                        name="jadwal[{{ $day }}][{{ $jamKe }}][mata_pelajaran_id]"
                                                        class="block w-full text-sm border-gray-300 rounded-md shadow-sm mb-1">
                                                        <option value="">-- Mapel --</option>
                                                        @foreach ($mataPelajaran as $mapel)
                                                            <option value="{{ $mapel->id }}"
                                                                @selected($currentJadwal?->mata_pelajaran_id == $mapel->id)>
                                                                {{ $mapel->nama_mapel }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <select
                                                        name="jadwal[{{ $day }}][{{ $jamKe }}][master_guru_id]"
                                                        class="block w-full text-sm border-gray-300 rounded-md shadow-sm">
                                                        <option value="">-- Guru --</option>
                                                        @foreach ($guru as $g)
                                                            <option value="{{ $g->id }}"
                                                                @selected($currentJadwal?->master_guru_id == $g->id)>
                                                                {{ $g->nama_lengkap }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="flex justify-end mt-6">
                            <x-primary-button type="submit">{{ __('Simpan Jadwal') }}</x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
