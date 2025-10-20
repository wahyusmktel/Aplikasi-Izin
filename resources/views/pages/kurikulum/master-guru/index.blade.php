<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Guru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4 gap-2">
                        <!-- Tombol Impor Excel (Baru) -->
                        <x-secondary-button x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'import-guru-modal')">
                            {{ __('Impor Excel') }}
                        </x-secondary-button>
                        <a href="{{ route('kurikulum.master-guru.create') }}">
                            <x-primary-button>{{ __('Tambah Guru') }}</x-primary-button>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NUPTK
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                        Lengkap</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                        Akun</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($guru as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->nuptk ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama_lengkap }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->user)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Belum
                                                    Ada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center space-x-2">
                                                @if (!$item->user)
                                                    <form
                                                        action="{{ route('kurikulum.master-guru.generate-akun', $item->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Buat akun login untuk guru ini?');">
                                                        @csrf
                                                        <x-secondary-button type="submit">Generate
                                                            Akun</x-secondary-button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('kurikulum.master-guru.edit', $item->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('kurikulum.master-guru.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus data guru ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $guru->links() }}</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal untuk Impor Excel (Baru) -->
    <x-modal name="import-guru-modal" focusable>
        <form method="post" action="{{ route('kurikulum.master-guru.import') }}" class="p-6"
            enctype="multipart/form-data">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Impor Data Master Guru') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Unggah file Excel untuk impor data guru. Pastikan file memiliki kolom: ') }}
                <code class="font-bold">nuptk</code> dan
                <code class="font-bold">nama_lengkap</code>.
            </p>

            <div class="mt-6">
                <x-input-label for="file_import" value="{{ __('Pilih File Excel') }}" />
                <x-text-input id="file_import" name="file_import" type="file" class="mt-1 block w-full" required
                    accept=".xlsx, .xls" />
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
