<?php
/**
 * class ini digunakan untuk aktivitas mahasiswa (perkuliahan)
 */
namespace App\Models\Kemahasiswaan;

use Illuminate\Database\Eloquent\Model;

class PesertaAktivitasModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'pe3_peserta_aktivitas';
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
    'data_aktivitas_id',        
    'nim',        
    'nirm',        
    'jenis_anggota',
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