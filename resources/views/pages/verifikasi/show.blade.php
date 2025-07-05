<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Surat Izin - SMK Telkom Lampung</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="w-full max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Verifikasi Dokumen Izin</h1>
                <p class="text-gray-500">SMK Telkom Lampung</p>
            </div>

            <div class="border-2 border-dashed border-green-500 bg-green-50 p-6 rounded-lg text-center">
                <i class="fa-solid fa-check-circle fa-4x text-green-500 mb-4"></i>
                <h2 class="text-xl font-semibold text-green-800">Dokumen Terverifikasi</h2>
                <p class="text-green-700">Surat izin ini adalah dokumen asli yang diterbitkan oleh sistem perizinan
                    sekolah.</p>
            </div>

            <div class="mt-8 space-y-4">
                <h3 class="font-bold text-lg border-b pb-2">Detail Izin</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1 text-gray-500">Nomor Unik</div>
                    <div class="col-span-2 font-mono text-sm">{{ $izin->uuid }}</div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1 text-gray-500">Nama Siswa</div>
                    <div class="col-span-2 font-semibold">{{ $izin->siswa->name }}</div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1 text-gray-500">Kelas</div>
                    <div class="col-span-2">{{ $izin->rombel->kelas->nama_kelas }}</div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1 text-gray-500">Tujuan</div>
                    <div class="col-span-2">{{ $izin->tujuan }}</div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1 text-gray-500">Waktu Pengajuan</div>
                    <div class="col-span-2">
                        {{ \Carbon\Carbon::parse($izin->created_at)->isoFormat('dddd, D MMMM YYYY, HH:mm') }} WIB</div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1 text-gray-500">Status</div>
                    <div class="col-span-2">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            {{ str_replace('_', ' ', Str::title($izin->status)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <footer class="mt-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Aplikasi Perizinan SMK Telkom Lampung.
        </footer>
    </div>
</body>

</html>
