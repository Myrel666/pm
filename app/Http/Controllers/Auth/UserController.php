<?php

namespace App\Http\Controllers\Auth;

use File;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('user');
    }

    public function index()
    {
        $id = auth()->user()->id;

        $diproses = Pengajuan::where('user_id', auth()->user()->id)->where('status', 'diproses')->get();
        $disetujui = Pengajuan::where('user_id', auth()->user()->id)->where('status', 'disetujui')->get();
        $ditolak = Pengajuan::where('user_id', auth()->user()->id)->where('status', 'ditolak')->get();

        $count = count(DB::select("select * from presensi where tgl = CURDATE() AND user_id = $id AND jam_masuk != '00:00:00' "));
        $count1 = count(DB::select("select * from presensi where tgl = CURDATE() AND user_id = $id AND jam_pulang != '00:00:00' "));

        
        return view('auth.user.index', compact('disetujui', 'diproses', 'ditolak','count','count1'));
    }

    public function pengajuan()
    {
        $pengajuan = Pengajuan::all()->sortByDesc('created_at');
        return view('auth.user.pengajuan', compact('pengajuan'));
    }

    public function showPengajuan($id)
    {
        $data = Pengajuan::find($id);
        return response()->json($data);
    }

    public function addOrUpdatePengajuan(Request $request)
    {
        // dd($request->editBukti->extension());
        $data = [
            'user_id' => auth()->user()->id,
            'status' => 'diproses'
        ];

        if($request->has('editAlasan')){
            $data['alasan'] = $request->editAlasan;
            $data['keterangan'] = $request->editKeterangan;
            if($request->editBukti != null){
                $data['bukti'] = time().'_bukti.'.$request->editBukti->extension();
            }

            $validation['editAlasan'] = 'required';
            $validation['editKeterangan'] = 'required';
            $validation['editBukti'] = 'mimes:pdf,jpg,jpeg,png|max:1024';
        }else{
            $data['alasan'] = $request->alasan;
            $data['keterangan'] = $request->keterangan;
            $data['bukti'] = time().'_bukti.'.$request->bukti->extension();

            $validation['alasan'] = 'required';
            $validation['keterangan'] = 'required';
            $validation['bukti'] = 'required|mimes:pdf,jpg,jpeg,png|max:1024';
        }


        $request->validate($validation, [
            'required' => ':attribute harus diisi.',
            'mimes' => ':attribute harus berekstensi jpeg,jpg,png,pdf.',
            'max' => 'file :attribute maksimal berukuran 1MB.',
        ]);

        if($request->has('id')){
            $ajuan = Pengajuan::find($request->id);
            if(!empty($ajuan)){
                if($request->editBukti != null){
                    File::delete('uploads/pengajuan/'.$ajuan->bukti);
                    $request->editBukti->move(public_path('uploads/pengajuan'), $data['bukti']);
                }
            }
        }else{
            $request->bukti->move(public_path('uploads/pengajuan'), $data['bukti']);
        }
        
        Pengajuan::updateOrCreate([
            'id' => $request->id
        ],$data);
        
        return redirect()->back();
    }

    public function deletePengajuan($id)
    {
        $ajuan = Pengajuan::find($id);
        File::delete('uploads/pengajuan/'.$ajuan->bukti);
        $ajuan->delete();

        return redirect()->back();
    }

    public function time()
    {
        $date = Carbon::now();

        $date->settings(['formatFunction' => 'translatedFormat']);

        return $date->format('l, j F Y H:i:s');
    }

    public function absensi(Request $request)
    {
        $img = $request->image;
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = time() . '_absensi.png';
        $public_path = 'uploads/absensi/'.$fileName;

        $data = [
            'user_id' => auth()->user()->id,
        ];

        if($request->absen == 'pulang'){
            $data['bukti_pulang'] = $fileName;
            $data['jam_pulang'] = now()->format('H:i:s');
            $data['updated_at'] = now();
            $data['longitude_pulang'] = $request->lat;
            $data['latitude_pulang'] = $request->long;
        }else{
            $data['bukti_masuk'] = $fileName;
            $data['jam_masuk'] = now()->format('H:i:s');
            $data['created_at'] = now();
            $data['longitude_masuk'] = $request->lat;
            $data['latitude_masuk'] = $request->long;
        }

        $absenNow = Presensi::where('tgl', Carbon::now()->format('Y-m-d'))->first();
        if(!empty($absenNow)){
            if($request->absen == 'pulang'){
                File::delete('uploads/absensi/'.$absenNow->bukti_pulang);
            }else{
                File::delete('uploads/absensi/'.$absenNow->bukti_masuk);
            }   
        }

        if(file_put_contents($public_path, $image_base64)){
            Presensi::updateOrCreate([
                'tgl' => Carbon::now()->format('Y-m-d'),
                'user_id'=> auth()->user()->id
            ],$data);
            return redirect()->back();
        }else{
            echo "Unable to save the file.";
        }
    }

    public function logPresensi()
    {
        // dd(logPresensi);
        $presensi = Presensi::where('user_id', auth()->user()->id)->get();
        $pengajuan = Pengajuan::where('user_id', auth()->user()->id)->get();
        return view('auth.user.log', compact('presensi','pengajuan'));
    }
}