<div>
    <x-input-label for="kode_mapel" :value="__('Kode Mata Pelajaran')" />
    <x-text-input id="kode_mapel" class="block mt-1 w-full" type="text" name="kode_mapel" :value="old('kode_mapel', $mataPelajaran->kode_mapel ?? '')" required
        autofocus />
    <x-input-error :messages="$errors->get('kode_mapel')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="nama_mapel" :value="__('Nama Mata Pelajaran')" />
    <x-text-input id="nama_mapel" class="block mt-1 w-full" type="text" name="nama_mapel" :value="old('nama_mapel', $mataPelajaran->nama_mapel ?? '')" required />
    <x-input-error :messages="$errors->get('nama_mapel')" class="mt-2" />
</div>
<div class="flex items-center justify-end mt-4">
    <a href="{{ route('kurikulum.mata-pelajaran.index') }}">
        <x-secondary-button type="button">{{ __('Batal') }}</x-secondary-button>
    </a>
    <x-primary-button class="ms-4">{{ __('Simpan') }}</x-primary-button>
</div>
