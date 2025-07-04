<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Guru Piket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Baris Atas: Widget & Pie Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg mb-4">Daftar Izin Hari Ini: {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</h3>
                        <div class="overflow-y-auto max-h-72">
                            <table class="min-w-full">
                                <tbody class="divide-y divide-gray-200">
                                    @forelse ($izinHariIni as $izin)
                                        <tr>
                                            <td class="py-2 whitespace-nowrap">{{ $izin->user->name }} ({{ $izin->user->masterSiswa?->rombels->first()?->kelas->nama_kelas ?? 'N/A' }})</td>
                                            <td class="py-2 whitespace-nowrap text-right">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ['diajukan' => 'bg-yellow-100 text-yellow-800', 'disetujui' => 'bg-green-100 text-green-800', 'ditolak' => 'bg-red-100 text-red-800'][$izin->status] }}">
                                                    {{ ucfirst($izin->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td class="py-2 text-center text-gray-500">Tidak ada data izin untuk hari ini.</td></tr>
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

            <!-- Baris Bawah: Bar & Line Chart -->
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
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusData = @json($statusChartData);
        const dailyData = @json($dailyChartData);
        const rombelData = @json($rombelChartData);

        // Pie Chart Status
        if (document.getElementById('statusIzinChart') && statusData.data.length > 0) {
            new Chart(document.getElementById('statusIzinChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: statusData.labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                    datasets: [{ data: statusData.data, backgroundColor: ['rgba(255, 205, 86, 0.8)', 'rgba(75, 192, 192, 0.8)', 'rgba(255, 99, 132, 0.8)'] }]
                },
                options: { responsive: true, plugins: { legend: { position: 'top' } } }
            });
        }

        // Bar Chart Rombel
        if (document.getElementById('rombelIzinChart') && rombelData.data.length > 0) {
            new Chart(document.getElementById('rombelIzinChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: rombelData.labels,
                    datasets: [{ label: 'Jumlah Izin', data: rombelData.data, backgroundColor: 'rgba(54, 162, 235, 0.7)' }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        }

        // Line Chart Tren Harian
        if (document.getElementById('trenIzinChart')) {
            new Chart(document.getElementById('trenIzinChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: dailyData.labels,
                    datasets: [{ label: 'Jumlah Pengajuan', data: dailyData.data, borderColor: 'rgb(75, 192, 192)', tension: 0.1 }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });
        }
    });
</script>
@endpush
</x-app-layout>
