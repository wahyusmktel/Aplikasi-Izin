<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Dispensasi</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11pt;
        }

        .header,
        .title {
            text-align: center;
        }

        .header h2,
        .header h3 {
            margin: 0;
        }

        .header {
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }

        .title {
            margin-top: 25px;
            font-weight: bold;
            text-decoration: underline;
        }

        .content {
            margin-top: 25px;
        }

        .student-list {
            margin-top: 15px;
        }

        .student-list td {
            border: 1px solid #ccc;
            padding: 5px;
        }

        .signatures {
            margin-top: 40px;
        }

        .qr-code {
            position: fixed;
            bottom: 0;
            right: 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>SURAT DISPENSASI</h2>
        <h3>SMK TELKOM LAMPUNG</h3>
    </div>
    <div class="title">Nomor: DISPEN/{{ $dispensasi->id }}/{{ date('Y') }}</div>
    <div class="content">
        <p>Dengan hormat,</p>
        <p>Berdasarkan kegiatan <strong>{{ $dispensasi->nama_kegiatan }}</strong>, maka dengan ini memberikan dispensasi
            (izin untuk tidak mengikuti kegiatan belajar mengajar) kepada siswa-siswi terlampir di bawah ini, pada:</p>
        <table>
            <tr>
                <td width="20%">Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($dispensasi->waktu_mulai)->isoFormat('dddd, D MMMM YYYY') }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>: {{ \Carbon\Carbon::parse($dispensasi->waktu_mulai)->format('H:i') }} s/d
                    {{ \Carbon\Carbon::parse($dispensasi->waktu_selesai)->format('H:i') }} WIB</td>
            </tr>
        </table>
        <div class="student-list">
            <p><strong>Daftar Nama Siswa:</strong></p>
            <table style="width:100%; border-collapse: collapse;">
                <thead style="background-color: #f2f2f2;">
                    <tr>
                        <th style="border: 1px solid #ccc; padding: 5px;">No</th>
                        <th style="border: 1px solid #ccc; padding: 5px;">Nama Siswa</th>
                        <th style="border: 1px solid #ccc; padding: 5px;">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dispensasi->siswa as $siswa)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nama_lengkap }}</td>
                            <td>{{ $siswa->rombels->first()?->kelas->nama_kelas ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p>Demikian surat dispensasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>
    <div class="signatures">
        <div style="width: 40%; float: right; text-align:center;">
            <p>Bandar Lampung, {{ now()->isoFormat('D MMMM YYYY') }}</p>
            <p>Waka Kesiswaan,</p><br><br><br>
            <p><strong>{{ $dispensasi->disetujuiOleh->name }}</strong></p>
        </div>
    </div>
    <div class="qr-code"><img src="{{ $qrCode }}" alt="QR Code"></div>
</body>

</html>
