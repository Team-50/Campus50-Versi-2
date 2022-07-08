<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;

class DulangModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'dulang';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'iddulang';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'iddulang',
		'nim',
		'tahun',
		'idsmt',
		'tasmt',
		'tanggal',
		'idkelas',
		'status_sebelumnya',
		'k_status',
	];
	/**
	 * enable auto_increment.
	 *
	 * @var string
	 */
	public $incrementing = true;
	/**
	 * activated timestamps.
	 *
	 * @var string
	 */
	public $timestamps = false;
}