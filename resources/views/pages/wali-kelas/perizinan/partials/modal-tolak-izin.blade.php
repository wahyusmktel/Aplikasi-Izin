<x-modal :name="'tolak-izin-modal-'.$izin->id" focusable>
    <form method="post" action="{{ route('wali-kelas.perizinan.reject', $izin->id) }}" class="p-6">
        @csrf
        @method('patch')

        <h2 class="text-lg font-medium text-gray-900">
            Tolak Pengajuan Izin: {{ $izin->user->name }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Silakan berikan alasan yang jelas mengapa pengajuan ini ditolak. Alasan ini akan dapat dilihat oleh siswa.
        </p>

        <div class="mt-6">
            <x-input-label for="alasan_penolakan_{{ $izin->id }}" value="Alasan Penolakan" class="sr-only" />
            <textarea id="alasan_penolakan_{{ $izin->id }}" name="alasan_penolakan" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Tulis alasan penolakan di sini..." required rows="4">{{ old('alasan_penolakan') }}</textarea>
            <x-input-error :messages="$errors->get('alasan_penolakan')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                Batal
            </x-secondary-button>

            <x-danger-button class="ms-3">
                Tolak Pengajuan
            </x-danger-button>
        </div>
    </form>
</x-modal>