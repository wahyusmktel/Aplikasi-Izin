<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Jadwal Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Pilih Rombongan Belajar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($rombels as $rombel)
                            <a href="{{ route('kurikulum.jadwal-pelajaran.show', $rombel->id) }}"
                                class="block p-4 border rounded-lg hover:bg-gray-50">
                                <p class="font-bold text-indigo-600">{{ $rombel->kelas->nama_kelas }}</p>
                                <p class="text-sm text-gray-600">T.A. {{ $rombel->tahun_ajaran }}</p>
                                <p class="text-sm text-gray-500">Wali Kelas: {{ $rombel->waliKelas->name }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
