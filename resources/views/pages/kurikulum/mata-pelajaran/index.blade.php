        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Referensi Mata Pelajaran') }}
                </h2>
            </x-slot>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-end mb-4 gap-2">
                                <x-secondary-button x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'import-mapel-modal')">
                                    {{ __('Impor Excel') }}
                                </x-secondary-button>
                                <a href="{{ route('kurikulum.mata-pelajaran.create') }}">
                                    <x-primary-button>{{ __('Tambah Mata Pelajaran') }}</x-primary-button>
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Kode
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Nama
                                                Mata Pelajaran</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Jumlah
                                                Jam</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($mapel as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->kode_mapel }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama_mapel }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->jumlah_jam }} Jam</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('kurikulum.mata-pelajaran.edit', $item->id) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    <form
                                                        action="{{ route('kurikulum.mata-pelajaran.destroy', $item->id) }}"
                                                        method="POST" class="inline-block"
                                                        onsubmit="return confirm('Yakin ingin menghapus?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center">Tidak ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">{{ $mapel->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <x-modal name="import-mapel-modal" :show="$errors->isNotEmpty()" focusable>
                <form method="post" action="{{ route('kurikulum.mata-pelajaran.import') }}" class="p-6"
                    enctype="multipart/form-data">
                    @csrf

                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Impor Data Mata Pelajaran') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Unggah file Excel (.xlsx, .xls) untuk menambahkan data mata pelajaran secara massal. Pastikan file Anda memiliki kolom: ') }}
                        <code class="font-bold">kode_mapel</code>,
                        <code class="font-bold">nama_mapel</code>, dan
                        <code class="font-bold">jumlah_jam</code>.
                    </p>

                    <div class="mt-6">
                        <x-input-label for="file_import" value="{{ __('Pilih File Excel') }}" />
                        <x-text-input id="file_import" name="file_import" type="file" class="mt-1 block w-full"
                            required accept=".xlsx, .xls, .csv" />
                        <x-input-error :messages="$errors->get('file_import')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Batal') }}
                        </x-secondary-button>

                        <x-primary-button class="ms-3">
                            {{ __('Impor Data') }}
                        </x-primary-button>
                    </div>
                </form>
            </x-modal>
        </x-app-layout>
