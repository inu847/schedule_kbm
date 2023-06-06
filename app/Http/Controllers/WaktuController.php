<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class WaktuController extends Controller
{
    function index() {
        $waktu = DB::table('waktu')
            ->select('id', 'hari', 'waktu')
            ->get();
        return view('waktu', [
            'semua_waktu' => $waktu,
        ]);
    }
    function delete($id){
        $waktu = DB::table('waktu')
        ->where('id', '=', $id)->delete();
        return redirect('/data-waktu');
    }
    function store(Request $request){
        DB::table('waktu')->insert([
            'hari' => $request->hari,
            'waktu' => $request->waktu,
        ]);
        return redirect('/data-waktu');
    }

    function update(Request $request){
        DB::table('waktu')
              ->where('id', '=', $request->id)
              ->update([
                'hari' => $request->hari,
            'waktu' => $request->waktu,
              ]);
        return redirect('/data-waktu');
    }
    
}