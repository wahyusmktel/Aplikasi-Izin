    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Hasil Verifikasi Izin Masuk Kelas') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center">
                        @if ($success)
                            <i class="fa-solid fa-check-circle fa-4x text-green-500 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-800">Verifikasi Berhasil</h3>
                            <p class="mt-2 text-gray-600">
                                <span class="font-bold">{{ $keterlambatan->siswa->name }}</span> {{ $message }}
                            </p>
                        @else
                            <i class="fa-solid fa-times-circle fa-4x text-red-500 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-800">Verifikasi Gagal</h3>
                            <p class="mt-2 text-gray-600">{{ $message }}</p>
                            @if (isset($keterlambatan))
                                <p class="mt-1 text-sm text-gray-500">Status saat ini untuk <span
                                        class="font-bold">{{ $keterlambatan->siswa->name }}</span> adalah: <span
                                        class="font-semibold">{{ str_replace('_', ' ', Str::title($keterlambatan->status)) }}</span>
                                </p>
                            @endif
                        @endif

                        <div class="mt-6">
                            <a href="{{ route('guru-kelas.dashboard.index') }}">
                                <x-primary-button>Kembali ke Dashboard</x-primary-button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
