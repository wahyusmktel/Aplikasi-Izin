<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat Dispensasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto max-w-4xl my-10 p-8 bg-white shadow-lg rounded-lg">
        <div class="text-center mb-6">
            <svg class="mx-auto h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Dokumen Dispensasi Terverifikasi</h1>
            <p class="text-gray-500">Surat ini sah dan tercatat dalam sistem perizinan SMK Telkom Lampung.</p>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Nama Kegiatan</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $dispensasi->nama_kegiatan }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Waktu Pelaksanaan</dt>
                    <dd class="mt-1 text-gray-900">
                        {{ \Carbon\Carbon::parse($dispensasi->waktu_mulai)->isoFormat('D MMM Y, HH:mm') }} -
                        {{ \Carbon\Carbon::parse($dispensasi->waktu_selesai)->isoFormat('HH:mm') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-gray-900">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Disetujui
                        </span>
                    </dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                    <dd class="mt-1 text-gray-700">{{ $dispensasi->keterangan }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Diajukan oleh</dt>
                    <dd class="mt-1 text-gray-900">{{ $dispensasi->diajukanOleh->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Disetujui oleh</dt>
                    <dd class="mt-1 text-gray-900">{{ $dispensasi->disetujuiOleh->name }}</dd>
                </div>
            </dl>
        </div>

        <div class="border-t border-gray-200 pt-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Daftar Siswa yang Terlibat</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @foreach ($dispensasi->siswa as $siswa)
                            <tr>
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 font-medium">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-4 py-2">{{ $siswa->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <footer class="mt-6 mb-10 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} Aplikasi Perizinan SMK Telkom Lampung.
    </footer>
</body>

</html>
