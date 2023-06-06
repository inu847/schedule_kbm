<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class RuanganController extends Controller
{
    function index() {
        $ruang = DB::table('dataruangan')
            ->select('id', 'ruang')
            ->get();
        return view('dataruangan', [
            'semua_ruang' => $ruang,
        ]);
    }
    function delete($id){
        $ruang = DB::table('dataruangan')
        ->where('id', '=', $id)->delete();
        return redirect('/data-ruangan');
    }
    function store(Request $request){
        DB::table('dataruangan')->insert([
            'ruang' => $request->ruang,
        ]);
        return redirect('/data-ruangan');
    }
    function update(Request $request){
        DB::table('dataruangan')
              ->where('id', '=', $request->id)
              ->update([
                'ruang' => $request->ruang,
              ]);
        return redirect('/data-ruangan');
    }
}


