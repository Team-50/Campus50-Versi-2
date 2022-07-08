<?php
/**
 * class ini digunakan untuk aktivitas mahasiswa (perkuliahan)
 */
namespace App\Models\Kemahasiswaan;

use Illuminate\Database\Eloquent\Model;

class DataAktivitasModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'pe3_data_aktivitas';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',                        
    'prodi_id',        
    'idsmt',        
    'tahun',        
    'tasmt',        
    'no_sk_tugas',        
    'tanggal_sk_tugas',        
    'jenis_aktivitas_id',        
    'jenis_anggota',
    'judul_aktivitas',        
    'keterangan',        
    'lokasi',   
	];
	/**
	 * enable auto_increment.
	 *
	 * @var string
	 */
	public $incrementing = false;
	/**
	 * activated timestamps.
	 *
	 * @var string
	 */
	public $timestamps = true;
}