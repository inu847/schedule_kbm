<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class KelasController extends Controller
{
    function index() {
        $kelas = DB::table('datakelas')
            ->select('id', 'kelas','jumlah_siswa')
            ->get();
        return view('datakelas', [
            'semua_kelas' => $kelas,
        ]);
    }
    function delete($id){
        $kelas = DB::table('datakelas')
        ->where('id', '=', $id)->delete();
        return redirect('/data-kelas');
    }
    function store(Request $request){
        DB::table('datakelas')->insert([
            'kelas' => $request->kelas,
            'jumlah_siswa' => $request->jumlah_siswa,
        ]);
        return redirect('/data-kelas');
    }
    function update(Request $request){
        DB::table('datakelas')
              ->where('id', '=', $request->id)
              ->update([
                'kelas' => $request->kelas,
            'jumlah_siswa' => $request->jumlah_siswa,
              ]);
        return redirect('/data-kelas');
    }

}