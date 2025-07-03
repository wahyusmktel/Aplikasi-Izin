<div>
    <x-input-label for="nama_kelas" :value="__('Nama Kelas (Contoh: X TKJ 1)')" />
    <x-text-input id="nama_kelas" class="block mt-1 w-full" type="text" name="nama_kelas" :value="old('nama_kelas', $kelas->nama_kelas ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('nama_kelas')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="jurusan" :value="__('Jurusan (Contoh: Teknik Komputer Jaringan)')" />
    <x-text-input id="jurusan" class="block mt-1 w-full" type="text" name="jurusan" :value="old('jurusan', $kelas->jurusan ?? '')" required />
    <x-input-error :messages="$errors->get('jurusan')" class="mt-2" />
</div>
<div class="flex items-center justify-end mt-4">
    <a href="{{ route('master-data.kelas.index') }}">
        <x-secondary-button type="button">{{ __('Batal') }}</x-secondary-button>
    </a>
    <x-primary-button class="ms-4">{{ __('Simpan') }}</x-primary-button>
</div>