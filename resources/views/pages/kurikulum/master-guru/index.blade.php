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
                    <div class="flex justify-end mb-4">
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
</x-app-layout>
