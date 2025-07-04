<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kurikulum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Widget Ringkasan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-indigo-500 text-white p-6 rounded-lg shadow-lg">
                    <h4 class="text-lg font-semibold">Total Guru</h4>
                    <p class="text-3xl font-bold mt-2">{{ $totalGuru }}</p>
                </div>
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                    <h4 class="text-lg font-semibold">Total Mata Pelajaran</h4>
                    <p class="text-3xl font-bold mt-2">{{ $totalMapel }}</p>
                </div>
                <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
                    <h4 class="text-lg font-semibold">Total Rombel Aktif</h4>
                    <p class="text-3xl font-bold mt-2">{{ $totalRombel }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Widget Jadwal Hari Ini -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg mb-4">Jadwal Hari Ini:
                            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h3>
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @forelse ($jadwalHariIni as $namaKelas => $jadwals)
                                <div class="p-3 border rounded-lg">
                                    <h4 class="font-bold text-gray-800">{{ $namaKelas }}</h4>
                                    <table class="min-w-full mt-2">
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($jadwals as $jadwal)
                                                <tr class="text-sm">
                                                    <td class="py-1 px-1 w-1/4">{{ $jadwal->jam_mulai }} -
                                                        {{ $jadwal->jam_selesai }}</td>
                                                    <td class="py-1 px-1 w-1/2">{{ $jadwal->mataPelajaran->nama_mapel }}
                                                    </td>
                                                    <td class="py-1 px-1 w-1/4 text-gray-600 truncate">
                                                        {{ $jadwal->guru->nama_lengkap }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">Tidak ada jadwal untuk hari ini.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Chart Mapel Paling Banyak Jamnya -->
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Top 7 Mapel (Total Jam)</h3>
                        <canvas id="mapelChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mapelChartData = @json($mapelChartData);

                if (document.getElementById('mapelChart') && mapelChartData.data.length > 0) {
                    const ctxMapel = document.getElementById('mapelChart').getContext('2d');
                    new Chart(ctxMapel, {
                        type: 'doughnut',
                        data: {
                            labels: mapelChartData.labels,
                            datasets: [{
                                label: 'Total Jam',
                                data: mapelChartData.data,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)', 'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)', 'rgba(255, 159, 64, 0.8)',
                                    'rgba(199, 199, 199, 0.8)'
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
                }
            });
        </script>
    @endpush
</x-app-layout>
