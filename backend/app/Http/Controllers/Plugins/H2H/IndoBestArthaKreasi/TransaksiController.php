<?php

namespace App\Http\Controllers\Plugins\H2H\IndoBestArthaKreasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Helpers\HelperAkademik;
use App\Models\SPMB\FormulirPendaftaranOnlineModel;
use App\Models\DMaster\ProgramStudiModel;
use App\Models\Akademik\DulangModel;

use App\Helpers\HelperKeuangan;

use Exception;

class TransaksiController extends Controller {  
	public function inquiryTagihan(Request $request)
	{
		$this->validate($request, [
			'kode_billing'=>'required'
		]);
		
		$kode_billing = $request->input('kode_billing');
		
		$tipe_transaksi=substr($kode_billing, 0,2);

		switch ($tipe_transaksi)
		{
			case 10: //bayar biasa
				$data = \DB::table('transaksi AS t')
					->select(\DB::raw('
						t.no_transaksi,
						t.no_faktur,
						t.kjur,
						t.tahun,
						t.idsmt,
						t.idkelas,
						k.nkelas,
						t.no_formulir,
						fp.nama_mhs,
						t.nim,
						t.disc,
						t.commited,
						t.tanggal,
						t.date_added
					'))
					->leftJoin('formulir_pendaftaran AS fp', 'fp.no_formulir', 't.no_formulir')
					->leftJoin('kelas AS k', 'k.idkelas', 't.idkelas')
					->where('t.no_transaksi', $kode_billing)
					->first();
				
				if (is_null($data))        {
					return Response()->json([
						'status'=>'14',        
						'message'=>"request KODE_BILLING ($kode_billing) tidak sesuai"											
					], 200); 
				}
				else if ($data->commited==1)
				{
					return Response()->json([
						'status'=>'88',        
						'message'=>"Tagihan sudah dibayarkan."
						
					], 200); 
				}
				else
				{
					$payload['kode_billing'] = $data->no_transaksi;
					$payload['no_formulir'] = $data->no_formulir;
					if ($data->nama_mhs == '' || $data->nim == '' || $data->kjur == 0)
					{
						$mahasiswa = FormulirPendaftaranOnlineModel::find($data->no_formulir);				
						$payload['nama_mhs'] = is_null($mahasiswa) ? '' : $mahasiswa->nama_mhs;
						$payload['keterangan'] = 'MAHASISWA BARU';
					}
					else
					{
						$payload['nama_mhs'] = $data->nama_mhs;
						$payload['keterangan'] = 'MAHASISWA LAMA';
					}
					$payload['kode_billing'] = $data->no_transaksi;
					$payload['no_faktur']=$data->no_faktur;
					$payload['kjur']=$data->kjur;
					$payload['tahun']=$data->tahun;
					$payload['idsmt']=$data->idsmt;						
					$payload['idkelas']=$data->idkelas;	
					$payload['nama_prodi']=ProgramStudiModel::find($payload['kjur'])->value('nama_ps');
					$payload['semester']=HelperAkademik::getSemester($payload['idsmt']);	
					$payload['nama_kelas']=$data->nkelas;
					$payload['totaltagihan']=\DB::table('transaksi_detail')
						->where('no_transaksi', $data->no_transaksi)
						->sum('dibayarkan');

					$payload['commited']=$data->commited;

					return response()->json([						
						'status'=>'00',
						'message'=>'Request Data Berhasil',
						'data'=>$payload,						
					], 200); 			

				}			
			break;
			case 11: //bayar cuti
				$data = \DB::table('transaksi_cuti AS t')
					->select(\DB::raw('
						t.no_transaksi,
						t.no_faktur,
						t.tahun,
						t.idsmt,
						vdm.no_formulir,
						t.nim,
						vdm.nama_mhs,
						vdm.kjur,
						vdm.nama_ps,
						vdm.idkelas,
						k.nkelas AS nama_kelas,
						t.dibayarkan AS totaltagihan,
						t.commited,
						t.tanggal,
						t.date_added
					'))
				->join('v_datamhs AS vdm', 'vdm.nim', 't.no_formulir')
				->join('kelas AS k', 'k.idkelas', 'vdm.idkelas')
				->where('t.no_transaksi', $kode_billing)
				->first();
			
				if (is_null($data))        {
					return Response()->json([
						'status'=>'14',        
						'message'=>"request KODE_BILLING ($kode_billing) tidak sesuai"												
					], 200); 
				}
				else if ($data->commited==1)
				{
					return Response()->json([
						'status'=>'88',        
						'message'=>"Tagihan sudah dibayarkan."											
					], 200); 
				}
				else
				{
					$payload['kode_billing'] = $data->no_transaksi;
					$payload['no_faktur']=$data->no_faktur;
					$payload['no_formulir'] = $data->no_formulir;
					$payload['nim'] = $data->nim;
					$payload['nama_mhs'] = $data->nama_mhs;					
					$payload['nama_ps']=$data->nama_ps;
					$payload['kjur']=$data->kjur;
					$payload['tahun']=$data->tahun;
					$payload['idsmt']=$data->idsmt;						
					$payload['idkelas']=$data->idkelas;	
					$payload['nama_kelas']=$data->nama_kelas;						
					$payload['semester']=HelperAkademik::getSemester($payload['idsmt']);
					$payload['nama_prodi']=ProgramStudiModel::find($payload['kjur'])->value('nama_ps');
					$payload['keterangan']='CUTI';
					$payload['totaltagihan']=$data->totaltagihan;						
					$payload['commited']=$data->commited;	
					$payload['tanggal']=$data->tanggal;	
					$payload['date_added']=$data->date_added;	
				}
				return response()->json([					
					'status'=>'00',
					'message'=>'Request Data Berhasil',
					'data'=>$payload,				
				], 200);
			break;
			default:
				return response()->json([					
					'status'=>30,
					'message'=>'Proses Login telah berhasil, namun ada error yaitu tipe_transaksi tidak dikenal.',						
				], 200);
		}
	}
	public function payment(Request $request)
	{
		$this->validate($request, [
			'kode_billing'=>'required',
			'no_ref'=>'required',
		]);

		$kode_billing = $request->input('kode_billing');		

		$tipe_transaksi=substr($kode_billing, 0,2);

		$userid=$this->getUserid();

		switch ($tipe_transaksi)
		{
			case 10: //bayar biasa
				$data = \DB::table('transaksi AS t')
					->select(\DB::raw('
						t.no_transaksi,
						t.kjur,
						t.no_formulir,
						fp.nama_mhs,
						t.nim,
						t.tahun,
						t.idsmt,
						t.idkelas,
						rm.k_status,
						rm.perpanjang,
						fp.ta AS tahun_masuk,
						fp.idsmt AS semester_masuk,
						t.commited
					'))
					->leftJoin('formulir_pendaftaran AS fp', 'fp.no_formulir', 't.no_formulir')
					->leftJoin('register_mahasiswa AS rm', 't.no_formulir', 'rm.no_formulir')
					->where('t.no_transaksi', $kode_billing)
					->first();
			
				if (is_null($data))        {
					return Response()->json([
						'status'=>'14',        
						'message'=>"request KODE_BILLING ($kode_billing) tidak sesuai"												
					], 200); 
				}
				else if ($data->commited == 1)
				{
					return Response()->json([
						'status'=>'88',        
						'message'=>"Tagihan dengan KODE_BILLING ($kode_billing) sudah dibayarkan."											
					], 200); 
				}
				else
				{
					$result = \DB::transaction(function () use ($request, $data) {
						$no_transaksi = $data->no_transaksi;
						$no_ref = $request->input('no_ref');
						$userid = $this->getUserid();
						$total_tagihan = \DB::table('transaksi_detail')
							->where('no_transaksi', $no_transaksi)
							->sum('dibayarkan');

						\DB::table('transaksi')
							->where('no_transaksi', $no_transaksi)
							->update([
								'commited'=> 1
							]);

						if ($data->nama_mhs == '' && $data->nim == '' && $data->kjur == 0) //biaya pendaftaran
						{
							$sql = "INSERT INTO transaksi_api (
								no_transaksi,
								no_faktur,
								kjur,
								tahun,
								idsmt,
								idkelas,
								no_formulir,
								nim,
								commited,
								tanggal,
								userid,
								total,
								date_added,
								date_modified
							)
							SELECT 
								no_transaksi,
								no_faktur,
								kjur,
								tahun,
								idsmt,
								idkelas,
								no_formulir,
								nim,
								commited,
								tanggal,
								$userid,
								$total_tagihan,
								NOW(),
								NOW() 
							FROM transaksi 
								WHERE no_transaksi='$no_transaksi'";

							\DB::statement($sql);
						}
						elseif ($data->nim == '') //pembayaran mahasiswa baru
						{
							$sql = "INSERT INTO transaksi_api (
								no_transaksi,
								no_faktur,
								kjur,
								tahun,
								idsmt,
								idkelas,
								no_formulir,
								nim,
								commited,
								tanggal,
								userid,
								total,
								date_added,
								date_modified
							) 
							SELECT 
								no_transaksi,
								no_faktur,
								kjur,
								tahun,
								idsmt,
								idkelas,
								no_formulir,
								nim,
								commited,
								tanggal,
								$userid,
								$total_tagihan,
								NOW(),
								NOW() 
							FROM transaksi 
							WHERE no_transaksi='$no_transaksi'";

							\DB::statement($sql);

						}
						else
						{
							$datadulang = \DB::table('dulang')
								->select(\DB::raw('
									iddulang,
									nim,
									tahun,
									idsmt,
									tanggal,
									idkelas,
									status_sebelumnya,
									k_status
								'))
							 	->where('nim', $data->nim)
								->where('idsmt', $data->idsmt)
								->where('tahun', $data->tahun)
								->first();

							if (is_null($datadulang))
							{
								$h_keuangan = new HelperKeuangan();
								$h_keuangan->setDataMHS([
									'no_formulir'=>$data->no_formulir,
									'nim'=>$data->nim,
									'kjur'=>$data->kjur,
									'ta'=>$data->tahun,
									'idsmt'=>$data->idsmt,
									'tahun_masuk'=>$data->tahun_masuk,
									'semester_masuk'=>$data->semester_masuk,
									'idkelas'=>$data->idkelas,
									'k_status'=>$data->k_status,									
								]);
								$datadulang = $h_keuangan->getDataDulang($data->idsmt, $data->tahun);
								
								if (is_null($datadulang))
								{
									$bool = $h_keuangan->getTresholdPembayaran($data->tahun, $data->idsmt);
									if ($bool)
									{
										$tasmt=$data->tahun.$data->idsmt;
										DulangModel::create([
											'nim'=>$data->nim,
											'tahun'=>$data->tahun,
											'idsmt'=>$data->idsmt,
											'tasmt'=>$tasmt,
											'tanggal'=>\Carbon\Carbon::now(),
											'idkelas'=>$data->idkelas,
											'k_status'=>'A',
											'status_sebelumnya'=>'A',
										]);
										
										\DB::table('register_mahasiswa')
											->where('nim', $data->nim)
											->update([
												'k_status'=>'A'
											]);
									}
								}
								$sql = "INSERT INTO transaksi_api (
									no_transaksi,
									no_faktur,
									kjur,
									tahun,
									idsmt,
									idkelas,
									no_formulir,
									nim,
									commited,
									tanggal,
									userid,
									total,
									date_added,
									date_modified
								) 
								SELECT 
									no_transaksi,
									no_faktur,
									kjur,
									tahun,
									idsmt,
									idkelas,
									no_formulir,
									nim,
									commited,
									tanggal,
									$userid,
									$total_tagihan,
									NOW(),
									NOW() 
								FROM transaksi 
									WHERE no_transaksi='$no_transaksi'";
								
								\DB::statement($sql);
							}
							return 	[
								'status'=>'00',
								'kode_billing'=>$data->no_transaksi,
								'message'=>'Pembayaran Berhasil',
								'noref'=>$no_ref,
							];
						}
					});
					return response()->json($result, 200);
				}
			break;
			case 12: //bayar cuti

			break;
			default:
				return response()->json([					
					'status'=>30,
					'message'=>'Proses Login telah berhasil, namun ada error yaitu tipe_transaksi tidak dikenal.',											
				], 200);
		}
	}
}
