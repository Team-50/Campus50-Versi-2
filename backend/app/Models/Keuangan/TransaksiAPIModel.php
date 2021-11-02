<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class TransaksiAPIModel extends Model {    
	/**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'transaksi_api';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'no_transaksi';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [        
		'no_transaksi',                      
		'no_faktur',    
		'kjur',
		'tahun',
		'idsmt',
		'idkelas',
		'no_formulir',
		'nim',
		'commited',
		'tanggal',
		'userid',
		'total',		
		'date_added',
		'date_modified',
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

	const CREATED_AT = 'date_added';

	const UPDATED_AT = 'date_modified';
	
	public function detail ()
	{
		return $this->hasMany('\App\Models\Keuangan\TransaksiDetailModel','transaksi_id','id');
	}
}