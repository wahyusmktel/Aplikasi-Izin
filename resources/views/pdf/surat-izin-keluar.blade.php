<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Izin Meninggalkan Kelas</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }

        .content {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
        }

        .signatures {
            margin-top: 40px;
            width: 100%;
        }

        .signature-box {
            width: 30%;
            text-align: center;
            float: left;
            margin: 0 1.6%;
        }

        .qr-table {
            width: 100%;
            margin-top: 5px;
        }

        .qr-table td {
            text-align: center;
            vertical-align: bottom;
        }

        .qr-label {
            font-size: 8px;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>SURAT IZIN MENINGGALKAN KELAS</h2>
            <h3>SMK TELKOM LAMPUNG</h3>
        </div>
        <div class="content">
            <p>Dengan ini memberikan izin kepada siswa di bawah ini:</p>
            <table>
                <tr>
                    <td width="30%">Nama</td>
                    <td>: {{ $izin->siswa->name }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>: {{ $izin->siswa->masterSiswa?->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Tujuan</td>
                    <td>: {{ $izin->tujuan }}</td>
                </tr>
                @if ($izin->jadwalPelajaran)
                    <tr>
                        <td>Pada Jam Pelajaran</td>
                        <td>: {{ $izin->jadwalPelajaran?->mataPelajaran?->nama_mapel }} (Guru:
                            {{ $izin->jadwalPelajaran?->guru?->nama_lengkap }})</td>
                    </tr>
                @endif
                <tr>
                    <td>Estimasi Kembali</td>
                    <td>: {{ \Carbon\Carbon::parse($izin->estimasi_kembali)->format('H:i') }} WIB</td>
                </tr>
            </table>
            <p>Untuk meninggalkan lingkungan sekolah. Surat ini berlaku pada tanggal
                {{ \Carbon\Carbon::parse($izin->created_at)->isoFormat('dddd, D MMMM Y') }}.</p>
        </div>
        <div class="signatures">
            <div class="signature-box">
                <p>Diizinkan oleh,</p>
                <p>Guru Kelas</p>
                <br><br><br>
                <p><strong>{{ $izin->guruKelasApprover->name ?? '(.........................)' }}</strong></p>
            </div>
            <div class="signature-box">
                <p>Diizinkan oleh,</p>
                <p>Guru Piket</p>
                <br><br><br>
                <p><strong>{{ $izin->guruPiketApprover->name ?? '(.........................)' }}</strong></p>
            </div>
            <div class="signature-box">
                <p>Diverifikasi oleh,</p>
                <p>Security</p>
                <table class="qr-table">
                    <tr>
                        <td>
                            <img src="{{ $securityQrCodeBase64 }}" alt="QR Code Petugas">
                            <p class="qr-label">Pindai oleh Petugas</p>
                        </td>
                        <td>
                            <img src="{{ $publicQrCodeBase64 }}" alt="QR Code Publik">
                            <p class="qr-label">Verifikasi Publik</p>
                        </td>
                    </tr>
                </table>
                <p><strong>{{ $izin->securityVerifier->name ?? '(.........................)' }}</strong></p>
            </div>
        </div>
    </div>
</body>

</html>
