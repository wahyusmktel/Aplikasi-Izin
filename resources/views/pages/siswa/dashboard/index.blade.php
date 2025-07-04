<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Widget Ringkasan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                    <h4 class="text-lg font-semibold">Total Diajukan</h4>
                    <p class="text-3xl font-bold mt-2">{{ $totalDiajukan }}</p>
                </div>
                <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
                    <h4 class="text-lg font-semibold">Total Disetujui</h4>
                    <p class="text-3xl font-bold mt-2">{{ $totalDisetujui }}</p>
                </div>
                <div class="bg-red-500 text-white p-6 rounded-lg shadow-lg">
                    <h4 class="text-lg font-semibold">Total Ditolak</h4>
                    <p class="text-3xl font-bold mt-2">{{ $totalDitolak }}</p>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold mb-4">Rekapitulasi Status Izin Saya</h3>
                    <div class="max-w-md mx-auto">
                        <canvas id="statusIzinChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusData = @json($statusChartData);

        if (document.getElementById('statusIzinChart') && statusData.data.length > 0) {
            const ctxStatus = document.getElementById('statusIzinChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'pie',
                data: {
                    labels: statusData.labels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                    datasets: [{
                        label: 'Jumlah Izin',
                        data: statusData.data,
                        backgroundColor: [
                            'rgba(255, 205, 86, 0.8)', // Diajukan (Kuning)
                            'rgba(75, 192, 192, 0.8)', // Disetujui (Hijau)
                            'rgba(255, 99, 132, 0.8)',  // Ditolak (Merah)
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                    }
                }
            });
        }
    });
</script>
@endpush
</x-app-layout>
