<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class GuruController extends Controller
{
    function index() {
        $guru = DB::table('dataguru')
            ->select('id', 'nama_guru','jabatan','mapel','no_hp')
            ->get();
        $mapel_umum = DB::table('mapelumum')
            ->select('mapel')
            ->get();
        $mapel_agama = DB::table('mapelagama')
            ->select('mapel')
            ->get();
        return view('dataguru', [
            'semua_guru' => $guru,
            'mapel_umum' => $mapel_umum,
            'mapel_agama' => $mapel_agama,
        ]);
    }
    function delete($id){
        $guru = DB::table('dataguru')
        ->where('id', '=', $id)->delete();
        return redirect('/data-guru');
    }
    function store(Request $request){
        DB::table('dataguru')->insert([
            'nama_guru' => $request->nama_guru,
            'jabatan' => $request->jabatan,
            'mapel' => $request->mapel,
            'no_hp' => $request->no_hp,
        ]);
        return redirect('/data-guru');
    }
    function update(Request $request){
        DB::table('dataguru')
              ->where('id', '=', $request->id)
              ->update([
            'nama_guru' => $request->nama_guru,
            'jabatan' => $request->jabatan,
            'mapel' => $request->mapel,
            'no_hp' => $request->no_hp,
              ]);
        return redirect('/data-guru');
    }
}
