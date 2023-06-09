<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data_kelas = DB::table('datakelas')->count();
        $data_guru = DB::table('dataguru')->count();
        $data_ruangan = DB::table('dataruangan')->count();
        $generate = DB::table('generate')->count();
        $mapel_umum = DB::table('mapelumum')->count();
        $mapel_agama = DB::table('mapelagama')->count();
        $waktu = DB::table('waktu')->count();
        
        return view('dashboard', [
            'data_kelas' => $data_kelas,
            'data_guru' => $data_guru,
            'data_ruangan' => $data_ruangan,
            'generate' => $generate,
            'mapel_agama' => $mapel_agama,
            'mapel_umum' => $mapel_umum,
            'waktu' => $waktu,
        ]);

    }
}
