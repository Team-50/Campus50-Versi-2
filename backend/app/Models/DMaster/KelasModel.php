<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class KelasModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'kelas';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'idkelas';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'idkelas', 'nkelas'
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

	//digunakan untuk menyimpan ke cache
	public static function toCache()
	{
		$kelas = KelasModel::all()->pluck('nkelas','idkelas'); 
		Cache::put('kelas', $kelas);
	}
	public static function getCache($idx=null)
	{
		if (!Cache::has('kelas'))
		{
			KelasModel::toCache();
		}

		if ($idx == null)
		{
			return Cache::get('kelas');
		}
		else
		{
			$kelas=Cache::get('kelas');
			
			return $kelas[$idx];
		}
	}
	//digunakan untuk menghapus cache
	public static function clear()
	{
		Cache::flush();
	}
}