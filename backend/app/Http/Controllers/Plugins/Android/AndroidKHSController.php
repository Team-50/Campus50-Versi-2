<?php

namespace App\Http\Controllers\Plugins\Android;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class AndroidKHSController extends Controller
{
	public function index(Request $request)
  {
    $nim = $request->query('nim', '0');
    $idsmt = $request->query('idsmt', 1);
    $ta = $request->query('ta', date('Y'));
    
    $daftar_khs = \DB::table('v_nilai_khs')
      ->where('nim', $nim)
      ->where('idsmt', $idsmt)
      ->where('tahun', $ta)
      ->get();

    return view('plugins/android/khs-index', [
      'daftar_khs'=>$daftar_khs,      
    ]);
  }
}