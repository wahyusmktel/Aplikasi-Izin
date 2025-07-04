<div>
    <x-input-label for="nuptk" :value="__('NUPTK (Opsional)')" />
    <x-text-input id="nuptk" class="block mt-1 w-full" type="text" name="nuptk" :value="old('nuptk', $masterGuru->nuptk ?? '')" autofocus />
    <x-input-error :messages="$errors->get('nuptk')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
    <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap', $masterGuru->nama_lengkap ?? '')" required />
    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
    <select name="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
        <option value="L" {{ old('jenis_kelamin', $masterGuru->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
        <option value="P" {{ old('jenis_kelamin', $masterGuru->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
    </select>
    <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
</div>
<div class="flex items-center justify-end mt-4">
    <a href="{{ route('kurikulum.master-guru.index') }}">
        <x-secondary-button type="button">{{ __('Batal') }}</x-secondary-button>
    </a>
    <x-primary-button class="ms-4">{{ __('Simpan') }}</x-primary-button>
</div>
