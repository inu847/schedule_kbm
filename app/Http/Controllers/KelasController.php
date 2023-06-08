<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        $this->validasiKelas($request);

        DB::table('datakelas')->insert([
            'kelas' => $request->kelas,
            'jumlah_siswa' => $request->jumlah_siswa,
        ]);
        return redirect('/data-kelas');
    }
    function update(Request $request){

        $this->validasiUpdateKelas($request, $request->id);

        DB::table('datakelas')
              ->where('id', '=', $request->id)
              ->update([
                'kelas' => $request->kelas,
            'jumlah_siswa' => $request->jumlah_siswa,
              ]);
        return redirect('/data-kelas');
    }

    public function validasiKelas($request)
    {
        $this->validate($request, [
            'kelas'      => 'required|unique:datakelas,kelas',
            'jumlah_siswa'        => 'required',
        ]);
    }

    public function validasiUpdateKelas($request, $id)
    {
        $this->validate($request, [
            'kelas'      => ["required", Rule::unique('datakelas')->ignore($id, 'kelas'),],
            'jumlah_siswa'        => 'required',
        ]);
    }

}