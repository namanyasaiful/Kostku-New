<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\PenghuniAuthController;
use App\Http\Controllers\Auth\PengelolaAuthController;
use App\Http\Controllers\Auth\SuperAdminAuthController;
use App\Http\Controllers\Auth\LupaPasswordController;
use App\Http\Controllers\Auth\LoginController;

// Penghuni
use App\Http\Controllers\Penghuni\PengaduanPenghuniController;
use App\Http\Controllers\Penghuni\PembayaranPenghuniController;
use App\Http\Controllers\Penghuni\DashboardPenghuniController;
use App\Http\Controllers\Penghuni\KamarPenghuniController;

// Pengelola
use App\Http\Controllers\Pengelola\DashboardPengelolaController;
use App\Http\Controllers\Pengelola\KamarPengelolaController;
use App\Http\Controllers\Pengelola\PembayaranPengelolaController;
use App\Http\Controllers\Pengelola\PengaduanPengelolaController;
use App\Http\Controllers\Pengelola\PenghuniPengelolaController;

// Super Admin
use App\Http\Controllers\SuperAdmin\DashboardSuperAdminController;
use App\Http\Controllers\SuperAdmin\ManajemenPengelolaController;
use App\Http\Controllers\SuperAdmin\ManajemenPenghuniController;
use App\Http\Controllers\SuperAdmin\PenilaianPenghuniController;
use App\Http\Controllers\SuperAdmin\PengaduanSuperAdminController;
use App\Http\Controllers\SuperAdmin\PembayaranSuperAdminController;
use App\Http\Controllers\SuperAdmin\LogAuditController;

/*
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('pages.landing.role-select');
})->name('landing');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

// Login Controller
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'view')->name('login');
    Route::post('/postLogin', 'sessionLogin')->name('sessionLogin');
});

// Lupa Password
Route::controller(LupaPasswordController::class)->group(function () {
    Route::get('/lupa-password', 'view')->name('lupa-password');
});

// Penghuni Auth
Route::controller(PenghuniAuthController::class)->group(function () {
    Route::post('/penghuni/register/store', 'store')->name('penghuni.store');
    Route::post('/penghuni/logout', 'logout')->name('penghuni.logout');
    Route::get('/penghuni/register', 'viewRegister')->name('register.penghuni');
});

// Pengelola Auth
Route::controller(PengelolaAuthController::class)->group(function () {
    Route::post('/pengelola/register/store', 'store')->name('pengelola.store');
    Route::post('/pengelola/logout', 'logout')->name('pengelola.logout');
    Route::get('/pengelola/register', 'viewRegister')->name('register.pengelola');
});

// Super Admin Auth
Route::controller(SuperAdminAuthController::class)->group(function () {
    Route::get('/superadmin/login', 'view')->name('superadmin.login');
    Route::post('/superadmin/postLogin', 'sessionLogin')->name('superadmin.sessionLogin');
    Route::post('/superadmin/logout', 'logout')->name('superadmin.logout');
});

/*
|--------------------------------------------------------------------------
| PENGHUNI
|--------------------------------------------------------------------------
*/

// Dashboard Penghuni
Route::controller(DashboardPenghuniController::class)->group(function () {
    Route::get('/penghuni/dashboard-penghuni', 'viewDashboard')->name('dashboard.penghuni');
    Route::get('/penghuni/kode-kost', 'kodeKost')->name('kodekost.penghuni');
    Route::post('/penghuni/dashboard-penghuni/join', 'joinKost')->name('penghuni.join');
});

// Pembayaran Penghuni
Route::controller(PembayaranPenghuniController::class)->group(function () {
    Route::get('/penghuni/pembayaran-penghuni', 'viewPembayaran')->name('pembayaran.penghuni');
});

// Pengaduan Penghuni
Route::controller(PengaduanPenghuniController::class)->group(function () {
    Route::get('/penghuni/pengaduan-penghuni', 'viewPengaduan')->name('pengaduan.penghuni');
    Route::post('/penghuni/pengaduan-penghuni/store', 'storePengaduanPenghuni')->name('penghuni.pengaduan.store');
    Route::patch('/penghuni/pengaduan/{id}/batal', [PengaduanPenghuniController::class, 'batalPengaduan'])->name('pengaduan.batal');
});

// Kamar Penghuni
Route::controller(KamarPenghuniController::class)->group(function () {
    Route::get('/penghuni/kamar-penghuni', 'viewKamar')->name('kamar.penghuni');
    Route::post('/penghuni/kamar-penghuni/request/{id}', 'requestKamar')->name('kamar.request');
    Route::post('/penghuni/kamar-penghuni/approve/{id}', 'approveRequest')->name('approve.request');
    Route::post('/penghuni/kamar-penghuni/leave', 'penghuniOut')->name('penghuni.out');
});

