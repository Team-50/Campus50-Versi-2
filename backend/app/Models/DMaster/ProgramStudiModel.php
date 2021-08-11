<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class ProgramStudiModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'program_studi';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'kjur';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id', 
		'kode_epsbed', 
		'nama_ps', 
		'nama_ps_alias', 
		'kjenjang',
		'konsentrasi',
		'idkur',
		'iddosen',        
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

	public function getKAProdi($prodi_id)
	{
		$prodi=ProgramStudiModel::find($prodi_id);
		if (is_null($prodi))
		{
			return null;
		}
		else
		{
			$config=json_decode($prodi->config);            
			return $config->kaprodi;
		}        
	}
}