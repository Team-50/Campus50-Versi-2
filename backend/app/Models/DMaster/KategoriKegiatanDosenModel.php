<?php
/**
 * class ini digunakan untuk kegiatan dosen (perkuliahan)
 */
namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class KategoriKegiatanDosenModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'pe3_kategori_kegiatan_dosen';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'idkategori';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'idkategori',
		'kode_kategori',		
		'nama_kategori',		
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