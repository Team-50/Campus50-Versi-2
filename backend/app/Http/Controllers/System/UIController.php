<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\System\ConfigurationModel;
use App\Models\DMaster\TAModel;
use App\Models\DMaster\FakultasModel;
use App\Models\DMaster\ProgramStudiModel;
use App\Models\DMaster\StatusMahasiswaModel;


class UIController extends Controller {
  /**
   * digunakan untuk mendapatkan setting variabel ui frontend
   */
  public function frontend ()
  {
    $config = ConfigurationModel::getCache();        
    $captcha_site_key = $config['CAPTCHA_SITE_KEY'];
    $tahun_pendaftaran = $config['DEFAULT_TAHUN_PENDAFTARAN'];
    $semester_pendaftaran = $config['DEFAULT_SEMESTER_PENDAFTARAN'];
    $identitas['nama_pt']=$config['NAMA_PT'];
    $identitas['nama_pt_alias']=$config['NAMA_PT_ALIAS'];
    $identitas['bentuk_pt']=$config['BENTUK_PT'];
    return Response()->json([
                  'status'=>1,
                  'pid'=>'fetchdata',
                  'captcha_site_key'=>$captcha_site_key,
                  'tahun_pendaftaran'=>$tahun_pendaftaran,
                  'semester_pendaftaran'=>$semester_pendaftaran,
                  'identitas'=>$identitas,
                  'message'=>'Fetch data ui untuk front berhasil diperoleh'
                ], 200);
  }
  /**
   * digunakan untuk mendapatkan setting variabel ui admin
   */
  public function admin ()
  {
    $config = ConfigurationModel::getCache();    
    $daftar_semester=[
              0=>[
                'id'=>1,
                'text'=>'GANJIL'
              ],
              1=>[
                'id'=>2,
                'text'=>'GENAP'
              ],
              2=>[
                'id'=>3,
                'text'=>'PENDEK'
              ]
            ];
    $roles = $this->getRoleName();

    $config = ConfigurationModel::getCache();
    $theme = 'default';
    $daftar_semester=[
      0=>[
        'id'=>1,
        'text'=>'GANJIL'
      ],
      1=>[
        'id'=>2,
        'text'=>'GENAP'
      ],
      2=>[
        'id'=>3,
        'text'=>'PENDEK'
      ]
    ];

    $daftar_kelas=\App\Models\DMaster\KelasModel::select(\DB::raw('idkelas AS id,nkelas AS text'))
    ->get();
    $idkelas='A';

    $daftar_status_mhs=StatusMahasiswaModel::select(\DB::raw('k_status AS id,n_status AS text'))
      ->get();
    $k_status='A';
    
    $role = $this->getRoleName();
    switch ($role)
    {
      case 'sa':
        $role_name = 'superadmin';
        $daftar_prodi = ProgramStudiModel::select(\DB::raw('
          program_studi.kjur,
          program_studi.kode_epsbed,
          program_studi.nama_ps,
          program_studi.kjenjang,
          jenjang_studi.njenjang,
          program_studi.konsentrasi
        '))
        ->join('jenjang_studi', 'jenjang_studi.kjenjang','program_studi.kjenjang')
        ->where('program_studi.kjur', '>', 0)
        ->get();
        
        $prodi_id = $config['default_kjur'];

        $daftar_ta = TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
          ->orderBy('tahun','asc')
          ->get();
        
        $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
        $tahun_akademik = $config['default_ta'];      

      break;
      default:        
        $role_name = 'undefined';
        $daftar_prodi = [];
        $prodi_id = null;
        $daftar_ta = [];
        $tahun_pendaftaran = null;
        $tahun_akademik = null;
        $daftar_kelas = [];
    }    
    return Response()->json([
      'status'=>1,
      'pid'=>'fetchdata',
      'role'=>$role,
      'role_name'=>$role_name,
      'daftar_ta'=>$daftar_ta,
      'tahun_pendaftaran'=>$tahun_pendaftaran,
      'tahun_akademik'=>$tahun_akademik,
      'daftar_semester'=>$daftar_semester,
      'semester_akademik' => $config['default_semester'],                    
      'daftar_prodi'=>$daftar_prodi,
      'prodi_id'=>$prodi_id,
      'daftar_kelas'=>$daftar_kelas,
      'idkelas'=>$idkelas,
      'daftar_status_mhs'=>$daftar_status_mhs,
      'k_status'=>$k_status,
      'theme'=>$theme,
      'message'=>'Fetch data ui untuk admin berhasil diperoleh',
    ], 200)->setEncodingOptions(JSON_NUMERIC_CHECK);
  }
}