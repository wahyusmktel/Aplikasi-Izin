<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Pindai QR Code') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <h3 class="text-xl font-bold border-b pb-3 mb-6">Detail Izin Siswa</h3>

                    <div class="space-y-3 text-lg">
                        <p><strong class="w-24 inline-block">Nama</strong>: {{ $izin->siswa->name }}</p>
                        <p><strong class="w-24 inline-block">Kelas</strong>:
                            {{ $izin->siswa->masterSiswa?->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}</p>
                        <p><strong class="w-24 inline-block">Tujuan</strong>: {{ $izin->tujuan }}</p>
                        <p><strong class="w-24 inline-block">Status</strong>:
                            <span
                                class="px-2 py-1 text-sm font-semibold rounded-full 
                                @if (in_array($izin->status, ['diverifikasi_security', 'selesai'])) bg-green-100 text-green-800
                                @elseif($izin->status == 'ditolak') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ str_replace('_', ' ', Str::title($izin->status)) }}
                            </span>
                        </p>
                    </div>

                    <div class="mt-8 border-t pt-6 text-center">
                        @if ($izin->status == 'disetujui_guru_piket')
                            <h4 class="text-md font-semibold mb-3">Aksi yang Diperlukan:</h4>
                            <form action="{{ route('security.verifikasi.keluar', $izin->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-blue-500 text-lg">
                                    Verifikasi Siswa Keluar
                                </button>
                            </form>
                        @elseif ($izin->status == 'diverifikasi_security')
                            <h4 class="text-md font-semibold mb-3">Aksi yang Diperlukan:</h4>
                            <form action="{{ route('security.verifikasi.kembali', $izin->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-green-500 text-lg">
                                    Verifikasi Siswa Kembali
                                </button>
                            </form>
                        @else
                            <p class="text-gray-600">Tidak ada aksi yang diperlukan untuk status izin ini.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('security.verifikasi.scan') }}"
                    class="text-sm text-indigo-600 hover:underline">Kembali ke Halaman Pindai</a>
            </div>
        </div>
    </div>
</x-app-layout>
