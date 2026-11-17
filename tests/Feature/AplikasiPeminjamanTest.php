<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Peminjaman;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Pengujian menyeluruh aplikasi Peminjaman Barang Laboratorium.
 * Mencakup: login, dashboard, CRUD barang & siswa, transaksi peminjaman
 * (stok berkurang), pengembalian (stok bertambah), laporan, dan PDF.
 */
class AplikasiPeminjamanTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    /* ============================ LOGIN ============================ */

    /** Halaman login dapat diakses; halaman terproteksi diarahkan ke login. */
    public function test_halaman_login_tampil_dan_halaman_lain_diproteksi(): void
    {
        $this->get('/')->assertOk()->assertSee('Peminjaman Lab');
        $this->get('/dashboard')->assertRedirect('/');
        $this->get('/barang')->assertRedirect('/');
        $this->get('/peminjaman')->assertRedirect('/');
        $this->get('/laporan')->assertRedirect('/');
    }

    /** Login dengan kredensial benar masuk dashboard. */
    public function test_login_berhasil(): void
    {
        $this->post('/login', [
            'email'    => $this->admin->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');
    }

    /** Login dengan kredensial salah ditolak dengan pesan error. */
    public function test_login_gagal(): void
    {
        $this->post('/login', [
            'email'    => $this->admin->email,
            'password' => 'salah',
        ])->assertSessionHasErrors('email');
    }

    /** Login yang diulang saat masih login -> tetap diarahkan ke dashboard. */
    public function test_login_sudah_login(): void
    {
        $this->actingAs($this->admin)
            ->post('/login', ['email' => $this->admin->email, 'password' => 'password'])
            ->assertRedirect('/dashboard');
    }

    /* ========================== DASHBOARD ========================== */

    public function test_dashboard_memuat_statistik(): void
    {
        $this->actingAs($this->admin)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Total Barang')
            ->assertSee('Total Siswa')
            ->assertSee('Aktivitas Transaksi Terbaru');
    }

    /* ========================== BARANG ============================= */

    public function test_crud_barang_berjalan(): void
    {
        $kategori = KategoriBarang::create([
            'nama_kategori' => 'Laptop',
            'kode_kategori' => 'LAP',
        ]);

        // Tampilkan form create.
        $this->actingAs($this->admin)->get('/barang/create')->assertOk()->assertSee('Laptop');

        // Simpan barang baru.
        $this->actingAs($this->admin)->post('/barang', [
            'kode_barang' => 'LAP-001',
            'nama_barang' => 'Laptop Lenovo ThinkPad',
            'id_kategori' => $kategori->id_kategori,
            'stok'        => 10,
            'kondisi'     => 'Baik',
        ])->assertRedirect('/barang')->assertSessionHas('success');

        $barang = Barang::where('kode_barang', 'LAP-001')->first();
        $this->assertNotNull($barang);
        $this->assertEquals('Tersedia', $barang->status);

        // Index menampilkan barang.
        $this->actingAs($this->admin)->get('/barang?cari=LAP-001')->assertOk()->assertSee('Laptop Lenovo ThinkPad');

        // Detail.
        $this->actingAs($this->admin)->get("/barang/{$barang->id_barang}")->assertOk()->assertSee('Riwayat Peminjaman');

        // Edit & update.
        $this->actingAs($this->admin)->get("/barang/{$barang->id_barang}/edit")->assertOk();
        $this->actingAs($this->admin)->put("/barang/{$barang->id_barang}", [
            'kode_barang' => 'LAP-001',
            'nama_barang' => 'Laptop Lenovo ThinkPad E14',
            'id_kategori' => $kategori->id_kategori,
            'stok'        => 8,
            'kondisi'     => 'Baik',
        ])->assertRedirect('/barang')->assertSessionHas('success');
        $this->assertEquals(8, $barang->fresh()->stok);

        // Validasi: kode duplikat ditolak.
        $this->actingAs($this->admin)->post('/barang', [
            'kode_barang' => 'LAP-001',
            'nama_barang' => 'Duplikat',
            'id_kategori' => $kategori->id_kategori,
            'stok'        => 1,
            'kondisi'     => 'Baik',
        ])->assertSessionHasErrors('kode_barang');

        // Hapus.
        $this->actingAs($this->admin)->delete("/barang/{$barang->id_barang}")
            ->assertRedirect('/barang')->assertSessionHas('success');
        $this->assertDatabaseMissing('barang', ['kode_barang' => 'LAP-001']);
    }

    /* =========================== SISWA ============================= */

    public function test_crud_siswa_berjalan(): void
    {
        $this->actingAs($this->admin)->post('/siswa', [
            'nis'          => '2212345',
            'nama_lengkap' => 'Budi Santoso',
            'kelas'        => 'XII RPL 1',
            'jurusan'      => 'Rekayasa Perangkat Lunak',
            'no_telepon'   => '081234567890',
        ])->assertRedirect('/siswa')->assertSessionHas('success');

        $siswa = Siswa::where('nis', '2212345')->first();
        $this->assertNotNull($siswa);

        $this->actingAs($this->admin)->get('/siswa')->assertOk()->assertSee('Budi Santoso');
        $this->actingAs($this->admin)->get("/siswa/{$siswa->id_siswa}")->assertOk()->assertSee('Riwayat Peminjaman');

        $this->actingAs($this->admin)->put("/siswa/{$siswa->id_siswa}", [
            'nis'          => '2212345',
            'nama_lengkap' => 'Budi Santoso Jr',
            'kelas'        => 'XII RPL 2',
            'jurusan'      => 'Rekayasa Perangkat Lunak',
        ])->assertRedirect('/siswa')->assertSessionHas('success');

        $this->actingAs($this->admin)->delete("/siswa/{$siswa->id_siswa}")
            ->assertRedirect('/siswa')->assertSessionHas('success');
        $this->assertDatabaseMissing('siswa', ['nis' => '2212345']);
    }

    /* ========================= PEMINJAMAN ========================== */

    /** Alur lengkap: simpan transaksi -> setujui (stok berkurang) -> kembalikan (stok bertambah). */
    public function test_alur_peminjaman_dan_pengembalian_lengkap(): void
    {
        $kategori = KategoriBarang::create(['nama_kategori' => 'Proyektor', 'kode_kategori' => 'PRJ']);
        $barang   = Barang::create([
            'kode_barang' => 'PRJ-001',
            'nama_barang' => 'Proyektor Epson',
            'id_kategori' => $kategori->id_kategori,
            'stok'        => 2,
            'stok_dipinjam' => 0,
            'kondisi'     => 'Baik',
            'status'      => 'Tersedia',
        ]);
        $siswa = Siswa::create([
            'nis'          => '2212001',
            'nama_lengkap' => 'Ani Wijaya',
            'kelas'        => 'XII TKJ 1',
            'jurusan'      => 'Teknik Komputer & Jaringan',
        ]);

        // 1. Simpan transaksi peminjaman.
        $this->actingAs($this->admin)->post('/peminjaman', [
            'id_siswa'                => $siswa->id_siswa,
            'barang'                  => [
                ['id_barang' => $barang->id_barang, 'jumlah' => 2],
            ],
            'tanggal_pinjam'          => now()->format('Y-m-d'),
            'tanggal_rencana_kembali' => now()->addDays(3)->format('Y-m-d'),
            'keterangan'              => 'Untuk praktik presentasi',
        ])->assertRedirect()->assertSessionHas('success');

        $peminjaman = Peminjaman::first();
        $this->assertEquals('Menunggu', $peminjaman->status);
        $this->assertStringStartsWith('PJM-', $peminjaman->kode_peminjaman);
        $this->assertEquals(2, $peminjaman->detail->first()->jumlah);

        // Stok belum berkurang sebelum disetujui.
        $this->assertEquals(0, $barang->fresh()->stok_dipinjam);

        // Validasi: jumlah melebihi stok ditolak.
        $this->actingAs($this->admin)->post('/peminjaman', [
            'id_siswa'                => $siswa->id_siswa,
            'barang'                  => [
                ['id_barang' => $barang->id_barang, 'jumlah' => 999],
            ],
            'tanggal_pinjam'          => now()->format('Y-m-d'),
            'tanggal_rencana_kembali' => now()->addDays(3)->format('Y-m-d'),
        ])->assertSessionHas('error');

        // 2. Setujui -> status Dipinjam & stok berkurang.
        $this->actingAs($this->admin)->post("/peminjaman/{$peminjaman->id_peminjaman}/setujui")
            ->assertSessionHas('success');
        $this->assertEquals('Dipinjam', $peminjaman->fresh()->status);
        $this->assertEquals(2, $barang->fresh()->stok_dipinjam);
        $this->assertEquals('Dipinjam', $barang->fresh()->status);

        // 3. Proses pengembalian -> stok bertambah kembali.
        $detail = $peminjaman->detail->first();
        $this->actingAs($this->admin)->post("/pengembalian/{$detail->id_detail}", [
            'tanggal_kembali' => now()->format('Y-m-d'),
            'kondisi_barang'  => 'Baik',
            'catatan'         => 'Kondisi lengkap',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertEquals(0, $barang->fresh()->stok_dipinjam);
        $this->assertEquals('Tersedia', $barang->fresh()->status);
        $this->assertEquals('Selesai', $peminjaman->fresh()->status);
        $this->assertDatabaseHas('pengembalian', ['id_detail' => $detail->id_detail, 'kondisi_barang' => 'Baik']);

        // Detail transaksi tampil.
        $this->actingAs($this->admin)->get("/peminjaman/{$peminjaman->id_peminjaman}")->assertOk()->assertSee('Selesai');
    }

    /** Transaksi ditolak: stok tetap, status Ditolak. */
    public function test_peminjaman_ditolak(): void
    {
        $kategori = KategoriBarang::create(['nama_kategori' => 'Router', 'kode_kategori' => 'RTR']);
        $barang   = Barang::create([
            'kode_barang' => 'RTR-001',
            'nama_barang' => 'Router Mikrotik',
            'id_kategori' => $kategori->id_kategori,
            'stok'        => 3,
            'stok_dipinjam' => 0,
            'kondisi'     => 'Baik',
            'status'      => 'Tersedia',
        ]);
        $siswa = Siswa::create([
            'nis' => '2212002', 'nama_lengkap' => 'Citra Dewi', 'kelas' => 'XI TKJ 1', 'jurusan' => 'Teknik Komputer & Jaringan',
        ]);

        $this->actingAs($this->admin)->post('/peminjaman', [
            'id_siswa'                => $siswa->id_siswa,
            'barang'                  => [['id_barang' => $barang->id_barang, 'jumlah' => 1]],
            'tanggal_pinjam'          => now()->format('Y-m-d'),
            'tanggal_rencana_kembali' => now()->addDays(2)->format('Y-m-d'),
        ])->assertSessionHas('success');

        $peminjaman = Peminjaman::first();
        $this->actingAs($this->admin)->post("/peminjaman/{$peminjaman->id_peminjaman}/tolak", [
            'alasan' => 'Barang dipakai ujian',
        ])->assertSessionHas('success');

        $this->assertEquals('Ditolak', $peminjaman->fresh()->status);
        $this->assertEquals(0, $barang->fresh()->stok_dipinjam);
    }

    /** Status barang: Habis (stok 0) dan Belum Dikembalikan (terlambat). */
    public function test_status_habis_dan_belum_dikembalikan(): void
    {
        $kategori = KategoriBarang::create(['nama_kategori' => 'Kabel Jaringan', 'kode_kategori' => 'KBL']);

        // 1. Barang dengan stok 0 -> status HABIS (sold out).
        $this->actingAs($this->admin)->post('/barang', [
            'kode_barang' => 'KBL-001',
            'nama_barang' => 'Kabel UTP Cat6',
            'id_kategori' => $kategori->id_kategori,
            'stok'        => 0,
            'kondisi'     => 'Baik',
        ])->assertSessionHas('success');

        $this->assertEquals('Habis', Barang::where('kode_barang', 'KBL-001')->first()->status);

        // 2. Barang dipinjam melewati batas rencana kembali -> BELUM DIKEMBALIKAN.
        $barang = Barang::create([
            'kode_barang'   => 'KBL-002',
            'nama_barang'   => 'Kabel LAN 10m',
            'id_kategori'   => $kategori->id_kategori,
            'stok'          => 1,
            'stok_dipinjam' => 0,
            'kondisi'       => 'Baik',
            'status'        => 'Tersedia',
        ]);
        $siswa = Siswa::create([
            'nis' => '2212099', 'nama_lengkap' => 'Eka Putri', 'kelas' => 'XI RPL 1', 'jurusan' => 'Rekayasa Perangkat Lunak',
        ]);

        $this->actingAs($this->admin)->post('/peminjaman', [
            'id_siswa'                => $siswa->id_siswa,
            'barang'                  => [['id_barang' => $barang->id_barang, 'jumlah' => 1]],
            'tanggal_pinjam'          => now()->subDays(10)->format('Y-m-d'),
            'tanggal_rencana_kembali' => now()->subDay()->format('Y-m-d'), // sudah lewat
        ])->assertSessionHas('success');

        $peminjaman = Peminjaman::where('id_siswa', $siswa->id_siswa)->first();
        $this->actingAs($this->admin)->post("/peminjaman/{$peminjaman->id_peminjaman}/setujui")
            ->assertSessionHas('success');

        $this->assertEquals('Belum Dikembalikan', $barang->fresh()->status);

        // Setelah dikembalikan, status kembali Tersedia.
        $detail = $peminjaman->detail->first();
        $this->actingAs($this->admin)->post("/pengembalian/{$detail->id_detail}", [
            'tanggal_kembali' => now()->format('Y-m-d'),
            'kondisi_barang'  => 'Baik',
        ])->assertSessionHas('success');

        $this->assertEquals('Tersedia', $barang->fresh()->status);
    }

    /* ========================== LAPORAN ============================ */

    public function test_laporan_dan_pdf(): void
    {
        $kategori = KategoriBarang::create(['nama_kategori' => 'Switch', 'kode_kategori' => 'SWT']);
        $barang   = Barang::create([
            'kode_barang' => 'SWT-001',
            'nama_barang' => 'Switch Cisco',
            'id_kategori' => $kategori->id_kategori,
            'stok'        => 4,
            'stok_dipinjam' => 0,
            'kondisi'     => 'Baik',
            'status'      => 'Tersedia',
        ]);
        $siswa = Siswa::create([
            'nis' => '2212003', 'nama_lengkap' => 'Dedi Kurniawan', 'kelas' => 'XII RPL 1', 'jurusan' => 'Rekayasa Perangkat Lunak',
        ]);

        $peminjaman = Peminjaman::create([
            'kode_peminjaman'         => 'PJM-TEST-0001',
            'id_siswa'                => $siswa->id_siswa,
            'id_user'                 => $this->admin->id_user,
            'tanggal_pinjam'          => now()->subDays(5),
            'tanggal_rencana_kembali' => now()->addDay(),
            'status'                  => 'Dipinjam',
        ]);

        // Laporan peminjaman.
        $this->actingAs($this->admin)->get('/laporan?jenis=peminjaman')
            ->assertOk()->assertSee('PJM-TEST-0001');

        // Filter status.
        $this->actingAs($this->admin)->get('/laporan?jenis=peminjaman&status=Dipinjam')
            ->assertOk()->assertSee('PJM-TEST-0001');
        $this->actingAs($this->admin)->get('/laporan?jenis=peminjaman&status=Ditolak')
            ->assertOk()->assertSee('Tidak ada data');

        // Laporan pengembalian.
        $this->actingAs($this->admin)->get('/laporan?jenis=pengembalian')->assertOk();

        // PDF peminjaman & pengembalian.
        $this->actingAs($this->admin)->get('/laporan/pdf?jenis=peminjaman')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->actingAs($this->admin)->get('/laporan/pdf?jenis=pengembalian')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    /* ========================== LOGOUT ============================= */

    public function test_logout_berhasil(): void
    {
        $this->actingAs($this->admin)->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }
}
