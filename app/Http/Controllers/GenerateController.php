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
    private $end_kbm;

    public function __construct()
    {
        $this->end_kbm = Carbon::parse('12:00:00');
    }

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
                                ->whereNotIn('kode_umum', ['PJOK', 'PRAMUKA'])
                                    ->get();

        $mapel = [];
        $limit_mapel_agama = (int)generalSetting('mapel_agama')->value;
        $limit_mapel_umum = (int)generalSetting('mapel_umum')->value;

        foreach($mapel_agama as $key => $value) {
            $dataGuru = DataGuru::where('code_mapel', $value->kode_agama)
                                    ->select('nama_guru')
                                    ->first();
            $value->nama_guru = $dataGuru->nama_guru ?? null;
            if ($value->kode_agama && $limit_mapel_agama >= $key+1) {
                array_push($mapel, $value);
            }
        }

        foreach($mapel_umum as $key => $value) {
            $dataGuru = DataGuru::where('code_mapel', $value->kode_umum)
                                    ->select('nama_guru')
                                    ->first();
            $value->nama_guru = $dataGuru->nama_guru ?? null;
            if ($dataGuru->nama_guru && $limit_mapel_umum >= $key+1) {
                array_push($mapel, $value);
            }
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
        $end_kbm = $this->end_kbm;
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
            $sorting = 1;
            foreach ($mapel as $value) {
                $not_avaliabe_time_start = '';
                $not_avaliabe_time_end = '';
                
                if ($end_kbm <= $time_now) {
                    $time_now = Carbon::parse('07:00:00');
                    $waktu_mulai = Carbon::parse('07:00:00');
                    $waktu_selesai = Carbon::parse('07:00:00');
                    break;
                }

                // fungsi explode
                // '08:30', '09:00'
                // ['08:30', '09:00']
                foreach ($time_not_found as $tkey => $time) {
                    $start_time_not_available_in_days = Carbon::parse(explode('-', $time->waktu)[0]);
                    $end_time_not_available_in_days = Carbon::parse(explode('-', $time->waktu)[1]);
                    if ($time->hari == $day) {
                        if ($time_now >= $start_time_not_available_in_days && $time_now < $end_time_not_available_in_days) {
                            $not_avaliabe_time_start = explode('-', $time->waktu)[0];                   
                            $not_avaliabe_time_end = explode('-', $time->waktu)[1];
                            // dd($time->waktu);
                        }

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
                
                // PJOK, PRAMUKA DI HARI SABTU
                if ($day == 'Sabtu') {
                    if ($waktu_mulai >= Carbon::parse('10:00') && $waktu_mulai < Carbon::parse('11:00') ) {
                        $value->kode_umum = 'PJOK';
                        $value->kode_agama = 'PJOK';
                        $value->mapel = 'PJOK';
                        $value->durasi = 60;
                        $value->nama_guru = DataGuru::where('code_mapel', 'PJOK')->first()->nama_guru ?? null;
                    }elseif ($waktu_mulai >= Carbon::parse('11:00')) {
                        $value->kode_umum = 'PRAMUKA';
                        $value->kode_agama = 'PRAMUKA';
                        $value->mapel = 'PRAMUKA';
                        $value->durasi = 60;
                        $value->nama_guru = DataGuru::where('code_mapel', 'PRAMUKA')->first()->nama_guru ?? null;
                    }
                }

                $data[$no] = [
                    'hari' => $day,
                    'waktu' => $waktu_kbm,
                    'number_day' => $key+1,
                    'number_sorting' => $sorting,
                    'kode_mapel' => $value->kode_umum ?? $value->kode_agama,
                    'mapel' => $value->mapel,
                    'durasi' => $value->durasi,
                    'kelas' => null,
                    'ruang' => null,
                    'nama_guru' => $value->nama_guru,
                ];

                $waktu_mulai = $waktu_mulai->addMinutes($value->durasi);
                $no++;
                $sorting++;
            }
        }
        
        // RULE 2
        foreach ($data as $key => $value) {
            $time_start = explode('-', $value['waktu'])[0];
            $time_start_kbm = Carbon::parse($time_start);
            $time_end = explode('-', $value['waktu'])[1];
            $time_end_kbm = Carbon::parse($time_end);

            if ($this->end_kbm < $time_end_kbm) {
                $guru = DataGuru::pluck('code_mapel');
                $mapel_pengganti = MapelAgama::where('durasi', 30)->whereIn('kode_agama', $guru)
                                    ->union(MapelUmum::where('durasi', 30)->whereIn('kode_umum', $guru))
                                    ->inRandomOrder()
                                    ->first();
                $dataGuru = DataGuru::where('code_mapel', $mapel_pengganti->kode_agama ?? $mapel_pengganti->kode_umum)->first();
                $data[$key]['kode_mapel'] = $mapel_pengganti->kode_agama ?? $mapel_pengganti->kode_umum;
                $data[$key]['mapel'] = $mapel_pengganti->mapel ?? null;
                $data[$key]['durasi'] = $mapel_pengganti->durasi ?? null;
                $data[$key]['nama_guru'] = $dataGuru->nama_guru ?? null;
                $first_time = explode('-', $data[$key]['waktu'])[0];
                $last_time = explode('-', $data[$key]['waktu'])[1];
                $last_time = Carbon::parse($last_time)->subMinutes($mapel_pengganti->durasi)->format('H:i');
                $data[$key]['waktu'] = $first_time . '-' . $last_time;
            }

            if ($this->end_kbm <= $time_start_kbm) {
                unset($data[$key]);
            }
        }

        $data = collect($data)->sortBy('number_day')->toArray();

        return $data;
    }

    public function generateKelas($data)
    {
        $data_kelas = DataKelas::orderBy('kelas', 'asc')->get();
        $data_ruangan = DataRuangan::orderBy('ruang', 'asc')->get();
        $data_guru = DataGuru::orderBy('id', 'asc')->get();

        $result_generate_kelas = [];
        $index_data_ruangan = 0;
        $no = 0;
        foreach ($data_kelas as $kelas) {
            $result_kelas = [
                'kelas' => $kelas->kelas,
                'ruang' => $data_ruangan[$index_data_ruangan]->ruang,
            ];
            array_push($result_generate_kelas, $result_kelas);
            if ($index_data_ruangan < $data_ruangan->count()) {
                $index_data_ruangan ++;
            }else{
                $index_data_ruangan = 0;
            }
        }

        $dataGenerate = [];
        $count_result_generate_kelas = count($result_generate_kelas) - 1;
        $index_result_generate_kelas = 0;

        foreach ($result_generate_kelas as $key => $value) {
            foreach ($data as $hkey => $detail) {
                $result = [
                    'hari' => $detail['hari'] ?? 'err',
                    'waktu' => $detail['waktu'] ?? 'err',
                    'kode_mapel' => $detail['kode_mapel'] ?? 'err',
                    'mapel' => $detail['mapel'] ?? 'err',
                    'durasi' => $detail['durasi'] ?? 'err',
                    'nama_guru' => $detail['nama_guru'] ?? 'err',
                    'kelas' => $value['kelas'] ?? 'err',
                    'ruang' => $value['ruang'] ?? 'err',
                ];
                
                array_push($dataGenerate, $result);
    
                if ($index_result_generate_kelas < $count_result_generate_kelas) {
                    $index_result_generate_kelas++;
                }else {
                    $index_result_generate_kelas = 0;
                }
            }
        }


        // dd($dataGenerate);
        return $dataGenerate;
    }

    public function deleteAll()
    {
        $data = Generate::all();
        foreach ($data as $value) {
            $value->delete();
        }

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}