<x-modal name="ajukan-izin-modal" :show="$errors->isNotEmpty()" focusable>
    <form method="post" action="{{ route('izin.store') }}" class="p-6" enctype="multipart/form-data">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Form Izin Tidak Masuk Sekolah') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Silakan isi detail perizinan Anda. Pastikan data yang diisi sudah benar.') }}
        </p>

        <div class="mt-6">
            <x-input-label for="tanggal_izin" value="{{ __('Tanggal Izin') }}" />
            <x-text-input id="tanggal_izin" name="tanggal_izin" type="date" class="mt-1 block w-full" :value="old('tanggal_izin')" required />
            <x-input-error :messages="$errors->get('tanggal_izin')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="keterangan" value="{{ __('Alasan/Keterangan') }}" />
            <textarea id="keterangan" name="keterangan" class="summernote" required>{{ old('keterangan') }}</textarea>
            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="dokumen_pendukung" value="{{ __('Dokumen Pendukung (Opsional)') }}" />
            <x-text-input id="dokumen_pendukung" name="dokumen_pendukung" type="file" class="mt-1 block w-full" />
            <small class="text-xs text-gray-500">Contoh: Surat Keterangan Dokter. Tipe file: JPG, PNG, PDF.</small>
            <x-input-error :messages="$errors->get('dokumen_pendukung')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-primary-button class="ms-3">
                {{ __('Kirim Pengajuan') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>