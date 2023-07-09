<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class AgamaController extends Controller
{
    function index() {
        $agama = DB::table('mapelagama')
            ->select('id','kode_agama', 'mapel', 'durasi')
            ->get();
        $data_kelas = DB::table('datakelas')
            ->select('kelas')
            ->get();
        return view('mapelagama', [
            'semua_agama' => $agama,
            'data_kelas' => $data_kelas,
        ]);
    }
    function delete($id){
        $guru = DB::table('mapelagama')
        ->where('id', '=', $id)->delete();
        return redirect('/mapel-agama');
    }
    function store(Request $request){
        // VALIDATE
        $message = $request->validate([
            'kode_agama' => 'required, unique:mapelagama',
            'mapel' => 'required',
            'durasi' => 'required',
        ]);
        // IF NOT VALIDATE RETURN WITH FLASH MESSAGE
        if($message){
            return redirect('/mapel-agama')->with('danger', $message);
        }

        DB::table('mapelagama')->insert([
            'kode_agama' => $request->kode_agama,
            'mapel' => $request->mapel,
            // 'kelas' => $request->kelas,
            'durasi' => (int)$request->durasi,
        ]);
        return redirect('/mapel-agama');
    }
    function update(Request $request){
        // VALIDATE
        $message = $request->validate([
            'agama' => 'required, unique:mapelagama',
            'mapel' => 'required',
            'durasi' => 'required',
        ]);
        // IF NOT VALIDATE RETURN WITH FLASH MESSAGE
        if($message){
            return redirect('/mapel-agama')->with('danger', $message);
        }
        
        DB::table('mapelagama')
              ->where('id', '=', $request->id)
              ->update([
                'kode_agama' => $request->agama,
                'mapel' => $request->mapel,
                // 'kelas' => $request->kelas,
                'durasi' => $request->durasi,
              ]);
        return redirect('/mapel-agama');
    }
}
