<?php

namespace App\Http\Controllers;

use App\Models\DataGuru;
use App\Models\DataKelas;
use App\Models\DataRuangan;
use App\Models\Generate;
use App\Models\MapelAgama;
use App\Models\MapelUmum;
use App\Models\Waktu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class GenerateController extends Controller
{
    function index() {
        $generate = Generate::orderBy('id', 'asc')
            ->select('id', 'kode_mapel', 'mapel','kelas','ruang','nama_guru','hari', 'waktu')
            ->get();
        $mapel_umum = DB::table('mapelumum')
            ->select('kode_umum', 'mapel')
            ->get();
        $mapel_agama = DB::table('mapelagama')
            ->select('kode_agama', 'mapel')
            ->get();
        $data_kelas = DB::table('datakelas')
            ->select('kelas')
            ->get();
        $data_ruangan = DB::table('dataruangan')
            ->select('ruang')
            ->get();
        $data_guru = DB::table('dataguru')
            ->select('nama_guru')
            ->get();
        return view('generate', [
            'generate' => $generate,
            'mapel_umum' => $mapel_umum,
            'mapel_agama' => $mapel_agama,
            'data_kelas' => $data_kelas,
            'data_ruangan' => $data_ruangan,
            'data_guru' => $data_guru,
        ]);
    }

    function delete($id){
        $generate = Generate::find($id)->delete();
        
        return redirect()->back();
    }

    function store(Request $request)
    {
        $status = false;
        if ($request->generate == 'true') {
            $status = $this->generateJadwal($request);
        }else{
            $status = $this->createdJadwal($request);
        }

        if ($status) {
            return redirect()->back();
        }
    }

    public function createdJadwal($request)
    {
        $mapel_agama = MapelAgama::orderBy('kode_agama', 'asc')
                                    ->select('kode_agama', 'mapel')
                                    ->get();
        $mapel_umum = MapelUmum::orderBy('kode_umum', 'asc')
                                    ->select('kode_umum', 'mapel')
                                    ->get();
        $nama_mapel = '';
        foreach($mapel_agama as $mapel) {
            if($mapel->kode_agama == $request->kode_mapel) {
                $nama_mapel = $mapel->mapel;
            }
        }
        foreach($mapel_umum as $mapel) {
            if($mapel->kode_umum == $request->kode_mapel) {
                $nama_mapel = $mapel->mapel;
            }
        }
        DB::table('generate')->insert([
            'kode_mapel' => $request->kode_mapel,
            'mapel' => $nama_mapel,
            'kelas' => $request->kelas,
            'ruang' => $request->ruang,
            'nama_guru' => $request->nama_guru,
            'hari' =>  $request->hari,
            'waktu' => $request->waktu,
        ]);
        return true;
    }

    public function generateJadwal($request)
    {
        $mapel_agama = MapelAgama::orderBy('kode_agama', 'asc')
                                    ->get();

        $mapel_umum = MapelUmum::orderBy('kode_umum', 'asc')
                                    ->get();

        $mapel = [];
        
        foreach($mapel_agama as $value) {
            array_push($mapel, $value);
        }

        foreach($mapel_umum as $value) {
            array_push($mapel, $value);
        }

        $generate_waktu = $this->generateWaktu($mapel);
        $result_generate = $this->generateKelas($generate_waktu);

        foreach ($result_generate as $key => $data) {
            $create = Generate::create($data);
        }

        return true;
    }

    public function generateWaktu($mapel)
    {
        $end_kbm = Carbon::parse('12:00:00');
        $time_now = Carbon::parse('07:00:00');
        $waktu_mulai = Carbon::parse('07:00:00');
        $waktu_selesai = Carbon::parse('07:00:00');
        $time_not_found = Waktu::get();

        $dayOfWeek = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        ];

        $data = [];
        $no = 0;
        foreach ($dayOfWeek as $key => $day) {
            foreach ($mapel as $ckey => $value) {
                $not_avaliabe_time_start = '';
                $not_avaliabe_time_end = '';
                
                if ($end_kbm < $time_now && $day == 'Sabtu') {
                    $time_now = Carbon::parse('07:00:00');
                    $waktu_mulai = Carbon::parse('07:00:00');
                    $waktu_selesai = Carbon::parse('07:00:00');
                    break;
                }

                foreach ($time_not_found as $tkey => $time) {
                    if ($time->hari == $day) {
                        $not_avaliabe_time_start = explode('-', $time->waktu)[0];                   
                        $not_avaliabe_time_end = explode('-', $time->waktu)[1];                   
                    }
                }
                // dd($time_now);
                $not_avaliabe_time_start = Carbon::parse($not_avaliabe_time_start);
                $not_avaliabe_time_end = Carbon::parse($not_avaliabe_time_end);
                
                if ($time_now >= $not_avaliabe_time_start && $time_now < $not_avaliabe_time_end) {
                    $time_now = Carbon::parse($not_avaliabe_time_end);
                    $time_now = $time_now->addMinutes($value->durasi);

                    $waktu_mulai = Carbon::parse($not_avaliabe_time_end);
                    $waktu_mulai = $waktu_mulai->addMinutes($value->durasi);

                    $waktu_selesai = Carbon::parse($not_avaliabe_time_end);
                    $waktu_selesai = $waktu_selesai->addMinutes($value->durasi);
                }else{
                    $time_now = $time_now->addMinutes($value->durasi);
                    $waktu_mulai = $waktu_mulai->addMinutes($value->durasi);
                    $waktu_selesai = $waktu_selesai->addMinutes($value->durasi);
                }

                $waktu_mulai = $waktu_mulai->subMinutes($value->durasi);
                $waktu_kbm = $waktu_mulai->format('H:i') . '-' . $waktu_selesai->format('H:i');
                
                $data[$no] = [
                    'hari' => $day,
                    'waktu' => $waktu_kbm,
                    'kode_mapel' => $value->kode_umum ?? $value->kode_agama,
                    'mapel' => $value->mapel,
                    'durasi' => $value->durasi,
                    'kelas' => null,
                    'ruang' => null,
                    'nama_guru' => null,
                ];

                $waktu_mulai = $waktu_mulai->addMinutes($value->durasi);
                $no++;
            }
        }

        // dd($data);
        return $data;
    }

    public function generateKelas($data)
    {
        $data_kelas = DataKelas::orderBy('id', 'asc')->get();
        $data_ruangan = DataRuangan::orderBy('id', 'asc')->get();
        $data_guru = DataGuru::orderBy('id', 'asc')->get();
        // KELAS 10
        // RUANGAN B1
        $result_generate_kelas = [];
        $no = 0;
        foreach ($data_kelas as $key => $kelas) {
            foreach ($data_ruangan as $ckey => $ruang) {
                $result_kelas = [
                    'kelas' => $kelas->kelas,
                    'ruang' => $ruang->ruang,
                ];
                array_push($result_generate_kelas, $result_kelas);
            }
        }

        // dd($result_generate_kelas);
        $result_generate_guru = [];
        $count_guru = count($data_guru) - 1;
        $index_guru = 0;
        foreach ($result_generate_kelas as $key => $generate_kelas) {
            $result_guru = [
                'kelas' => $generate_kelas['kelas'],
                'ruang' => $generate_kelas['ruang'],
                'nama_guru' => $data_guru[$index_guru]->nama_guru,
            ];
            array_push($result_generate_guru, $result_guru);

            if ($index_guru < $count_guru) {
                $index_guru++;
            }else {
                $index_guru = 0;
            }
        }

        // dd($result_generate_guru);
        $dataGenerate = [];
        $count_result_generate_guru = count($result_generate_guru) - 1;
        $index_result_generate_guru = 0;

        foreach ($data as $hkey => $detail) {
            $result = [
                'hari' => $detail['hari'] ?? 'err',
                'waktu' => $detail['waktu'] ?? 'err',
                'kode_mapel' => $detail['kode_mapel'] ?? 'err',
                'mapel' => $detail['mapel'] ?? 'err',
                'durasi' => $detail['durasi'] ?? 'err',
                'kelas' => $result_generate_guru[$index_result_generate_guru]['kelas'] ?? 'err',
                'ruang' => $result_generate_guru[$index_result_generate_guru]['ruang'] ?? 'err',
                'nama_guru' => $result_generate_guru[$index_result_generate_guru]['nama_guru'] ?? 'err',
            ];
            
            array_push($dataGenerate, $result);

            if ($index_result_generate_guru < $count_result_generate_guru) {
                $index_result_generate_guru++;
            }else {
                $index_result_generate_guru = 0;
            }
        }

        // dd($dataGenerate);
        return $dataGenerate;
    }
}