<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kesiswaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Proporsi Status Izin</h3>
                        <canvas id="statusIzinChart" class="h-64"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Aktivitas Izin Terakhir</h3>
                        <div class="space-y-4">
                            @forelse ($latestActivities as $activity)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 pt-1">
                                        @if ($activity->status == 'diajukan')
                                            <span
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                                <i class="fa-solid fa-file-import text-blue-500"></i>
                                            </span>
                                        @elseif ($activity->status == 'disetujui')
                                            <span
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                                <i class="fa-solid fa-check text-green-500"></i>
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100">
                                                <i class="fa-solid fa-times text-red-500"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-sm text-gray-800">
                                            @if ($activity->status == 'diajukan')
                                                <span class="font-bold">{{ $activity->user->name }}</span> mengajukan
                                                izin baru.
                                            @elseif ($activity->status == 'disetujui')
                                                Izin <span class="font-bold">{{ $activity->user->name }}</span> telah
                                                <span class="font-semibold text-green-600">disetujui</span> oleh
                                                {{ $activity->approver->name ?? 'sistem' }}.
                                            @else
                                                Izin <span class="font-bold">{{ $activity->user->name }}</span> telah
                                                <span class="font-semibold text-red-600">ditolak</span> oleh
                                                {{ $activity->approver->name ?? 'sistem' }}.
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $activity->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center">Belum ada aktivitas.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Total Izin per Rombel</h3>
                        <canvas id="rombelIzinChart"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Tren Izin Harian (30 Hari Terakhir)</h3>
                        <canvas id="trenIzinChart"></canvas>
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

                // Inisialisasi Pie Chart Status Izin
                const ctxStatus = document.getElementById('statusIzinChart').getContext('2d');
                new Chart(ctxStatus, {
                    type: 'pie',
                    data: {
                        labels: statusData.labels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                        datasets: [{
                            label: 'Jumlah Izin',
                            data: statusData.data,
                            backgroundColor: ['rgba(255, 205, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
                                'rgba(255, 99, 132, 0.7)'
                            ],
                            borderColor: ['rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
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

                // Inisialisasi Bar Chart Izin per Rombel
                const ctxRombel = document.getElementById('rombelIzinChart').getContext('2d');
                new Chart(ctxRombel, {
                    type: 'bar',
                    data: {
                        labels: rombelData.labels,
                        datasets: [{
                            label: 'Jumlah Izin',
                            data: rombelData.data,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Membuat bar chart menjadi horizontal
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

                // Inisialisasi Line Chart Tren Izin
                const ctxTren = document.getElementById('trenIzinChart').getContext('2d');
                new Chart(ctxTren, {
                    type: 'line',
                    data: {
                        labels: dailyData.labels,
                        datasets: [{
                            label: 'Jumlah Pengajuan Izin',
                            data: dailyData.data,
                            fill: false,
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
            });
        </script>
    @endpush
</x-app-layout>
