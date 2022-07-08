<?php

namespace App\Http\Controllers\Plugins\Android;

use Illuminate\Http\Request;
use App\Models\System\ConfigurationModel;

use App\Http\Controllers\Controller;

class AndroidKHSController extends Controller
{
	public function index(Request $request)
  {
    $config = ConfigurationModel::getCache();
    
    $nim = $request->query('nim', 0);
    $idsmt = $request->query('idsmt', $config['default_semester']);
    $ta = $request->query('ta', $config['default_ta']);
    
    $daftar_khs = \DB::table('v_nilai_khs')
      ->where('nim', $nim)
      ->where('idsmt', $idsmt)
      ->where('tahun', $ta)
      ->get();

    return view('plugins/android/khs-index', [
      'daftar_khs'=>$daftar_khs,
      'nim'=>$nim,
      'idsmt'=>$idsmt,
      'tahun'=>$ta,
    ]);
  }
}