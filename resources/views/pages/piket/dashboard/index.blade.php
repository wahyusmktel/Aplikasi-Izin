<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Guru Piket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ================================================== -->
            <!--   BAGIAN 1: STATISTIK UMUM IZIN TIDAK MASUK       -->
            <!-- ================================================== -->
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Statistik Umum Izin Tidak Masuk</h3>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg mb-4">Daftar Izin Tidak Masuk Hari Ini</h3>
                        <div class="overflow-y-auto max-h-72">
                            <table class="min-w-full">
                                <tbody class="divide-y divide-gray-200">
                                    @forelse ($izinHariIni as $izin)
                                        <tr>
                                            <td class="py-2 whitespace-nowrap">{{ $izin->user->name }}
                                                ({{ $izin->user->masterSiswa?->rombels->first()?->kelas->nama_kelas ?? 'N/A' }})
                                            </td>
                                            <td class="py-2 whitespace-nowrap text-right">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ['diajukan' => 'bg-yellow-100 text-yellow-800', 'disetujui' => 'bg-green-100 text-green-800', 'ditolak' => 'bg-red-100 text-red-800'][$izin->status] }}">
                                                    {{ ucfirst($izin->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="py-2 text-center text-gray-500">Tidak ada data izin tidak masuk
                                                hari ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Proporsi Status Izin</h3>
                        <canvas id="statusIzinChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Total Izin per Kelas</h3>
                        <canvas id="rombelIzinChart"></canvas>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Tren Izin Harian (30 Hari)</h3>
                        <canvas id="trenIzinChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- ================================================== -->
            <!--   BAGIAN 2: STATISTIK PERSONAL IZIN KELUAR KELAS   -->
            <!-- ================================================== -->
            <h3 class="text-lg font-semibold text-gray-700 mb-4 mt-12 pt-4 border-t">Statistik Izin Keluar Kelas (Yang
                Anda Proses)</h3>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div
                    class="lg:col-span-1 bg-indigo-500 text-white p-6 rounded-lg shadow-lg flex flex-col justify-center">
                    <h4 class="text-lg font-semibold">Total Izin Anda Proses</h4>
                    <p class="text-4xl font-bold mt-2">{{ $totalIzinDiprosesPiket }}</p>
                    <p class="text-sm opacity-80">Sepanjang waktu</p>
                </div>
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg mb-4">Tren Izin yang Anda Proses (30 Hari Terakhir)</h3>
                        <canvas id="trenIzinPribadiChart"></canvas>
                    </div>
                </div>
                <div class="lg:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg mb-4">Top 5 Tujuan Izin yang Anda Setujui</h3>
                        <canvas id="tujuanIzinPribadiChart"></canvas>
                    </div>
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
                const dailyDataPiket = @json($dailyChartDataPiket);
                const tujuanDataPiket = @json($tujuanChartDataPiket);

                // Inisialisasi Chart Lama (Izin Tidak Masuk)
                if (document.getElementById('statusIzinChart')) new Chart(document.getElementById('statusIzinChart')
                    .getContext('2d'), {
                        type: 'pie',
                        data: {
                            labels: statusData.labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                            datasets: [{
                                data: statusData.data,
                                backgroundColor: ['rgba(255, 205, 86, 0.8)', 'rgba(75, 192, 192, 0.8)',
                                    'rgba(255, 99, 132, 0.8)'
                                ]
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
                if (document.getElementById('rombelIzinChart')) new Chart(document.getElementById('rombelIzinChart')
                    .getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: rombelData.labels,
                            datasets: [{
                                label: 'Jumlah Izin',
                                data: rombelData.data,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)'
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                if (document.getElementById('trenIzinChart')) new Chart(document.getElementById('trenIzinChart')
                    .getContext('2d'), {
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

                // Inisialisasi Chart Baru (Izin Keluar Kelas - Personal)
                if (document.getElementById('trenIzinPribadiChart')) new Chart(document.getElementById(
                    'trenIzinPribadiChart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: dailyDataPiket.labels,
                        datasets: [{
                            label: 'Jumlah Izin Diproses',
                            data: dailyDataPiket.data,
                            borderColor: 'rgba(153, 102, 255, 1)',
                            tension: 0.1,
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            fill: true
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
                if (document.getElementById('tujuanIzinPribadiChart') && tujuanDataPiket.data.length > 0) new Chart(
                    document.getElementById('tujuanIzinPribadiChart').getContext('2d'), {
                        type: 'pie',
                        data: {
                            labels: tujuanDataPiket.labels,
                            datasets: [{
                                data: tujuanDataPiket.data,
                                backgroundColor: ['rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)', 'rgba(75, 192, 192, 0.8)',
                                    'rgba(201, 203, 207, 0.8)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'left'
                                }
                            }
                        }
                    });
            });
        </script>
    @endpush
</x-app-layout>
