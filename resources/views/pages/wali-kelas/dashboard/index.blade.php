<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Wali Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                <!-- Chart Status Izin (Pie Chart) -->
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Status Izin Kelas Anda</h3>
                        <canvas id="statusIzinChart" class="h-64"></canvas>
                    </div>
                </div>

                <!-- Widget Aktivitas Terakhir -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Aktivitas Terakhir di Kelas Anda</h3>
                        <div class="space-y-4">
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
                                                <span class="font-bold">{{ $activity->user->name }}</span> mengajukan
                                                izin baru.
                                            @else
                                                Izin <span class="font-bold">{{ $activity->user->name }}</span> telah
                                                <span
                                                    class="font-semibold {{ $activity->status == 'disetujui' ? 'text-green-600' : 'text-red-600' }}">{{ $activity->status }}</span>.
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $activity->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center">Belum ada aktivitas.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Aktivitas Izin Meninggalkan Kelas Terakhir</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Siswa</th>
                                        <th class="px-4 py-2 text-left">Tujuan</th>
                                        <th class="px-4 py-2 text-left">Waktu</th>
                                        <th class="px-4 py-2 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-sm">
                                    @forelse ($izinKeluarTerakhir as $izin)
                                        <tr>
                                            <td class="px-4 py-2">{{ $izin->siswa->name }}</td>
                                            <td class="px-4 py-2">{{ $izin->tujuan }}</td>
                                            <td class="px-4 py-2">{{ $izin->updated_at->diffForHumans() }}</td>
                                            <td class="px-4 py-2">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if (in_array($izin->status, ['selesai', 'terlambat'])) bg-gray-100 text-gray-800
                                                    @elseif(in_array($izin->status, ['disetujui_guru_piket', 'diverifikasi_security'])) bg-green-100 text-green-800
                                                    @elseif($izin->status == 'ditolak') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ str_replace('_', ' ', Str::title($izin->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-4 text-center text-gray-500">Belum ada
                                                aktivitas izin meninggalkan kelas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Tren Izin Harian (Line Chart) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold mb-4">Tren Izin Harian Kelas Anda (15 Hari Terakhir)</h3>
                    <canvas id="trenIzinChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const statusData = @json($statusChartData);
                const dailyData = @json($dailyChartData);

                // Inisialisasi Pie Chart
                if (document.getElementById('statusIzinChart') && statusData.data.length > 0) {
                    const ctxStatus = document.getElementById('statusIzinChart').getContext('2d');
                    new Chart(ctxStatus, {
                        type: 'pie',
                        data: {
                            labels: statusData.labels.map(label => label.charAt(0).toUpperCase() + label.slice(
                                1)),
                            datasets: [{
                                data: statusData.data,
                                backgroundColor: ['rgba(255, 205, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
                                    'rgba(255, 99, 132, 0.7)'
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
                }

                // Inisialisasi Line Chart
                if (document.getElementById('trenIzinChart')) {
                    const ctxTren = document.getElementById('trenIzinChart').getContext('2d');
                    new Chart(ctxTren, {
                        type: 'line',
                        data: {
                            labels: dailyData.labels,
                            datasets: [{
                                label: 'Jumlah Pengajuan Izin',
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
                }
            });
        </script>
    @endpush
</x-app-layout>
