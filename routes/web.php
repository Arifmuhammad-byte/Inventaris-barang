<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LandingPage;
use App\Livewire\PilihRole;

use App\Livewire\Admin\Login as AdminLogin;
use App\Livewire\Guru\Login as GuruLogin;

use App\Livewire\Guru\Register;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Guru\Dashboard as GuruDashboard;
use App\Livewire\Guru\Inventaris;
use App\Livewire\Guru\Peminjaman as GuruPeminjaman;
use App\Livewire\Guru\PengembalianGuru;
use App\Livewire\Guru\Profil as GuruProfil;



use App\Livewire\Admin\BarangCrud;
use App\Livewire\Admin\KategoriLokasi;
use App\Livewire\Admin\Peminjaman as AdminPeminjaman;
use App\Livewire\Admin\Pengembalian as AdminPengembalian;
use App\Livewire\Admin\Laporan;
use App\Livewire\Admin\ManajemenPengguna;
use App\Livewire\Admin\Profil as AdminProfil;
use App\Http\Controllers\Admin\LaporanController;





// ===================
// Tampilan Umum
// ===================
Route::get('/', LandingPage::class);
Route::get('/pilih-role', PilihRole::class)->name('pilih.role');

// ===================
// Guru
// ===================
Route::get('/guru/login', GuruLogin::class)->name('guru.login'); // route default login
Route::get('/guru/register', Register::class)->name('guru.register');
    Route::get('/guru/dashboard', GuruDashboard::class)->name('guru.dashboard');
    Route::get('/guru/inventaris', Inventaris::class)->name('guru.inventaris');
    Route::get('/guru/peminjaman', GuruPeminjaman::class)->name('guru.peminjaman');
    Route::get('/guru/pengembalian', PengembalianGuru::class)->name('guru.pengembalian');
    Route::get('/guru/profil', GuruProfil::class)->name('guru.profil');





// ===================
// Admin
// ===================
Route::get('/admin/login', AdminLogin::class)->name('admin.login');
Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
Route::get('/admin/barang', BarangCrud::class)->name('admin.barang');
Route::get('/kategori-lokasi', KategoriLokasi::class)->name('admin.kategori-lokasi');
Route::get('/admin/peminjaman', AdminPeminjaman::class)->name('admin.peminjaman');
Route::get('/admin/pengembalian', AdminPengembalian::class) ->name('admin.pengembalian');
Route::get('/admin/laporan', laporan::class) ->name('admin.laporan');
Route::get('/export-pdf', [BarangController::class, 'exportPdf']) ->name('export.pdf');
Route::get('/manajemen-pengguna', ManajemenPengguna::class)->name('manajemen.pengguna');
Route::get('/admin/profil', AdminProfil::class)->name('admin.profil');
Route::get('/export-pdf', [LaporanController::class, 'cetakPdf'])
    ->name('export.pdf');


Route::get('/logout', function () {
    session()->flush();
    return redirect('/pilih-role');
})->name('logout');


