<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pengajuan Dispensasi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('dispensasi.pengajuan.store') }}" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="nama_kegiatan" value="Nama Kegiatan" />
                            <x-text-input id="nama_kegiatan" name="nama_kegiatan" type="text"
                                class="mt-1 block w-full" :value="old('nama_kegiatan')" required />
                        </div>
                        <div>
                            <x-input-label for="keterangan" value="Keterangan/Deskripsi Kegiatan" />
                            <textarea id="keterangan" name="keterangan" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('keterangan') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="waktu_mulai" value="Waktu Mulai" />
                                <x-text-input id="waktu_mulai" name="waktu_mulai" type="datetime-local"
                                    class="mt-1 block w-full" :value="old('waktu_mulai')" required />
                            </div>
                            <div>
                                <x-input-label for="waktu_selesai" value="Waktu Selesai" />
                                <x-text-input id="waktu_selesai" name="waktu_selesai" type="datetime-local"
                                    class="mt-1 block w-full" :value="old('waktu_selesai')" required />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="pilih_siswa" value="Pilih Siswa yang Terlibat" />
                            <select id="pilih_siswa" name="siswa_ids[]" multiple
                                placeholder="Ketik nama atau NIS siswa...">
                                @foreach ($siswa as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_lengkap }}
                                        ({{ $s->rombels->first()?->kelas->nama_kelas ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-end pt-4 border-t">
                            <a href="{{ route('dispensasi.pengajuan.index') }}"><x-secondary-button
                                    type="button">Batal</x-secondary-button></a>
                            <x-primary-button class="ms-3">Kirim Pengajuan</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            new TomSelect("#pilih_siswa", {
                plugins: ['remove_button'],
                create: false,
            });
        </script>
    @endpush
</x-app-layout>
