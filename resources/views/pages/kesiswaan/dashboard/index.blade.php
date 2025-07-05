<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kesiswaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ================================================== -->
            <!--   BAGIAN 1: STATISTIK IZIN TIDAK MASUK SEKOLAH     -->
            <!-- ================================================== -->
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Statistik Izin Tidak Masuk Sekolah</h3>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Proporsi Status Izin -->
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-4">Proporsi Status Izin</h3>
                    <canvas id="statusIzinChart" class="h-64"></canvas>
                </div>

                <!-- Aktivitas Izin Terakhir -->
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-4">Aktivitas Izin Terakhir</h3>
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        @forelse ($latestActivities as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 pt-1">
                                    @if ($activity->status == 'diajukan')
                                        <span
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100"><i
                                                class="fa-solid fa-file-import text-blue-500"></i></span>
                                    @elseif ($activity->status == 'disetujui')
                                        <span
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100"><i
                                                class="fa-solid fa-check text-green-500"></i></span>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100"><i
                                                class="fa-solid fa-times text-red-500"></i></span>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm text-gray-800">
                                        @if ($activity->status == 'diajukan')
                                            <span class="font-bold">{{ $activity->user->name }}</span> mengajukan izin
                                            baru.
                                        @else
                                            Izin <span class="font-bold">{{ $activity->user->name }}</span> telah <span
                                                class="font-semibold {{ $activity->status == 'disetujui' ? 'text-green-600' : 'text-red-600' }}">{{ $activity->status }}</span>
                                            oleh {{ $activity->approver->name ?? 'sistem' }}.
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $activity->updated_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center">Belum ada aktivitas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-8">
                <!-- Tren Izin Harian -->
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-4">Tren Izin Harian (30 Hari Terakhir)</h3>
                    <canvas id="trenIzinChart"></canvas>
                </div>
            </div>


            <!-- ================================================== -->
            <!--     BAGIAN 2: STATISTIK IZIN MENINGGALKAN KELAS      -->
            <!-- ================================================== -->
            <h3 class="text-lg font-semibold text-gray-700 mb-4 mt-12">Statistik Izin Meninggalkan Kelas</h3>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Widget Top Siswa Izin Keluar -->
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-4">Top 10 Siswa Sering Izin Keluar</h3>
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @forelse ($topSiswaIzinKeluar as $siswa)
                            <div class="flex justify-between items-center text-sm">
                                <span>{{ $loop->iteration }}. {{ $siswa->name }}</span>
                                <span class="font-bold text-gray-700">{{ $siswa->izin_meninggalkan_kelas_count }}
                                    kali</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada data.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Grafik Rombel Izin Keluar -->
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-4">Izin Keluar per Rombel</h3>
                    <canvas id="rombelIzinKeluarChart"></canvas>
                </div>

                <!-- Grafik Tujuan Izin Keluar -->
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-4">Top 5 Tujuan Izin Keluar</h3>
                    <canvas id="tujuanIzinKeluarChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Data dari Controller
                const statusData = @json($statusChartData);
                const dailyData = @json($dailyChartData);
                const rombelData = @json($rombelChartData);
                const rombelIzinKeluarData = @json($rombelIzinKeluarChartData);
                const tujuanIzinKeluarData = @json($tujuanIzinKeluarChartData);

                // Inisialisasi Chart Izin Tidak Masuk
                if (document.getElementById('statusIzinChart')) new Chart(document.getElementById('statusIzinChart'), {
                    type: 'pie',
                    data: {
                        labels: statusData.labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                        datasets: [{
                            data: statusData.data,
                            backgroundColor: ['rgba(255, 205, 86, 0.8)', 'rgba(75, 192, 192, 0.8)',
                                'rgba(255, 99, 132, 0.8)'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
                if (document.getElementById('trenIzinChart')) new Chart(document.getElementById('trenIzinChart'), {
                    type: 'line',
                    data: {
                        labels: dailyData.labels,
                        datasets: [{
                            label: 'Jumlah Pengajuan',
                            data: dailyData.data,
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                // Inisialisasi Chart Izin Meninggalkan Kelas
                if (document.getElementById('rombelIzinKeluarChart')) new Chart(document.getElementById(
                    'rombelIzinKeluarChart'), {
                    type: 'bar',
                    data: {
                        labels: rombelIzinKeluarData.labels,
                        datasets: [{
                            label: 'Jumlah Izin Keluar',
                            data: rombelIzinKeluarData.data,
                            backgroundColor: 'rgba(255, 159, 64, 0.7)'
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
                if (document.getElementById('tujuanIzinKeluarChart')) new Chart(document.getElementById(
                    'tujuanIzinKeluarChart'), {
                    type: 'doughnut',
                    data: {
                        labels: tujuanIzinKeluarData.labels,
                        datasets: [{
                            data: tujuanIzinKeluarData.data,
                            backgroundColor: ['rgba(153, 102, 255, 0.8)', 'rgba(255, 99, 132, 0.8)',
                                'rgba(75, 192, 192, 0.8)', 'rgba(255, 205, 86, 0.8)',
                                'rgba(201, 203, 207, 0.8)'
                            ],
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
            });
        </script>
    @endpush
</x-app-layout>
