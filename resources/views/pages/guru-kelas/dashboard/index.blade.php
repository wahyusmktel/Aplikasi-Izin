<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Guru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Baris Atas: Widget Info & Siswa Izin -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Widget Kelas & Siswa Diajar -->
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Kelas yang Diajar</h3>
                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            @forelse ($kelasDiajar as $kelas)
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span class="font-medium text-gray-700">{{ $kelas->kelas->nama_kelas }}</span>
                                    <span class="text-sm text-gray-500">{{ $kelas->siswa_count }} Siswa</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada kelas yang diajar.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Widget Siswa Izin Hari Ini -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Siswa Izin Hari Ini (di Kelas Anda)</h3>
                        <div class="space-y-3 max-h-60 overflow-y-auto">
                            @forelse ($siswaIzinHariIni as $izin)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-user-check text-green-500"></i>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-sm font-semibold text-gray-800">{{ $izin->user->name }}</p>
                                        <p class="text-xs text-gray-600">
                                            {{ $izin->user->masterSiswa?->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}
                                            - {{ strip_tags($izin->keterangan) }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">Tidak ada siswa yang izin hari ini.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Widget Jadwal Mengajar Hari Ini -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Jadwal Mengajar Hari Ini:
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Waktu
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jam Ke
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kelas
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mata
                                        Pelajaran</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($jadwalHariIni as $jadwal)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $jadwal->jam_mulai }} -
                                            {{ $jadwal->jam_selesai }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">{{ $jadwal->jam_ke }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap font-medium">
                                            {{ $jadwal->rombel->kelas->nama_kelas }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $jadwal->mataPelajaran->nama_mapel }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada jadwal
                                            mengajar hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- WIDGET BARU: Siswa Sedang di Luar Kelas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6 mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Siswa Sedang di Luar Kelas (Real-time)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Siswa
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kelas
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tujuan
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Keluar
                                        Sejak</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($siswaSedangKeluar as $izin)
                                    <tr class="bg-yellow-50">
                                        <td class="px-4 py-3 whitespace-nowrap font-semibold">{{ $izin->siswa->name }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $izin->rombel->kelas->nama_kelas }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $izin->tujuan }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($izin->waktu_keluar_sebenarnya)->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada siswa
                                            yang sedang di luar kelas saat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- WIDGET BARU: Statistik Izin Keluar Kelas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-4">Top 5 Siswa Sering Izin Keluar</h3>
                    <canvas id="topSiswaIzinKeluarChart"></canvas>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-4">Top 5 Tujuan Izin Keluar</h3>
                    <canvas id="tujuanIzinKeluarChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const topSiswaData = @json($topSiswaIzinKeluarChartData);
                const tujuanData = @json($tujuanIzinKeluarChartData);

                // Bar Chart Top Siswa Izin Keluar
                if (document.getElementById('topSiswaIzinKeluarChart') && topSiswaData.data.length > 0) {
                    new Chart(document.getElementById('topSiswaIzinKeluarChart').getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: topSiswaData.labels,
                            datasets: [{
                                label: 'Jumlah Izin Keluar',
                                data: topSiswaData.data,
                                backgroundColor: 'rgba(255, 159, 64, 0.7)'
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }

                // Doughnut Chart Top Tujuan Izin Keluar
                if (document.getElementById('tujuanIzinKeluarChart') && tujuanData.data.length > 0) {
                    new Chart(document.getElementById('tujuanIzinKeluarChart').getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: tujuanData.labels,
                            datasets: [{
                                data: tujuanData.data,
                                backgroundColor: ['rgba(153, 102, 255, 0.8)', 'rgba(255, 99, 132, 0.8)',
                                    'rgba(75, 192, 192, 0.8)', 'rgba(255, 205, 86, 0.8)',
                                    'rgba(201, 203, 207, 0.8)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
