<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Form Penempatan Siswa Prakerin') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('prakerin.penempatan.store') }}" class="space-y-4">
                        @csrf
                        <div><x-input-label for="master_siswa_id" value="Pilih Siswa" /><select name="master_siswa_id"
                                id="master_siswa_id" class="block mt-1 w-full border-gray-300 rounded-md" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach ($siswa as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><x-input-label for="prakerin_industri_id" value="Pilih Industri" /><select
                                name="prakerin_industri_id" id="prakerin_industri_id"
                                class="block mt-1 w-full border-gray-300 rounded-md" required>
                                <option value="">-- Pilih Industri --</option>
                                @foreach ($industri as $i)
                                    <option value="{{ $i->id }}">{{ $i->nama_industri }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><x-input-label for="master_guru_id" value="Pilih Guru Pembimbing" /><select
                                name="master_guru_id" id="master_guru_id"
                                class="block mt-1 w-full border-gray-300 rounded-md" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach ($guru as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                                @endforeach
                            </select></div>
                        <div><x-input-label for="nama_pembimbing_industri"
                                value="Nama Pembimbing Industri" /><x-text-input id="nama_pembimbing_industri"
                                class="block mt-1 w-full" type="text" name="nama_pembimbing_industri"
                                :value="old('nama_pembimbing_industri')" required /></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><x-input-label for="tanggal_mulai" value="Tanggal Mulai" /><x-text-input
                                    id="tanggal_mulai" class="block mt-1 w-full" type="date" name="tanggal_mulai"
                                    :value="old('tanggal_mulai')" required /></div>
                            <div><x-input-label for="tanggal_selesai" value="Tanggal Selesai" /><x-text-input
                                    id="tanggal_selesai" class="block mt-1 w-full" type="date" name="tanggal_selesai"
                                    :value="old('tanggal_selesai')" required /></div>
                        </div>
                        <div class="flex items-center justify-end pt-4 border-t"><a
                                href="{{ route('prakerin.penempatan.index') }}"><x-secondary-button
                                    type="button">{{ __('Batal') }}</x-secondary-button></a><x-primary-button
                                class="ms-4">{{ __('Simpan Penempatan') }}</x-primary-button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
