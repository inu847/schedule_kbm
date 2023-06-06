<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class UmumController extends Controller
{
    function index() {
        $umum = DB::table('mapelumum')
            ->select('id','kode_umum', 'mapel', 'kelas','durasi')
            ->get();
        $data_kelas = DB::table('datakelas')
            ->select('kelas')
            ->get();
        return view('mapelumum', [
            'semua_umum' => $umum,
            'data_kelas' => $data_kelas,
        ]);
    }
    function delete($id){
        $umum = DB::table('mapelumum')
        ->where('id', '=', $id)->delete();
        return redirect('/mapel-umum');
    }
    function store(Request $request){
        DB::table('mapelumum')->insert([
            'kode_umum' => $request->kode_umum,
            'mapel' => $request->mapel,
            'kelas' => $request->kelas,
            'durasi' => $request->durasi,
        ]);
        return redirect('/mapel-umum');
    }
    function update(Request $request){
        DB::table('mapelumum')
              ->where('id', '=', $request->id)
              ->update([
                'kode_umum' => $request->umum,
                'mapel' => $request->mapel,
                'kelas' => $request->kelas,
                'durasi' => $request->durasi,
              ]);
        return redirect('/mapel-umum');
    }
}