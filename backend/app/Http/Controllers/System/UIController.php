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
    $captcha_site_key = $config['captcha_public_key'];
    $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
    $semester_pendaftaran = 1;
    $identitas['nama_pt']=$config['nama_pt'];
    $identitas['nama_pt_alias']=$config['nama_pt_alias'];
    $identitas['bentuk_pt']='sekolahtinggi';
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
    $role = $this->getRoleName();
    switch ($role)
    {
      case 'sa':
        
      break;
    }
    // if (count($roles) > 0)
    // {
    //   if ($this->hasRole('superadmin'))
    //   {
    //     $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
    //             ->orderBy('tahun','asc')
    //             ->get();

    //     $daftar_fakultas=FakultasModel::all();
    //     $fakultas_id=$config['DEFAULT_FAKULTAS'];

    //     $daftar_prodi=ProgramStudiModel::all();
    //     $prodi_id=$config['DEFAULT_PRODI'];

    //     $tahun_pendaftaran = $config['DEFAULT_TAHUN_PENDAFTARAN'];
    //     $tahun_akademik = $config['DEFAULT_TA'];
    //   }
    //   elseif($this->hasRole('pmb'))
    //   {
    //     $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
    //             ->orderBy('tahun','asc')
    //             ->get();

    //     $userid=$this->getUserid();
    //     $daftar_prodi=$this->guard()->user()->prodi;

    //     if ($daftar_prodi->count()>0)
    //     {
    //       $daftar_fakultas=FakultasModel::select(\DB::raw('kode_fakultas,nama_fakultas'))
    //             ->whereExists(function ($query) use ($userid) {
    //               $query->select(\DB::raw(1))
    //                 ->from('usersprodi')
    //                 ->join('pe3_prodi','pe3_prodi.id','usersprodi.prodi_id')
    //                 ->where('user_id',$userid);
    //             })
    //             ->get();

    //       $fakultas_id=$config['DEFAULT_FAKULTAS'];
    //       $prodi_id=$daftar_prodi[0]->id;
    //     }
    //     else
    //     {
    //       $daftar_fakultas=FakultasModel::all();
    //       $fakultas_id=$config['DEFAULT_FAKULTAS'];

    //       $daftar_prodi=ProgramStudiModel::all();
    //       $prodi_id=$config['DEFAULT_PRODI'];
    //     }
    //     $tahun_pendaftaran = $config['DEFAULT_TAHUN_PENDAFTARAN'];
    //     $tahun_akademik = $config['DEFAULT_TA'];
    //   }
    //   elseif ($this->hasRole('puslahta'))
    //   {
    //     $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
    //             ->orderBy('tahun','asc')
    //             ->get();

    //     $userid=$this->getUserid();
    //     $daftar_prodi=$this->guard()->user()->prodi;

    //     if ($daftar_prodi->count()>0)
    //     {
    //       $daftar_fakultas=FakultasModel::select(\DB::raw('kode_fakultas,nama_fakultas'))
    //             ->whereExists(function ($query) use ($userid) {
    //               $query->select(\DB::raw(1))
    //                 ->from('usersprodi')
    //                 ->join('pe3_prodi','pe3_prodi.id','usersprodi.prodi_id')
    //                 ->where('user_id',$userid);
    //             })
    //             ->get();

    //       $fakultas_id=$config['DEFAULT_FAKULTAS'];
    //       $prodi_id=$daftar_prodi[0]->id;
    //     }
    //     else
    //     {
    //       $daftar_fakultas=FakultasModel::all();
    //       $fakultas_id=$config['DEFAULT_FAKULTAS'];

    //       $daftar_prodi=ProgramStudiModel::all();
    //       $prodi_id=$config['DEFAULT_PRODI'];
    //     }
    //     $tahun_pendaftaran = $config['DEFAULT_TAHUN_PENDAFTARAN'];
    //     $tahun_akademik = $config['DEFAULT_TA'];
    //   }
    //   elseif ($this->hasRole('keuangan'))
    //   {
    //     $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
    //             ->orderBy('tahun','asc')
    //             ->get();

    //     $userid=$this->getUserid();
    //     $daftar_prodi=$this->guard()->user()->prodi;

    //     if ($daftar_prodi->count()>0)
    //     {
    //       $daftar_fakultas=FakultasModel::select(\DB::raw('kode_fakultas,nama_fakultas'))
    //             ->whereExists(function ($query) use ($userid) {
    //               $query->select(\DB::raw(1))
    //                 ->from('usersprodi')
    //                 ->join('pe3_prodi','pe3_prodi.id','usersprodi.prodi_id')
    //                 ->where('user_id',$userid);
    //             })
    //             ->get();

    //       $fakultas_id=$config['DEFAULT_FAKULTAS'];
    //       $prodi_id=$daftar_prodi[0]->id;
    //     }
    //     else
    //     {
    //       $daftar_fakultas=FakultasModel::all();
    //       $fakultas_id=$config['DEFAULT_FAKULTAS'];

    //       $daftar_prodi=ProgramStudiModel::all();
    //       $prodi_id=$config['DEFAULT_PRODI'];
    //     }
    //     $tahun_pendaftaran = $config['DEFAULT_TAHUN_PENDAFTARAN'];
    //     $tahun_akademik = $config['DEFAULT_TA'];
    //   } 
    //   elseif ($this->hasRole('mahasiswa'))
    //   {
    //     $formulir=\App\Models\SPMB\FormulirPendaftaranModel::find($this->getUserid());

    //     $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
    //             ->where('tahun','>=',$formulir->ta)
    //             ->orderBy('tahun','asc')
    //             ->get();

    //     $daftar_fakultas=[];
    //     $fakultas_id=$config['DEFAULT_FAKULTAS'];

    //     $daftar_prodi=ProgramStudiModel::where('id',$formulir->kjur1)->get();
    //     $prodi_id=$formulir->kjur1;

    //     $tahun_pendaftaran = $formulir->ta;
    //     $tahun_akademik = $config['DEFAULT_TA'];
    //   }
    //   elseif ($this->hasRole('mahasiswabaru'))
    //   {
    //     $formulir=\App\Models\SPMB\FormulirPendaftaranModel::find($this->getUserid());
    //     $daftar_ta=TAModel::where('tahun','=',$formulir->ta)
    //               ->select(\DB::raw('tahun AS value,tahun_akademik AS text'))
    //               ->get();

    //     $daftar_fakultas=[];
    //     $fakultas_id=$config['DEFAULT_FAKULTAS'];

    //     $daftar_prodi=ProgramStudiModel::where('id',$formulir->kjur1)->get();
    //     $prodi_id=$formulir->kjur1;

    //     $tahun_pendaftaran = $formulir->ta;
    //     $tahun_akademik = $formulir->ta;
    //   }
    //   elseif ($this->hasRole(['akademik','programstudi']))
    //   {
    //     $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
    //             ->orderBy('tahun','asc')
    //             ->get();

    //     $userid=$this->getUserid();
    //     $daftar_prodi=$this->guard()->user()->prodi;

    //     $daftar_fakultas=FakultasModel::select(\DB::raw('kode_fakultas,nama_fakultas'))
    //           ->whereExists(function ($query) use ($userid) {
    //             $query->select(\DB::raw(1))
    //               ->from('usersprodi')
    //               ->join('pe3_prodi','pe3_prodi.id','usersprodi.prodi_id')
    //               ->where('user_id',$userid);
    //           })
    //           ->get();

    //     $fakultas_id=$config['DEFAULT_FAKULTAS'];
    //     $prodi_id=$daftar_prodi[0]->id;

    //     $tahun_pendaftaran = $config['DEFAULT_TAHUN_PENDAFTARAN'];
    //     $tahun_akademik = $config['DEFAULT_TA'];
    //   }
    //   elseif ($this->hasRole(['dosen','dosenwali']))
    //   {
    //     $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
    //             ->orderBy('tahun','asc')
    //             ->get();

    //     $daftar_fakultas=FakultasModel::all();
    //     $fakultas_id=$config['DEFAULT_FAKULTAS'];

    //     $daftar_prodi=ProgramStudiModel::all();
    //     $prodi_id=$config['DEFAULT_PRODI'];

    //     $tahun_pendaftaran = $config['DEFAULT_TAHUN_PENDAFTARAN'];
    //     $tahun_akademik = $config['DEFAULT_TA'];
    //   }
    //   $daftar_kelas=\App\Models\DMaster\KelasModel::select(\DB::raw('idkelas AS id,nkelas AS text'))
    //                         ->get();
    //   $idkelas='A';

    //   $daftar_status_mhs=StatusMahasiswaModel::select(\DB::raw('k_status AS id,n_status AS text'))
    //                       ->get();
    //   $k_status='A';
      return Response()->json([
                    'status'=>1,
                    'pid'=>'fetchdata',
                    'roles'=>$roles,
                    // 'daftar_ta'=>$daftar_ta,
                    // 'tahun_pendaftaran'=>$tahun_pendaftaran,
                    // 'tahun_akademik'=>$tahun_akademik,
                    // 'daftar_semester'=>$daftar_semester,
                    // 'semester_akademik' => $config['DEFAULT_SEMESTER'],
                    // 'daftar_fakultas'=>$daftar_fakultas,
                    // 'fakultas_id'=>$fakultas_id,
                    // 'daftar_prodi'=>$daftar_prodi,
                    // 'prodi_id'=>$prodi_id,
                    // 'daftar_kelas'=>$daftar_kelas,
                    // 'idkelas'=>$idkelas,
                    // 'daftar_status_mhs'=>$daftar_status_mhs,
                    // 'k_status'=>$k_status,
                    // 'theme'=>$theme,
                    'message'=>'Fetch data ui untuk admin berhasil diperoleh',
                  ],200)->setEncodingOptions(JSON_NUMERIC_CHECK);
    // }
    // else
    // {
    //   return Response()->json([
    //                 'status'=>0,
    //                 'pid'=>'fetchdata',
    //                 'message'=>'Fetch data ui gagal karena roles kosong.'
    //               ], 422);
    // }
  }
}
