<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengembalian — Peminjaman Lab</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        .header { text-align: center; border-bottom: 3px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 16px; }
        .header h1 { font-size: 16px; color: #1e3a5f; text-transform: uppercase; }
        .header p { font-size: 10px; color: #6b7280; margin-top: 2px; }
        .filter { font-size: 10px; margin-bottom: 12px; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 5px 7px; }
        th { background-color: #1e3a5f; color: #fff; font-size: 10px; text-transform: uppercase; text-align: center; }
        tr:nth-child(even) td { background-color: #f8fafc; }
        .footer { margin-top: 24px; font-size: 10px; color: #6b7280; display: flex; justify-content: space-between; }
        .ttd { margin-top: 40px; text-align: right; width: 220px; float: right; }
        .ttd .nama { border-top: 1px solid #374151; margin-top: 44px; padding-top: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pengembalian Barang Laboratorium</h1>
        <p>{{ config('app.name') }} — Dicetak {{ now()->translatedFormat('d M Y, H:i') }}</p>
    </div>

    <div class="filter">
        Periode: {{ $filter['dari'] ?: '—' }} s.d. {{ $filter['sampai'] ?: '—' }}
        | Kondisi: {{ $filter['status'] ?: 'Semua' }}
        | Total transaksi: {{ $data->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th style="width:110px">Kode</th>
                <th>Siswa</th>
                <th>Barang</th>
                <th style="width:45px">Unit</th>
                <th style="width:75px">Tgl Kembali</th>
                <th style="width:80px">Kondisi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $i => $r)
                <tr>
                    <td style="text-align:center">{{ $i + 1 }}</td>
                    <td>{{ $r->detail->peminjaman->kode_peminjaman }}</td>
                    <td>{{ $r->detail->peminjaman->siswa->nama_lengkap }}</td>
                    <td>{{ $r->detail->barang->nama_barang }}</td>
                    <td style="text-align:center">{{ $r->detail->jumlah }}</td>
                    <td style="text-align:center">{{ $r->tanggal_kembali->translatedFormat('d/m/Y') }}</td>
                    <td style="text-align:center">{{ $r->kondisi_barang }}</td>
                    <td>{{ $r->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;padding:20px">Tidak ada data sesuai filter.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span>Aplikasi Peminjaman Barang Laboratorium</span>
        <span>{{ $data->count() }} transaksi</span>
    </div>
    <div class="ttd">
        <p>Mengetahui,</p>
        <p class="nama">Admin Laboratorium</p>
    </div>
</body>
</html>
