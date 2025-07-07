<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Master Data Industri Prakerin') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4"><a
                            href="{{ route('prakerin.industri.create') }}"><x-primary-button>{{ __('+ Tambah Industri') }}</x-primary-button></a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                        Industri</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kota
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak
                                        PIC</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($industri as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama_industri }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->kota }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama_pic ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('prakerin.industri.edit', $item->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('prakerin.industri.destroy', $item->id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Yakin?');">@csrf @method('DELETE')<button
                                                    type="submit"
                                                    class="text-red-600 hover:text-red-900 ml-4">Hapus</button></form>
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
                    <div class="mt-4">{{ $industri->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
