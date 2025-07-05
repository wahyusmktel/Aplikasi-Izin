<x-modal name="ajukan-izin-keluar-kelas" focusable>
    <form method="post" action="{{ route('siswa.izin-keluar-kelas.store') }}" class="p-6">
        @csrf
        <h2 class="text-lg font-medium text-gray-900">Form Izin Meninggalkan Kelas</h2>
        <p class="mt-1 text-sm text-gray-600">Isi detail tujuan Anda dan perkirakan kapan akan kembali ke kelas.</p>

        @if ($jadwalSaatIni)
            <div class="mt-4 p-3 bg-indigo-50 border border-indigo-200 rounded-md">
                <p class="text-sm font-semibold text-indigo-800">Informasi Jam Pelajaran Saat Ini:</p>
                <p class="text-sm text-gray-700"><strong>Mata Pelajaran:</strong>
                    {{ $jadwalSaatIni->mataPelajaran->nama_mapel }}</p>
                <p class="text-sm text-gray-700"><strong>Guru Pengajar:</strong> {{ $jadwalSaatIni->guru->nama_lengkap }}
                </p>
                <input type="hidden" name="jadwal_pelajaran_id" value="{{ $jadwalSaatIni->id }}">
            </div>
        @else
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                <p class="text-sm font-semibold text-yellow-800">Saat ini sedang tidak ada jam pelajaran.</p>
            </div>
        @endif

        <div class="mt-6">
            <x-input-label for="tujuan" value="Tujuan (Contoh: UKS, Perpustakaan, Ruang BK)" />
            <x-text-input id="tujuan" name="tujuan" type="text" class="mt-1 block w-full" :value="old('tujuan')"
                required />
            <x-input-error :messages="$errors->get('tujuan')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="estimasi_kembali" value="Estimasi Jam Kembali" />
            <x-text-input id="estimasi_kembali" name="estimasi_kembali" type="time" class="mt-1 block w-full"
                :value="old('estimasi_kembali')" required />
            <x-input-error :messages="$errors->get('estimasi_kembali')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="keterangan" value="Keterangan Tambahan (Opsional)" />
            <textarea id="keterangan" name="keterangan"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                rows="3">{{ old('keterangan') }}</textarea>
            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
            <x-primary-button class="ms-3">Kirim Pengajuan</x-primary-button>
        </div>
    </form>
</x-modal>