/*
|--------------------------------------------------------------------------
| PENGELOLA
|--------------------------------------------------------------------------
*/

// Dashboard Pengelola
Route::controller(DashboardPengelolaController::class)->group(function () {
    Route::get('/pengelola/dashboard-pengelola', 'viewDashboard')->name('dashboard.pengelola');
});

// Kamar Pengelola
Route::controller(KamarPengelolaController::class)->group(function () {
    Route::get('/pengelola/kamar-pengelola', 'viewKamar')->name('kamar.pengelola');
    Route::post('/pengelola/kamar-pengelola/store', 'storeKamar')->name('kamar.store');
    Route::post('/pengelola/kamar-pengelola/update/{id}', 'updateKamar')->name('kamar.update');
    Route::post('/pengelola/kamar-pengelola/delete/{id}', 'deleteKamar')->name('kamar.destroy');
});

// Pembayaran Pengelola
Route::controller(PembayaranPengelolaController::class)->group(function () {
    Route::get('/pengelola/pembayaran-pengelola', 'viewPembayaran')->name('pembayaran.pengelola');
});

// Pengaduan Pengelola
Route::controller(PengaduanPengelolaController::class)->group(function () {
    Route::get('/pengelola/pengaduan-pengelola', 'viewPengaduan')->name('pengaduan.pengelola');
    Route::post('/pengelola/pengaduan-pengelola/store', 'pengelolaStorePengaduan')->name('pengelola.pengaduan.store');
});

// Penghuni Pengelola
Route::controller(PenghuniPengelolaController::class)->group(function () {
    Route::get('/pengelola/penghuni-pengelola', 'viewPenghuni')->name('penghuni.pengelola');
    Route::post('/pengelola/penghuni-pengelola/approve/{penghuni}', 'approvePenghuni')->name('penghuni.approve');
    Route::post('/pengelola/penghuni-pengelola/reject/{penghuni}', 'rejectPenghuni')->name('penghuni.reject');
    Route::post('/pengelola/penghuni-pengelola/keluar/{penghuni}', 'approveKeluar')->name('penghuni.approve_keluar');
});

/*
|--------------------------------------------------------------------------
| SUPER ADMIN
|--------------------------------------------------------------------------
*/

// Dashboard Super Admin
Route::controller(DashboardSuperAdminController::class)->group(function () {
    Route::get('/superadmin/dashboard-superadmin', 'viewDashboard')->name('dashboard.superadmin');
});

// Manajemen Pengelola - superadmin
Route::controller(ManajemenPengelolaController::class)->group(function(){
    Route::get('/superadmin/manajemen-pengelola','viewManajemenPengelola')->name('manajemen-pengelola.superadmin');
    Route::post('/superadmin/manajemen-pengelola/setujui/{pengelola}', 'setujuiPengelola')->name('pengelola.setujui');
    Route::post('/superadmin/manajemen-pengelola/tolak/{pengelola}',   'tolakPengelola')->name('pengelola.tolak');
    Route::post('/superadmin/manajemen-pengelola/batasi/{pengelola}',  'batasiPengelola')->name('pengelola.batasi');
    Route::post('/superadmin/manajemen-pengelola/aktifkan/{pengelola}','aktifkanPengelola')->name('pengelola.aktifkan');
});

// Manajemen Penghuni - superadmin
Route::controller(ManajemenPenghuniController::class)->group(function(){
    Route::get('/superadmin/manajemen-penghuni','viewManajemenPenghuni')->name('manajemen-penghuni.superadmin');
    Route::post('/superadmin/manajemen-penghuni/batasi/{penghuni}',  'batasipenghuni')->name('penghuni.batasi');
    Route::post('/superadmin/manajemen-penghuni/aktifkan/{penghuni}','aktifkanpenghuni')->name('penghuni.aktifkan');
});

// Penilaian Penghuni - superadmin
Route::controller(PenilaianPenghuniController::class)->group(function () {
    Route::get('/superadmin/penilaian-penghuni', 'viewPenilaianPenghuni')->name('penilaian-penghuni.superadmin');
});

// Pengaduan - superadmin
Route::controller(PengaduanSuperAdminController::class)->group(function () {
    Route::get('/superadmin/pengaduan-superadmin', 'viewPengaduanSuperAdmin')->name('pengaduan-superadmin.superadmin');
});

// Pembayaran - superadmin
Route::controller(PembayaranSuperAdminController::class)->group(function () {
    Route::get('/superadmin/pembayaran-superadmin', 'viewPembayaranSuperAdmin')->name('pembayaran-superadmin.superadmin');
});

// Log Audit - superadmin
Route::controller(LogAuditController::class)->group(function () {
    Route::get('/superadmin/log-audit', 'viewLogAudit')->name('log-audit.superadmin');
});
