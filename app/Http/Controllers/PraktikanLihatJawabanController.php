<?php

namespace App\Http\Controllers;


use App\Models\JawabanFitb;
use App\Models\JawabanJurnal;
use App\Models\JawabanMandiri;

class PraktikanLihatJawabanController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function my_array_unique($array, $keep_key_assoc = false){
        $duplicate_keys = array();
        $tmp = array();       
    
        foreach ($array as $key => $val){
            // convert objects to arrays, in_array() does not support objects
            if (is_object($val))
                $val = (array)$val;
    
            if (!in_array($val, $tmp))
                $tmp[] = $val;
            else
                $duplicate_keys[] = $key;
        }
    
        foreach ($duplicate_keys as $key)
            unset($array[$key]);
    
        return $keep_key_assoc ? $array : array_values($array);
    }
    
    public function __invoke($praktikan_id, $modul_id)
    {
        $allJawabanJurnal = [];

        $jawabans = JawabanFitb::where('praktikan_id', $praktikan_id)
            ->where('jawaban_fitbs.modul_id', $modul_id)
            ->leftJoin('soal_fitbs', 'jawaban_fitbs.soal_id', '=', 'soal_fitbs.id')
            ->join('moduls', 'jawaban_fitbs.modul_id', '=', 'moduls.id')
            ->where('moduls.isUnlocked', 1)
            ->select('jawaban_fitbs.jawaban', 'soal_fitbs.soal')
            ->get();
            
        foreach ($jawabans as $jawaban => $value)
            array_push($allJawabanJurnal, $value);  
            
        $jawabans = JawabanJurnal::where('praktikan_id', $praktikan_id)
            ->where('jawaban_jurnals.modul_id', $modul_id)
            ->leftJoin('soal_jurnals', 'jawaban_jurnals.soal_id', '=', 'soal_jurnals.id')
            ->join('moduls', 'jawaban_jurnals.modul_id', '=', 'moduls.id')
            ->where('moduls.isUnlocked', 1)
            ->select('jawaban_jurnals.jawaban', 'soal_jurnals.soal')
            ->get();
            
        foreach ($jawabans as $jawaban => $value)
            array_push($allJawabanJurnal, $value);  
            
        $jawabans = JawabanMandiri::where('praktikan_id', $praktikan_id)
            ->where('jawaban_mandiris.modul_id', $modul_id)
            ->leftJoin('soal_mandiris', 'jawaban_mandiris.soal_id', '=', 'soal_mandiris.id')
            ->join('moduls', 'jawaban_mandiris.modul_id', '=', 'moduls.id')
            ->where('moduls.isUnlocked', 1)
            ->select('jawaban_mandiris.jawaban', 'soal_mandiris.soal')
            ->get();
            
        foreach ($jawabans as $jawaban => $value)
            array_push($allJawabanJurnal, $value);

        $allJawabanJurnal = $this->my_array_unique($allJawabanJurnal);

        return response()->json([
            'message' => 'success',
            'allJawabanJurnal' => $allJawabanJurnal,
        ], 200);
    }
}
