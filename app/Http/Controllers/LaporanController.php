<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /** Laporan transaksi dengan filter tanggal, siswa, status, dan jenis. */
    public function index(Request $request)
    {
        $jenis = $request->get('jenis', 'peminjaman'); // peminjaman | pengembalian

        if ($jenis === 'pengembalian') {
            $data = $this->queryPengembalian($request);
        } else {
            $data = $this->queryPeminjaman($request);
        }

        $siswaList = Siswa::orderBy('nama_lengkap')->get();

        return view('laporan.index', [
            'jenis'     => $jenis,
            'data'      => $data,
            'siswaList' => $siswaList,
            'filter'    => [
                'dari'     => $request->get('dari'),
                'sampai'   => $request->get('sampai'),
                'id_siswa' => $request->get('id_siswa'),
                'status'   => $request->get('status'),
            ],
        ]);
    }

    /** Unduh laporan sebagai PDF (dompdf). */
    public function pdf(Request $request)
    {
        $jenis = $request->get('jenis', 'peminjaman');

        if ($jenis === 'pengembalian') {
            $data = $this->queryPengembalian($request);
            $view = 'laporan.pdf_pengembalian';
        } else {
            $data = $this->queryPeminjaman($request);
            $view = 'laporan.pdf_peminjaman';
        }

        $pdf = Pdf::loadView($view, [
            'jenis'  => $jenis,
            'data'   => $data,
            'filter' => [
                'dari'     => $request->get('dari'),
                'sampai'   => $request->get('sampai'),
                'id_siswa' => $request->get('id_siswa'),
                'status'   => $request->get('status'),
            ],
        ])
            ->setPaper('a4', 'landscape');

        $namaFile = 'laporan-' . $jenis . '-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($namaFile);
    }

    /** Query laporan peminjaman sesuai filter. */
    private function queryPeminjaman(Request $request)
    {
        $query = Peminjaman::with(['siswa', 'detail.barang', 'user']);

        if ($dari = $request->get('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $dari);
        }

        if ($sampai = $request->get('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $sampai);
        }

        if ($idSiswa = $request->get('id_siswa')) {
            $query->where('id_siswa', $idSiswa);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        return $query->orderBy('tanggal_pinjam', 'desc')->get();
    }

    /** Query laporan pengembalian sesuai filter. */
    private function queryPengembalian(Request $request)
    {
        $query = Pengembalian::with(['detail.peminjaman.siswa', 'detail.barang', 'user']);

        if ($dari = $request->get('dari')) {
            $query->whereDate('tanggal_kembali', '>=', $dari);
        }

        if ($sampai = $request->get('sampai')) {
            $query->whereDate('tanggal_kembali', '<=', $sampai);
        }

        if ($idSiswa = $request->get('id_siswa')) {
            $query->whereHas('detail.peminjaman', fn ($q) => $q->where('id_siswa', $idSiswa));
        }

        if ($status = $request->get('status')) {
            $query->whereHas('detail.barang', fn ($q) => $q->where('kondisi', $status));
        }

        return $query->orderBy('tanggal_kembali', 'desc')->get();
    }
}
