<?php

namespace App\Http\Controllers;

use App\Models\MapelAgama;
use App\Models\MapelUmum;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class GuruController extends Controller
{
    function index() {
        $guru = DB::table('dataguru')
            ->select('id', 'nama_guru','jabatan','mapel','no_hp', 'code_mapel', 'kelas')
            ->get();
        $mapel = MapelUmum::whereNotNull('kode_umum')->union(MapelAgama::whereNotNull('kode_agama'))->get();
        foreach ($mapel as $key => $value) {
            $value['code_mapel'] = $value->kode_agama ?? $value->kode_umum;
        }

        return view('dataguru', [
            'semua_guru' => $guru,
            'mapel' => $mapel,
        ]);
    }
    function delete($id){
        $guru = DB::table('dataguru')
        ->where('id', '=', $id)->delete();
        return redirect('/data-guru');
    }
    function store(Request $request){
        $mapel = MapelUmum::where('kode_umum', '=', $request->code_mapel)->first() ?? MapelAgama::where('kode_agama', '=', $request->code_mapel)->first();

        DB::table('dataguru')->insert([
            'nama_guru' => $request->nama_guru,
            'jabatan' => $request->jabatan,
            'mapel' => $mapel->mapel,
            'no_hp' => $request->no_hp,
            'code_mapel' => $request->code_mapel,
        ]);
        return redirect('/data-guru');
    }
    function update(Request $request){
        $mapel = MapelUmum::where('kode_umum', '=', $request->code_mapel)->first() ?? MapelAgama::where('kode_agama', '=', $request->code_mapel)->first();

        DB::table('dataguru')
              ->where('id', '=', $request->id)
              ->update([
            'nama_guru' => $request->nama_guru,
            'jabatan' => $request->jabatan,
            'mapel' => $mapel->mapel,
            'code_mapel' => $request->code_mapel,
            'no_hp' => $request->no_hp,
              ]);
        return redirect('/data-guru');
    }
}
