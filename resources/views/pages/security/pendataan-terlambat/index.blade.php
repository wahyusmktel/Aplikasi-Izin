<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pendataan Siswa Terlambat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">1. Cari Siswa</h3>
                    <form action="{{ route('security.pendataan-terlambat.index') }}" method="GET">
                        <div class="flex items-center space-x-2">
                            <x-text-input id="search" name="search" type="text" class="w-full"
                                placeholder="Ketik NIS atau Nama Siswa..." :value="request('search')" required />
                            <x-primary-button type="submit">Cari</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            @if (isset($hasilPencarian))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg mb-4">2. Catat Keterlambatan</h3>
                        @if ($hasilPencarian->count() == 1)
                            @php $siswa = $hasilPencarian->first(); @endphp
                            <form action="{{ route('security.pendataan-terlambat.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="master_siswa_id" value="{{ $siswa->id }}">
                                <div class="space-y-4">
                                    <div>
                                        <p><strong>Nama:</strong> {{ $siswa->nama_lengkap }}</p>
                                        <p><strong>NIS:</strong> {{ $siswa->nis }}</p>
                                        <p><strong>Kelas:</strong>
                                            {{ $siswa->rombels->first()?->kelas->nama_kelas ?? 'Belum ada rombel' }}</p>
                                    </div>
                                    <div>
                                        <x-input-label for="alasan_siswa"
                                            value="Alasan Keterlambatan (menurut siswa)" />
                                        <textarea name="alasan_siswa" id="alasan_siswa" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"
                                            required>{{ old('alasan_siswa') }}</textarea>
                                    </div>
                                    <div class="flex justify-end">
                                        <x-primary-button type="submit">Simpan Data</x-primary-button>
                                    </div>
                                </div>
                            </form>
                        @elseif ($hasilPencarian->count() > 1)
                            <p class="text-yellow-700">Ditemukan beberapa siswa. Mohon gunakan NIS untuk pencarian yang
                                lebih spesifik.</p>
                        @else
                            <p class="text-red-700">Siswa tidak ditemukan.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
