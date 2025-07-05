<div>
    <x-input-label for="jam_ke" :value="__('Jam Ke')" />
    {{-- Periksa apakah $jamPelajaran ada sebelum digunakan --}}
    <x-text-input id="jam_ke" class="block mt-1 w-full" type="number" name="jam_ke" :value="old('jam_ke', isset($jamPelajaran) ? $jamPelajaran->jam_ke : '')" required
        autofocus />
    <x-input-error :messages="$errors->get('jam_ke')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="jam_mulai" :value="__('Jam Mulai')" />
    {{-- Gunakan isset() untuk keamanan --}}
    <x-text-input id="jam_mulai" class="block mt-1 w-full" type="time" name="jam_mulai" :value="old(
        'jam_mulai',
        isset($jamPelajaran) ? \Carbon\Carbon::parse($jamPelajaran->jam_mulai)->format('H:i') : '',
    )" required />
    <x-input-error :messages="$errors->get('jam_mulai')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="jam_selesai" :value="__('Jam Selesai')" />
    {{-- Gunakan isset() untuk keamanan --}}
    <x-text-input id="jam_selesai" class="block mt-1 w-full" type="time" name="jam_selesai" :value="old(
        'jam_selesai',
        isset($jamPelajaran) ? \Carbon\Carbon::parse($jamPelajaran->jam_selesai)->format('H:i') : '',
    )"
        required />
    <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="keterangan" :value="__('Keterangan (Contoh: Istirahat, Sholat Jumat)')" />
    {{-- Gunakan isset() untuk keamanan --}}
    <x-text-input id="keterangan" class="block mt-1 w-full" type="text" name="keterangan" :value="old('keterangan', isset($jamPelajaran) ? $jamPelajaran->keterangan : '')" />
    <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('kurikulum.jam-pelajaran.index') }}">
        <x-secondary-button type="button">{{ __('Batal') }}</x-secondary-button>
    </a>
    <x-primary-button class="ms-4">{{ __('Simpan') }}</x-primary-button>
</div>
