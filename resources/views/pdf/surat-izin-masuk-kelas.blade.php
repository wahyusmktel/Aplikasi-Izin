<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Izin Masuk Kelas</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11pt;
            color: #333;
        }

        .container {
            border: 1px solid #ccc;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h3 {
            margin: 0;
        }

        .header p {
            margin: 0;
            font-size: 10pt;
        }

        .content {
            margin-top: 20px;
        }

        .content table {
            width: 100%;
        }

        .content td {
            padding: 4px;
            vertical-align: top;
        }

        .label {
            width: 30%;
        }

        .footer {
            margin-top: 30px;
        }

        .signatures {
            width: 100%;
        }

        .signatures .piket {
            width: 40%;
            float: right;
            text-align: center;
        }

        .qr-codes {
            width: 55%;
            float: left;
        }

        .qr-box {
            width: 48%;
            text-align: center;
            font-size: 7pt;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h3>SURAT IZIN MASUK KELAS</h3>
            <p>SMK TELKOM LAMPUNG</p>
        </div>
        <div class="content">
            <p>Siswa di bawah ini telah ditangani keterlambatannya dan diizinkan untuk mengikuti pelajaran:</p>
            <table>
                <tr>
                    <td class="label">Nama</td>
                    <td>: <strong>{{ $keterlambatan->siswa->nama_lengkap }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Kelas</td>
                    <td>: {{ $keterlambatan->siswa->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Jam Terlambat</td>
                    <td>: {{ \Carbon\Carbon::parse($keterlambatan->waktu_dicatat_security)->format('H:i') }} WIB</td>
                </tr>
                <tr>
                    <td class="label">Alasan Siswa</td>
                    <td>: {{ $keterlambatan->alasan_siswa }}</td>
                </tr>
                @if ($keterlambatan->tindak_lanjut_piket)
                    <tr>
                        <td class="label">Tindak Lanjut</td>
                        <td>: {{ $keterlambatan->tindak_lanjut_piket }}</td>
                    </tr>
                @endif
                @if ($keterlambatan->jadwalPelajaran)
                    <tr>
                        <td class="label">Masuk di Jam Ke-</td>
                        <td>: {{ $keterlambatan->jadwalPelajaran->jam_ke }}
                            ({{ $keterlambatan->jadwalPelajaran->mataPelajaran->nama_mapel }})</td>
                    </tr>
                    <tr>
                        <td class="label">Guru Mengajar</td>
                        <td>: {{ $keterlambatan->jadwalPelajaran->guru->nama_lengkap }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="label">Keterangan</td>
                        <td>: Saat ini sedang tidak ada jam pelajaran.</td>
                    </tr>
                @endif
            </table>
        </div>
        <div class="footer">
            <div class="signatures">
                <div class="piket">
                    <p>Bandar Lampung, {{ now()->isoFormat('D MMMM YYYY') }}</p>
                    <p>Guru Piket,</p>
                    <br><br><br>
                    <p><strong>{{ $keterlambatan->guruPiket->name }}</strong></p>
                </div>
                <div class="qr-codes">
                    <div class="qr-box">
                        <img src="{{ $guruKelasQrCode }}" alt="QR Verifikasi Guru Kelas">
                        <p>Pindai oleh Guru Kelas</p>
                    </div>
                    <div class="qr-box">
                        <img src="{{ $publicQrCode }}" alt="QR Verifikasi Publik">
                        <p>Verifikasi Keabsahan Surat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
