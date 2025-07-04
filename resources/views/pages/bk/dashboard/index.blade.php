<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Guru BK') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Widget Izin Hari Ini -->
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">Izin Hari Ini ({{ $izinHariIni->count() }})</h3>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @forelse ($izinHariIni as $izin)
                                <div class="flex items-center">
                                    <span class="text-sm w-2/3 truncate">{{ $izin->user->name }}</span>
                                    <span class="text-xs text-white rounded-full px-2 py-0.5
                                        @if($izin->status == 'disetujui') bg-green-500
                                        @elseif($izin->status == 'ditolak') bg-red-500
                                        @else bg-yellow-500 @endif">
                                        {{ ucfirst($izin->status) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Tidak ada izin hari ini.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Chart Siswa Sering Izin -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold mb-4">5 Siswa Paling Sering Izin (Semua Waktu)</h3>
                        <canvas id="topSiswaChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const topSiswaData = @json($topSiswaChartData);

        if (document.getElementById('topSiswaChart') && topSiswaData.data.length > 0) {
            const ctxTopSiswa = document.getElementById('topSiswaChart').getContext('2d');
            new Chart(ctxTopSiswa, {
                type: 'bar',
                data: {
                    labels: topSiswaData.labels,
                    datasets: [{
                        label: 'Jumlah Izin',
                        data: topSiswaData.data,
                        backgroundColor: 'rgba(153, 102, 255, 0.7)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }
    });
</script>
@endpush
</x-app-layout>
