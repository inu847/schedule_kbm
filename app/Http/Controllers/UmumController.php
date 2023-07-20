<?php

namespace App\Http\Controllers;

use App\Models\DataKelas;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class UmumController extends Controller
{
    function index() {
        $umum = DB::table('mapelumum')
            ->select('id','kode_umum', 'mapel', 'durasi')
            ->get();
        $data_kelas = DataKelas::all();
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
        // VALIDATE
        $message = $request->validate([
            'kode_umum' => 'required, unique:mapelumum',
            'mapel' => 'required',
            'durasi' => 'required',
        ]);
        // IF NOT VALIDATE RETURN WITH FLASH MESSAGE
        if($message){
            return redirect('/mapel-umum')->with('danger', $message);
        }

        DB::table('mapelumum')->insert([
            'kode_umum' => $request->kode_umum,
            'mapel' => $request->mapel,
            // 'kelas' => $request->kelas,
            'durasi' => $request->durasi,
        ]);
        return redirect('/mapel-umum');
    }
    function update(Request $request){
        // VALIDATE
        $message = $request->validate([
            'kode_umum' => 'required, unique:mapelumum,kode_umum,'.$request->id,
            'mapel' => 'required',
            'durasi' => 'required',
        ]);
        // IF NOT VALIDATE RETURN WITH FLASH MESSAGE
        if($message){
            return redirect('/mapel-umum')->with('danger', $message);
        }

        DB::table('mapelumum')
              ->where('id', '=', $request->id)
              ->update([
                'kode_umum' => $request->umum,
                'mapel' => $request->mapel,
                // 'kelas' => $request->kelas,
                'durasi' => $request->durasi,
              ]);
        return redirect('/mapel-umum');
    }
}