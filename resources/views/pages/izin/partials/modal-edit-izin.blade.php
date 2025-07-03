<x-modal :name="'edit-izin-modal-'.$izin->id" :show="$errors->isNotEmpty() && session('open_modal') == 'edit-izin-modal-'.$izin->id" focusable>
    <form method="post" action="{{ route('izin.update', $izin->id) }}" class="p-6" enctype="multipart/form-data">
        @csrf
        @method('put')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Edit Form Izin Tidak Masuk') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui detail perizinan Anda. Pastikan data yang diisi sudah benar.') }}
        </p>

        <div class="mt-6">
            <x-input-label for="tanggal_izin_{{ $izin->id }}" value="{{ __('Tanggal Izin') }}" />
            <x-text-input id="tanggal_izin_{{ $izin->id }}" name="tanggal_izin" type="date" class="mt-1 block w-full" :value="old('tanggal_izin', $izin->tanggal_izin)" required />
            <x-input-error :messages="$errors->get('tanggal_izin')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="keterangan_{{ $izin->id }}" value="{{ __('Alasan/Keterangan') }}" />
            <textarea id="keterangan_{{ $izin->id }}" name="keterangan" class="summernote" required>{{ old('keterangan', $izin->keterangan) }}</textarea>
            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="dokumen_pendukung_{{ $izin->id }}" value="{{ __('Ganti Dokumen Pendukung (Opsional)') }}" />
            <x-text-input id="dokumen_pendukung_{{ $izin->id }}" name="dokumen_pendukung" type="file" class="mt-1 block w-full" />
            @if ($izin->dokumen_pendukung)
                <small class="text-xs text-gray-500">Dokumen saat ini: <a href="{{ Storage::url($izin->dokumen_pendukung) }}" target="_blank" class="text-blue-600">Lihat</a>. Kosongkan jika tidak ingin mengganti.</small>
            @endif
            <x-input-error :messages="$errors->get('dokumen_pendukung')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-primary-button class="ms-3">
                {{ __('Perbarui Pengajuan') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>