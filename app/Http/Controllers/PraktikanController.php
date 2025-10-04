<?php

namespace App\Http\Controllers;

use App\Models\Praktikan;
use App\Models\JawabanFitb;
use App\Models\JawabanJurnal;
use App\Models\JawabanMandiri;
use App\Models\JawabanTa;
use App\Models\JawabanTk;
use App\Models\Configuration;
use App\Models\LaporanPraktikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PraktikanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|unique:praktikans|unique:asistens|string',
            'nim'           => 'required|unique:praktikans|max:12|string',
            'password'      => 'required|min:6|string',
            'kelas_id'      => 'required|integer',
            'alamat'        => 'required|min:10|string',
            'nomor_telepon' => 'required|min:9|string',
            'email'         => 'required|email|unique:praktikans|string',
        ]);

        if(!Configuration::find(1)->registrationPraktikan_activation)
            return '{"message": "Registrasi untuk praktikan telah ditutup"}';

        Praktikan::create([
            'nama'          => $request->nama,
            'nim'           => $request->nim,
            'password'      => Hash::make($request->password),
            'kelas_id'      => $request->kelas_id,
            'alamat'        => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'email'         => $request->email,
        ]);

        return '{"message": "success"}';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Praktikan  $praktikan
     * @return \Illuminate\Http\Response
     */
    public function show(Praktikan $praktikan)
    {
        //
    }

    public function edit($praktikan_nim, $new_pass)
    {
        if(!Praktikan::where("nim", $praktikan_nim)->exists()) {

            return response()->json([
                'message' => 'Praktikan ini tidak ada dalam database',
            ], 200);
        }

        $currentPraktikan = Praktikan::where("nim", $praktikan_nim)->first();
        $currentPraktikan->password = Hash::make($new_pass);
        $currentPraktikan->save();

        return response()->json([
            'message' => 'success',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Praktikan  $praktikan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Praktikan $praktikan)
    {
        //
    }

    public function resetPass(Request $request){
        $user = Auth::guard('praktikan')->user();
        $request->validate([
            'password' => 'required|min:6',
            'repeatpass' => 'required|min:6',
        ]);
        if(!strcmp($request->password, $request->repeatpass)){
            $praktikan = Praktikan::where('id',$user->id)->update([
                'password'=>Hash::make($request->password),
            ]);
            return response()->json([
                'message'=> 'success',
                'password' => 'Password berhasil diperbarui',
            ], 200);
        }else{
            return response()->json([
                'message'=> 'repeat password tidak sesuai',
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        foreach (explode("-", $request->allpraktikan_nim) as $nim => $value) {
                
            JawabanFitb::where('praktikan_id', Praktikan::where('nim', $value)->first()->id)
                    ->where('modul_id', $request->modul_id)
                    ->delete();
            JawabanJurnal::where('praktikan_id', Praktikan::where('nim', $value)->first()->id)
                    ->where('modul_id', $request->modul_id)
                    ->delete();
            JawabanMandiri::where('praktikan_id', Praktikan::where('nim', $value)->first()->id)
                    ->where('modul_id', $request->modul_id)
                    ->delete();
            JawabanTa::where('praktikan_id', Praktikan::where('nim', $value)->first()->id)
                    ->where('modul_id', $request->modul_id)
                    ->delete(); 
            JawabanTk::where('praktikan_id', Praktikan::where('nim', $value)->first()->id)
                    ->where('modul_id', $request->modul_id)
                    ->delete();
            LaporanPraktikan::where('praktikan_id', Praktikan::where('nim', $value)->first()->id)
                    ->where('modul_id', $request->modul_id)
                    ->delete();
        }
        
        return '{"message": "success"}';
    }
}
