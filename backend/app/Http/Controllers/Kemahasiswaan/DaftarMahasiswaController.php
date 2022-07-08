<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Http\Controllers\Controller;

class DaftarMahasiswaController extends Controller {      
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function all()
  { 
    $daftar_mahasiswa = \DB::table('v_datamhs')->get();    

    return Response()->json([
      'status'=>'00',        
      'message'=>"data mahasiswa berhasil diperoleh",
      'daftar_mahasiswa'=>$daftar_mahasiswa
    ], 200); 
  }     
}