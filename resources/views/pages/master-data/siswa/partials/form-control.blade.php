<div>
    <x-input-label for="nis" :value="__('Nomor Induk Siswa (NIS)')" />
    <x-text-input id="nis" class="block mt-1 w-full" type="text" name="nis" :value="old('nis', $siswa->nis ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('nis')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
    <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap', $siswa->nama_lengkap ?? '')" required />
    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
    <select name="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
    </select>
    <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
    <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir', $siswa->tanggal_lahir ?? '')" />
    <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
</div>
<div class="mt-4">
    <x-input-label for="alamat" :value="__('Alamat')" />
    <textarea name="alamat" id="alamat" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
</div>
<div class="flex items-center justify-end mt-4">
    <a href="{{ route('master-data.siswa.index') }}">
        <x-secondary-button type="button">{{ __('Batal') }}</x-secondary-button>
    </a>
    <x-primary-button class="ms-4">{{ __('Simpan') }}</x-primary-button>
</div>