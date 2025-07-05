<x-modal :name="'tolak-izin-piket-' . $izin->id" focusable>
    <form method="post" action="{{ route('piket.persetujuan-izin-keluar.reject', $izin->id) }}" class="p-6">
        @csrf
        @method('patch')
        <h2 class="text-lg font-medium text-gray-900">Tolak Izin: {{ $izin->siswa->name }}</h2>
        <p class="mt-1 text-sm text-gray-600">Berikan alasan penolakan.</p>
        <div class="mt-6">
            <x-input-label for="alasan_penolakan_piket_{{ $izin->id }}" value="Alasan Penolakan" class="sr-only" />
            <textarea id="alasan_penolakan_piket_{{ $izin->id }}" name="alasan_penolakan"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required rows="3">{{ old('alasan_penolakan') }}</textarea>
            <x-input-error :messages="$errors->get('alasan_penolakan')" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
            <x-danger-button class="ms-3">Tolak</x-danger-button>
        </div>
    </form>
</x-modal>
