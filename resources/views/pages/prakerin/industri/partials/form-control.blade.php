<div class="space-y-4">
    <div><x-input-label for="nama_industri" value="Nama Industri" /><x-text-input id="nama_industri"
            class="block mt-1 w-full" type="text" name="nama_industri" :value="old('nama_industri', $industri->nama_industri ?? '')" required autofocus /></div>
    <div><x-input-label for="alamat" value="Alamat Lengkap" />
        <textarea name="alamat" id="alamat" rows="3" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat', $industri->alamat ?? '') }}</textarea>
    </div>
    <div><x-input-label for="kota" value="Kota/Kabupaten" /><x-text-input id="kota" class="block mt-1 w-full"
            type="text" name="kota" :value="old('kota', $industri->kota ?? '')" required /></div>
    <div><x-input-label for="telepon" value="Telepon" /><x-text-input id="telepon" class="block mt-1 w-full"
            type="text" name="telepon" :value="old('telepon', $industri->telepon ?? '')" /></div>
    <div><x-input-label for="nama_pic" value="Nama PIC (Person in Charge)" /><x-text-input id="nama_pic"
            class="block mt-1 w-full" type="text" name="nama_pic" :value="old('nama_pic', $industri->nama_pic ?? '')" /></div>
    <div><x-input-label for="email_pic" value="Email PIC" /><x-text-input id="email_pic" class="block mt-1 w-full"
            type="email" name="email_pic" :value="old('email_pic', $industri->email_pic ?? '')" /></div>
    <div class="flex items-center justify-end pt-4 border-t"><a
            href="{{ route('prakerin.industri.index') }}"><x-secondary-button
                type="button">{{ __('Batal') }}</x-secondary-button></a><x-primary-button
            class="ms-4">{{ __('Simpan') }}</x-primary-button></div>
</div>
