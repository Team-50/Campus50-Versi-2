<?php
/**
 * class ini digunakan untuk aktivitas mahasiswa (perkuliahan)
 */
namespace App\Models\Kemahasiswaan;

use Illuminate\Database\Eloquent\Model;

class RegisterMahasiswaModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'register_mahasiswa';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'nim';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'nim',                        
    'nirm',
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
	public $timestamps = false;
}