<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\AgamaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UmumController;
use App\Http\Controllers\WaktuController;
use App\Http\Controllers\GenerateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/login', function () {
//     return view('login');
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-guru', [GuruController::class, 'index'])->name('data-guru.index');
    Route::delete('/data-guru/delete/{id}', [GuruController::class, 'delete']);
    Route::post('/data-guru/store', [GuruController::class, 'store']);
    Route::post('/data-guru/update', [GuruController::class, 'update']);
    
    Route::get('/data-kelas', [KelasController::class, 'index'])->name('data-kelas.index');
    Route::delete('/data-kelas/delete/{id}', [KelasController::class, 'delete']);
    Route::post('/data-kelas/store', [KelasController::class, 'store']);
    Route::post('/data-kelas/update', [KelasController::class, 'update']);
    
    Route::get('/data-ruangan', [RuanganController::class, 'index'])->name('data-ruangan.index');
    Route::delete('/data-ruangan/delete/{id}', [RuanganController::class, 'delete']);
    Route::post('/data-ruangan/store', [RuanganController::class, 'store']);
    Route::post('/data-ruangan/update', [RuanganController::class, 'update']);
    
    Route::get('/mapel-agama', [AgamaController::class, 'index'])->name('mapel-agama.index');
    Route::delete('/mapel-agama/delete/{id}', [AgamaController::class, 'delete']);
    Route::post('/mapel-agama/store', [AgamaController::class, 'store']);
    Route::post('/mapel-agama/update', [AgamaController::class, 'update']);
    
    Route::get('/mapel-umum', [UmumController::class, 'index'])->name('mapel-umum.index');
    Route::delete('/mapel-umum/delete/{id}', [UmumController::class, 'delete']);
    Route::post('/mapel-umum/store', [UmumController::class, 'store']);
    Route::post('/mapel-umum/update', [UmumController::class, 'update']);
    
    Route::get('/data-waktu', [WaktuController::class, 'index'])->name('data-waktu.index');
    Route::delete('/data-waktu/delete/{id}', [WaktuController::class, 'delete']);
    Route::post('/data-waktu/store', [WaktuController::class, 'store']);
    Route::post('/data-waktu/update', [WaktuController::class, 'update']);
    
    Route::get('/generate-jadwal', [GenerateController::class, 'index'])->name('generate-jadwal.index');
    Route::delete('/generate/delete/{id}', [GenerateController::class, 'delete']);
    Route::post('/generate/store', [GenerateController::class, 'store'])->name('generate.store');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
