<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jurnal Prakerin: {{ $penempatan->siswa->nama_lengkap }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @forelse($jurnals as $jurnal)
                    <div class="bg-white p-6 rounded-lg shadow-sm" x-data="{ open: false }">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-lg">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->isoFormat('dddd, D MMMM Y') }}</p>
                                <span
                                    class="text-xs px-2 py-1 rounded-full {{ ['menunggu' => 'bg-yellow-100 text-yellow-800', 'disetujui' => 'bg-green-100 text-green-800', 'revisi' => 'bg-red-100 text-red-800'][$jurnal->status_verifikasi] }}">{{ Str::title($jurnal->status_verifikasi) }}</span>
                            </div>
                            <button @click="open = !open" class="text-sm text-blue-600">
                                <span x-show="!open">Validasi</span>
                                <span x-show="open">Tutup</span>
                            </button>
                        </div>
                        <div class="mt-4 border-t pt-4">
                            <p><strong>Kegiatan:</strong><br>{{ $jurnal->kegiatan_dilakukan }}</p>
                            <p class="mt-2"><strong>Kompetensi:</strong><br>{{ $jurnal->kompetensi_yang_didapat }}</p>
                            @if ($jurnal->foto_kegiatan)
                                <a href="{{ Storage::url($jurnal->foto_kegiatan) }}" target="_blank"
                                    class="text-blue-500 text-sm mt-2 inline-block">Lihat Foto</a>
                            @endif
                        </div>
                        {{-- Form Validasi --}}
                        <div x-show="open" class="mt-4 border-t pt-4">
                            <form method="POST"
                                action="{{ route('pembimbing-prakerin.monitoring.updateJurnal', $jurnal->id) }}">
                                @csrf @method('PATCH')
                                <x-input-label for="catatan_pembimbing_{{ $jurnal->id }}"
                                    value="Catatan / Feedback" />
                                <textarea name="catatan_pembimbing" id="catatan_pembimbing_{{ $jurnal->id }}" rows="2"
                                    class="w-full border-gray-300 rounded-md shadow-sm">{{ $jurnal->catatan_pembimbing }}</textarea>
                                <div class="mt-2 flex items-center justify-end space-x-2">
                                    <button name="status_verifikasi" value="revisi"
                                        class="px-3 py-1 text-sm rounded-md bg-red-500 text-white">Minta Revisi</button>
                                    <button name="status_verifikasi" value="disetujui"
                                        class="px-3 py-1 text-sm rounded-md bg-green-500 text-white">Setujui</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 bg-white p-10 rounded-lg">Siswa ini belum mengisi jurnal.</p>
                @endforelse
            </div>
            <div class="mt-6">{{ $jurnals->links() }}</div>
        </div>
    </div>
</x-app-layout>
