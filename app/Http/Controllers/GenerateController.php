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
        $dataGuru = DataGuru::orderBy('kelas', 'asc')
                                ->select('nama_guru', 'kelas', 'code_mapel')
                                ->whereNotIn('code_mapel', ['PJOK', 'PRAMUKA'])
                                ->get();

        $mapel = [];
        $limit_mapel_agama = (int)generalSetting('mapel_agama')->value;
        $limit_mapel_umum = (int)generalSetting('mapel_umum')->value;
        $kelas_now = '';
        $max_mapel_agama = 0;
        $max_mapel_umum = 0;

        foreach ($dataGuru as $key => $value) {
            // CHECK MAPEL IS SAME WITH DATA GURU
            $mapel_check = MapelAgama::where('kode_agama', $value->code_mapel)->first() ?? MapelUmum::where('kode_umum', $value->code_mapel)->first();
            $result = [
                'nama_guru' => $value->nama_guru,
                'kelas' => $value->kelas,
                'mapel' => $mapel_check->mapel,
                'code_mapel' => $value->code_mapel,
                'durasi' => $mapel_check->durasi,
            ];
            
            // CHECK KELAS
            if ($kelas_now != $value->kelas) {
                $kelas_now = $value->kelas;
                $max_mapel_agama = 0;
                $max_mapel_umum = 0;
            }

            // CHECK MAPEL LIMIT WITH CODE AGAMA BY KELAS
            if ($kelas_now == $value->kelas) {
                if ($mapel_check->kode_agama != null) {
                    if ($max_mapel_agama < $limit_mapel_agama) {
                        $max_mapel_agama++;
                        array_push($mapel, $result);
                    }
                }else{
                    if ($max_mapel_umum < $limit_mapel_umum) {
                        $max_mapel_umum++;
                        array_push($mapel, $result);
                    }
                }
            }
        }

        // RULE BARU
        // $result_generate = $this->generateAll($mapel);

        // RULE LAMA
        $result_generate = $this->generateWaktu($mapel);
        // $result_generate = $this->generateKelas($generate_waktu);

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
        $index_mapel_check = 0;
        $max_mapel = count($mapel) - 1;
        $ruang = DataRuangan::InRandomOrder()->get();
        $index_data_ruangan = 0;
        $data_kelas = DataKelas::orderBy('kelas', 'asc')->get();
        $index_day = 0;
        $kelas_now = $data_kelas[0]->kelas;

        foreach ($data_kelas as $key => $kelas) {
            $sorting = 1;
            $mapel_in_class = collect($mapel)->where('kelas', $kelas->kelas);
            if (count($mapel_in_class) < 20) {
                $mapel_in_class = $mapel_in_class->concat($mapel_in_class)
                                ->concat($mapel_in_class)
                                ->concat($mapel_in_class);
            }

            foreach ($mapel_in_class as $index_mapel => $value) {
                $not_avaliabe_time_start = '';
                $not_avaliabe_time_end = '';
                
                // IF WAKTU END KBM AND HARI SABTU IS BREAK
                if ($end_kbm <= $time_now && $dayOfWeek[$index_day] == 'Sabtu') {
                    $time_now = Carbon::parse('07:00:00');
                    $waktu_mulai = Carbon::parse('07:00:00');
                    $waktu_selesai = Carbon::parse('07:00:00');
                    $index_day = 0;
                    break;
                }

                // IF WAJTU END KBM IS CHANGE DAY AND RESET TIME
                if ($end_kbm <= $time_now) {
                    $time_now = Carbon::parse('07:00:00');
                    $waktu_mulai = Carbon::parse('07:00:00');
                    $waktu_selesai = Carbon::parse('07:00:00');
                    $index_day++;
                }

                // WAKTU TIDAK TERSEDIA
                foreach ($time_not_found as $tkey => $time) {
                    $start_time_not_available_in_days = Carbon::parse(explode('-', $time->waktu)[0]);
                    $end_time_not_available_in_days = Carbon::parse(explode('-', $time->waktu)[1]);
                    if ($time->hari == $dayOfWeek[$index_day]) {
                        if ($time_now >= $start_time_not_available_in_days && $time_now < $end_time_not_available_in_days) {
                            $not_avaliabe_time_start = explode('-', $time->waktu)[0];                   
                            $not_avaliabe_time_end = explode('-', $time->waktu)[1];
                            // dd($time->waktu);
                        }

                    }
                }

                // ADD WAKTU MULAI AND WAKTU SELESAI MAPEL WITH DURASI
                $not_avaliabe_time_start = Carbon::parse($not_avaliabe_time_start);
                $not_avaliabe_time_end = Carbon::parse($not_avaliabe_time_end);
                
                if ($time_now >= $not_avaliabe_time_start && $time_now < $not_avaliabe_time_end) {
                    $time_now = Carbon::parse($not_avaliabe_time_end);
                    $time_now = $time_now->addMinutes($value['durasi']);

                    $waktu_mulai = Carbon::parse($not_avaliabe_time_end);
                    $waktu_mulai = $waktu_mulai->addMinutes($value['durasi']);

                    $waktu_selesai = Carbon::parse($not_avaliabe_time_end);
                    $waktu_selesai = $waktu_selesai->addMinutes($value['durasi']);
                }else{
                    $time_now = $time_now->addMinutes($value['durasi']);
                    $waktu_mulai = $waktu_mulai->addMinutes($value['durasi']);
                    $waktu_selesai = $waktu_selesai->addMinutes($value['durasi']);
                }
                
                // MERGE TO GET TIME START AND TIME END
                $waktu_mulai = $waktu_mulai->subMinutes($value['durasi']);
                $waktu_kbm = $waktu_mulai->format('H:i') . '-' . $waktu_selesai->format('H:i');

                // PJOK, PRAMUKA DI HARI SABTU
                if ($dayOfWeek[$index_day] == 'Sabtu') {
                    if ($waktu_mulai >= Carbon::parse('10:00') && $waktu_mulai < Carbon::parse('11:00') ) {
                        $value['mapel'] = 'PJOK';
                        $value['code_mapel'] = 'PJOK';
                        $value['durasi'] = 60;
                        $value['nama_guru'] = DataGuru::where('code_mapel', 'PJOK')->first()->nama_guru ?? null;
                    }elseif ($waktu_mulai >= Carbon::parse('11:00')) {
                        $value['mapel'] = 'PRAMUKA';
                        $value['code_mapel'] = 'PRAMUKA';
                        $value['durasi'] = 60;
                        $value['nama_guru'] = DataGuru::where('code_mapel', 'PRAMUKA')->first()->nama_guru ?? null;
                    }
                }

                // IF DAY SABTU AND END TIME KBM THEN BREAK
                // if ($dayOfWeek[$index_day] == 'Sabtu' && $waktu_selesai->format('H:i') >= $end_kbm) {
                //     $index_day = 0;
                //     break;
                // }

                $data[$no] = [
                    'hari' => $dayOfWeek[$index_day],
                    'waktu' => $waktu_kbm,
                    'number_day' => $key+1,
                    'number_sorting' => $sorting,
                    'kode_mapel' => $value['code_mapel'],
                    'mapel' => $value['mapel'],
                    'durasi' => $value['durasi'],
                    'kelas' => $value['kelas'],
                    'ruang' => $ruang[$index_data_ruangan]->ruang ?? null,
                    'nama_guru' => $value['nama_guru'],
                ];

                $waktu_mulai = $waktu_mulai->addMinutes($value['durasi']);
                $no++;
                $sorting++;

                if ($index_data_ruangan < (count($ruang) -1)) {
                    $index_data_ruangan++;
                }else {
                    $index_data_ruangan = 0;
                }
            }

            $index_day = 0;
        }

        // dd($data);
        
        // RULE 2
        // foreach ($data as $key => $value) {
        //     $time_start = explode('-', $value['waktu'])[0];
        //     $time_start_kbm = Carbon::parse($time_start);
        //     $time_end = explode('-', $value['waktu'])[1];
        //     $time_end_kbm = Carbon::parse($time_end);

        //     if ($this->end_kbm < $time_end_kbm) {
        //         $guru = DataGuru::pluck('code_mapel');
        //         $mapel_pengganti = MapelAgama::where('durasi', 30)->whereIn('kode_agama', $guru)
        //                             ->union(MapelUmum::where('durasi', 30)->whereIn('kode_umum', $guru))
        //                             ->inRandomOrder()
        //                             ->first();
        //         $dataGuru = DataGuru::where('code_mapel', $mapel_pengganti->kode_agama ?? $mapel_pengganti->kode_umum)->first();
        //         $data[$key]['kode_mapel'] = $mapel_pengganti->kode_agama ?? $mapel_pengganti->kode_umum;
        //         $data[$key]['mapel'] = $mapel_pengganti->mapel ?? null;
        //         $data[$key]['durasi'] = $mapel_pengganti->durasi ?? null;
        //         $data[$key]['nama_guru'] = $dataGuru->nama_guru ?? null;
        //         $first_time = explode('-', $data[$key]['waktu'])[0];
        //         $last_time = explode('-', $data[$key]['waktu'])[1];
        //         $last_time = Carbon::parse($last_time)->subMinutes($mapel_pengganti->durasi)->format('H:i');
        //         $data[$key]['waktu'] = $first_time . '-' . $last_time;
        //     }

        //     if ($this->end_kbm <= $time_start_kbm) {
        //         unset($data[$key]);
        //     }
        // }

        $data = collect($data)->sortBy('number_day')->toArray();

        return $data;
    }

    // public function generateKelas($data)
    // {
    //     $data_kelas = DataKelas::orderBy('kelas', 'asc')->get();
    //     $data_ruangan = DataRuangan::orderBy('ruang', 'asc')->get();
    //     $data_guru = DataGuru::orderBy('id', 'asc')->get();

    //     $result_generate_kelas = [];
    //     $index_data_ruangan = 0;
    //     $no = 0;
    //     foreach ($data_kelas as $kelas) {
    //         $result_kelas = [
    //             'kelas' => $kelas->kelas,
    //             'ruang' => $data_ruangan[$index_data_ruangan]->ruang,
    //         ];
    //         array_push($result_generate_kelas, $result_kelas);
    //         if ($index_data_ruangan < $data_ruangan->count()) {
    //             $index_data_ruangan ++;
    //         }else{
    //             $index_data_ruangan = 0;
    //         }
    //     }

    //     $dataGenerate = [];
    //     $count_result_generate_kelas = count($result_generate_kelas) - 1;
    //     $index_result_generate_kelas = 0;
    //     $kelas_now = null;
    //     $day_now = null;
    //     $count_same_day_class = 1;

    //     foreach ($result_generate_kelas as $key => $value) {
    //         foreach ($data as $hkey => $detail) {
    //             // CHECK IS DATA GENERATE IN ONE DAY AND CLASS IF MAPEL SAME > 4 IN ONE DAY REDECLARATION MAPEL SAME IN ONE DAY
    //             if ($kelas_now == $value['kelas'] && $day_now == $detail['hari']) {
    //                 if ($count_same_day_class >= 4) {
    //                     $detail['kode_mapel'] = $data[$hkey - $count_same_day_class]['kode_mapel'];
    //                     $detail['mapel'] = $data[$hkey - $count_same_day_class]['mapel'];
    //                     $detail['durasi'] = $data[$hkey - $count_same_day_class]['durasi'];
    //                 }else{
    //                     $count_same_day_class++;
    //                 }
    //             }else{
    //                 $kelas_now = $value['kelas'];
    //                 $day_now = $detail['hari'];
    //                 $count_same_day_class = 1;
    //             }
                
    //             $result = [
    //                 'hari' => $detail['hari'] ?? '-',
    //                 'waktu' => $detail['waktu'] ?? '-',
    //                 'kode_mapel' => $detail['kode_mapel'] ?? '-',
    //                 'mapel' => $detail['mapel'] ?? '-',
    //                 'durasi' => $detail['durasi'] ?? '-',
    //                 'nama_guru' => $detail['nama_guru'] ?? '-',
    //                 'kelas' => $value['kelas'] ?? '-',
    //                 'ruang' => $value['ruang'] ?? '-',
    //             ];
                
    //             array_push($dataGenerate, $result);
    
    //             if ($index_result_generate_kelas < $count_result_generate_kelas) {
    //                 $index_result_generate_kelas++;
    //             }else {
    //                 $index_result_generate_kelas = 0;
    //             }
    //         }
    //     }
    
    //     return $dataGenerate;
    // }

    public function deleteAll()
    {
        $data = Generate::all();
        foreach ($data as $value) {
            $value->delete();
        }

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function generateAll($mapel)
    {  
        $day = [
            'Senin', 
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
        ];
        
        $data = [];
        $no = 1;
        $sorting = 1;
        $waktu_mulai_mapel = Carbon::parse('08:00');
        $waktu_berakhir_mapel = Carbon::parse('08:00');
        $waktu_selesai_kbm = Carbon::parse('12:00');
        $matapelajaran = DataGuru::whereNotIn('code_mapel', ['PJOK', 'PRAMUKA'])->whereNotNull('kelas')->orderBy('kelas', 'asc')->get();
        $index_hari_sekarang = 0;
        $ruang = DataRuangan::InrandomOrder()->get();
        $index_data_ruangan = 0;
        $max_mapel_per_hari = 3;
        $index_mapel_per_hari = 0;
        $status_mapel_lebih_max = false;
        $time_not_found = Waktu::get();
        // GENERATE DATA WITH RULE 1
        // JAM PELAJARAN JAM 07:00 - 14:00 MENAMBAHKAN JAM SESUAI DURASI MAPEL YANG DIAMBIL
        foreach ($matapelajaran as $key1 => $guru) {
            // FIND MAPEL
            $mapel_find = MapelUmum::where('kode_umum', $guru->code_mapel)->first() ?? 
                            MapelAgama::where('kode_agama', $guru->code_mapel)->first();
            
            if ($mapel_find) {
                // STATMENT MAX MAPEL PER HARI
                if ($index_mapel_per_hari >= $max_mapel_per_hari) {
                    $status_mapel_lebih_max = true;
                    if ($waktu_berakhir_mapel >= $waktu_selesai_kbm) {
                        $index_mapel_per_hari = 0;
                    }
                }else{
                    $status_mapel_lebih_max = false;
                    $index_mapel_per_hari++;
                }
                
                if ($status_mapel_lebih_max == true) {
                    // GET MAPEL IN FIST DAY AGAIN
                    $kode_mapel = $data[$sorting - $max_mapel_per_hari]['kode_mapel'];
                    $durasi = $data[$sorting - $max_mapel_per_hari]['durasi'];
                    $kelas = $data[$sorting - $max_mapel_per_hari]['kelas'];
                    $mapel = $data[$sorting - $max_mapel_per_hari]['mapel'];
                    $nama_guru = $data[$sorting - $max_mapel_per_hari]['nama_guru'];
                    $ruang_repeat = $data[$sorting - $max_mapel_per_hari]['ruang'];
                    // ADD ARRAY TO DATA
                    $result = [
                        'hari' => $day[$index_hari_sekarang],
                        'waktu' => $waktu_mulai_mapel->format('H:i') . ' - ' . $waktu_berakhir_mapel->addMinutes($durasi)->format('H:i'),
                        'kode_mapel' => $kode_mapel,
                        'mapel' => $mapel,
                        'durasi' => $durasi,
                        'nama_guru' => $nama_guru,
                        'kelas' => $kelas,
                        'ruang' => $ruang_repeat,
                    ];

                    array_push($data, $result);

                    // REPEAT LOOPING DATAGURU -1 
                    if ($key1 > 0) {
                        $key1--;
                    }
                }else{
                    $kode_mapel = $mapel_find->kode_umum ?? $mapel_find->kode_agama;
                    $durasi = $mapel_find->durasi;
                    $kelas = $guru->kelas;
                    $mapel = $guru->mapel;
                    $nama_guru = $guru->nama_guru;
                }

                // STATMENT IF WAKTU BERAKHIR MAPEL > WAKTU SELESAI KBM
                if ($waktu_berakhir_mapel >= $waktu_selesai_kbm) {
                    $waktu_mulai_mapel = Carbon::parse('08:00');
                    $waktu_berakhir_mapel = Carbon::parse('08:00');

                    // SWITCH DAY
                    $index_hari_sekarang++;
                    if ($index_hari_sekarang > 5) {
                        $index_hari_sekarang = 0;
                    }
                }else{
                    // ADD TIME
                    $waktu_berakhir_mapel->addMinutes($durasi);
                }

                // CHECK TIME IS NOT AVAILABLE
                foreach ($time_not_found as $tkey => $time) {
                    $start_time_not_available_in_days = Carbon::parse(explode('-', $time->waktu)[0]);
                    $end_time_not_available_in_days = Carbon::parse(explode('-', $time->waktu)[1]);
                    $update_berakhir = Carbon::parse(explode('-', $time->waktu)[1]);
                    if ($time->hari == $day[$index_hari_sekarang]) {
                        // IF TIME NOW IN RANGE TIME NOT FOUND UPDATE START TIME AND END TIME
                        if ($waktu_berakhir_mapel->between($start_time_not_available_in_days, $end_time_not_available_in_days)) {
                            $waktu_mulai_mapel = $end_time_not_available_in_days;
                            // GETTING RANGE MINUTE IN start_time_not_available_in_days end_time_not_available_in_days
                            $range_minute = $start_time_not_available_in_days->diffInMinutes($end_time_not_available_in_days);
                            // ADD TIME
                            $waktu_berakhir_mapel->addMinutes($range_minute);
                            // dd($waktu_mulai_mapel, $waktu_berakhir_mapel);
                        }
                    }
                }

                // IF TIME SAME ADD END REDESCRIPTION MINUTE
                if ($waktu_mulai_mapel == $waktu_berakhir_mapel) {
                    $waktu_berakhir_mapel->addMinutes($durasi);
                }

                // PJOK, PRAMUKA DI HARI SABTU
                if ($day[$index_hari_sekarang] == 'Sabtu') {
                    if ($waktu_mulai_mapel >= Carbon::parse('10:00') && $waktu_mulai_mapel < Carbon::parse('11:00') ) {
                        $kode_mapel = 'PJOK';
                        $mapel = 'PJOK';
                        $durasi = 60;
                        $nama_guru = DataGuru::where('kelas', $guru->kelas)->where('code_mapel', 'PJOK')->first()->nama_guru ?? null;
                    }elseif ($waktu_mulai_mapel >= Carbon::parse('11:00')) {
                        $kode_mapel = 'PRAMUKA';
                        $mapel = 'PRMAUKA';
                        $durasi = 60;
                        $nama_guru = DataGuru::where('kelas', $guru->kelas)->where('code_mapel', 'PRAMUKA')->first()->nama_guru ?? null;
                    }
                }

                $result = [
                    'number_day' => $index_hari_sekarang,
                    'hari' => $day[$index_hari_sekarang],
                    'waktu' => $waktu_mulai_mapel->format('H:i') . '-' . $waktu_berakhir_mapel->format('H:i'),
                    'kode_mapel' => $kode_mapel,
                    'kelas' => $kelas ?? '-',
                    'mapel' => $mapel,
                    'nama_guru' => $nama_guru,
                    'durasi' => $durasi,
                    'sorting' => $sorting,
                    'ruang' => $ruang[$index_data_ruangan]->ruang,
                ];

                array_push($data, $result);
                // UPDATE START TIME
                $waktu_mulai_mapel->addMinutes($durasi);
                $sorting++;
                // SWITCH RUANG
                $index_data_ruangan++;
                if ($index_data_ruangan >= $ruang->count()) {
                    $index_data_ruangan = 0;
                }
            }
        }

        // CHECK DATA IF END TIME > END TIME KBM
        foreach ($data as $key => $value) {
            // MENGAMBIL DATA ENDTIME IN WAKTU
            $waktu_berakhir = explode('-', $value['waktu'])[1];
            // IF WAKTU BERAKHIR > WAKTU SELESAI KBM THEN REMOVE DATA
            if (Carbon::parse($waktu_berakhir) > $waktu_selesai_kbm) {
                unset($data[$key]);
            }
        }

        
        $result = [];
        // GROUP BY KELAS AND DURASI
        $group_by_kelas = collect($data)->groupBy('kelas')->toArray();
        // GENERATE AGAIN DATA WITH RANDOM MAPEL IF DURASI SAME
        foreach ($group_by_kelas as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $mapel_random = collect($data)->where('kelas', $value1['kelas'])->where('durasi', $value1['durasi'])->random();

                $mapel = $mapel_random['mapel'] ?? null;
                $durasi = $value1['durasi'];
                $nama_guru = DataGuru::where('kelas', $value1['kelas'])->where('code_mapel', $mapel_random['kode_mapel'])->first()->nama_guru ?? null;
                $kelas = $value1['kelas'];
                $ruang_repeat = $value1['ruang'];
                $waktu_mulai_mapel = Carbon::parse(explode('-', $value1['waktu'])[0]);
                $waktu_berakhir_mapel = Carbon::parse(explode('-', $value1['waktu'])[1]);
                $sorting = $value1['sorting'] ?? null;
                $index_hari_sekarang = $value1['number_day'] ?? null;
                $kode_mapel = $mapel_random['kode_mapel'];
                $hari = $value1['hari'] ?? null;
                $result_generate2 = [
                    'number_day' => $index_hari_sekarang,
                    'hari' => $hari,
                    'waktu' => $waktu_mulai_mapel->format('H:i') . '-' . $waktu_berakhir_mapel->format('H:i'),
                    'kode_mapel' => $kode_mapel,
                    'kelas' => $kelas ?? '-',
                    'mapel' => $mapel,
                    'nama_guru' => $nama_guru,
                    'durasi' => $durasi,
                    'sorting' => $sorting,
                    'ruang' => $ruang_repeat,
                ];

                array_push($result, $result_generate2);
            }
        }
        
        // SORTING NUMBER DAY AND SORTING
        $result = collect($result)->sortBy('number_day')->sortBy('sorting')->toArray();
        
        // dd($data);
        return $result;
    }
}