<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Perizinan (Piket)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Form Filter -->
                    <form action="{{ route('piket.monitoring.index') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <input type="text" name="search" placeholder="Cari Nama Siswa..." class="border-gray-300 rounded-md shadow-sm" value="{{ request('search') }}">
                            <input type="date" name="start_date" class="border-gray-300 rounded-md shadow-sm" value="{{ request('start_date') }}">
                            <input type="date" name="end_date" class="border-gray-300 rounded-md shadow-sm" value="{{ request('end_date') }}">
                            <select name="status" class="border-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Status</option>
                                <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <select name="kelas_id" class="border-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                                @endforeach
                            </select>
                            <div class="lg:col-span-5 flex justify-end">
                                <x-primary-button type="submit">Filter</x-primary-button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Data -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                             <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Izin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diproses Oleh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($perizinan as $izin)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $izin->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $izin->user->masterSiswa?->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($izin->tanggal_izin)->isoFormat('D MMM YY') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ['diajukan' => 'bg-yellow-100 text-yellow-800', 'disetujui' => 'bg-green-100 text-green-800', 'ditolak' => 'bg-red-100 text-red-800'][$izin->status] }}">
                                                {{ ucfirst($izin->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $izin->approver->name ?? 'Belum diproses' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-4 text-center">Tidak ada data untuk ditampilkan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $perizinan->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
