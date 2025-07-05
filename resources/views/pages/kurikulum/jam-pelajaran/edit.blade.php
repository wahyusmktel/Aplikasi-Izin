<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jam Pelajaran') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('kurikulum.jam-pelajaran.update', $jamPelajaran->id) }}">
                        @csrf
                        @method('PUT')
                        @include('pages.kurikulum.jam-pelajaran.partials.form-control', [
                            'jamPelajaran' => $jamPelajaran,
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
