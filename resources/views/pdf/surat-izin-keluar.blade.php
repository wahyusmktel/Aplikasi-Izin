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
            margin-top: 50px;
            width: 100%;
        }

        .signature-box {
            width: 30%;
            text-align: center;
            float: left;
            margin: 0 1.5%;
        }

        .qr-code {
            float: right;
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
                <tr>
                    <td>Estimasi Kembali</td>
                    <td>: {{ \Carbon\Carbon::parse($izin->estimasi_kembali)->format('H:i') }} WIB</td>
                </tr>
            </table>
            <p>Untuk meninggalkan lingkungan sekolah. Surat ini berlaku pada tanggal
                {{ \Carbon\Carbon::parse($izin->created_at)->isoFormat('D MMMM YYYY') }}.</p>
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
                <div class="qr-code">
                    {!! QrCode::size(80)->generate($verificationUrl) !!}
                </div>
                <br><br><br>
                <p><strong>{{ $izin->securityVerifier->name ?? '(.........................)' }}</strong></p>
            </div>
        </div>
    </div>
</body>

</html>
