    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verifikasi Surat Izin Masuk</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-100">
        <div class="container mx-auto max-w-2xl my-10 p-8 bg-white shadow-lg rounded-lg">
            <div class="text-center mb-6">
                <svg class="mx-auto h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h1 class="text-2xl font-bold text-gray-800 mt-2">Surat Terverifikasi</h1>
                <p class="text-gray-500">Dokumen ini sah dan tercatat dalam sistem.</p>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Nama Siswa</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $keterlambatan->siswa->nama_lengkap }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Kelas</dt>
                        <dd class="mt-1 text-gray-900">
                            {{ $keterlambatan->siswa->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Jam Terlambat</dt>
                        <dd class="mt-1 text-gray-900">
                            {{ \Carbon\Carbon::parse($keterlambatan->waktu_dicatat_security)->format('H:i') }} WIB</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Alasan</dt>
                        <dd class="mt-1 text-gray-900">{{ $keterlambatan->alasan_siswa }}</dd>
                    </div>
                    @if ($keterlambatan->jadwalPelajaran)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Masuk di Jam Pelajaran</dt>
                            <dd class="mt-1 text-gray-900">
                                {{ $keterlambatan->jadwalPelajaran->mataPelajaran->nama_mapel }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Guru Mengajar</dt>
                            <dd class="mt-1 text-gray-900">{{ $keterlambatan->jadwalPelajaran->guru->nama_lengkap }}
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dicatat oleh Security</dt>
                        <dd class="mt-1 text-gray-900">{{ $keterlambatan->security->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Diverifikasi oleh Guru Piket</dt>
                        <dd class="mt-1 text-gray-900">{{ $keterlambatan->guruPiket->name }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </body>

    </html>
