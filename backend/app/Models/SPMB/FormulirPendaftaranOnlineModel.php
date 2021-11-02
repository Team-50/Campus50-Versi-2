<?php

namespace App\Models\SPMB;

use Illuminate\Database\Eloquent\Model;

class FormulirPendaftaranOnlineModel extends Model {    
	/**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'formulir_pendaftaran_temp';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'no_pendaftaran';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [        
		'no_pendaftaran',                      
		'no_formulir',    
		'nama_mhs',
		'tempat_lahir',
		'tanggal_lahir',
		'jk',
		'email',
		'telp_hp',
		'kjur1',
		'kjur2',
		'idkelas',				
		'ta',
		'idsmt',		
		'salt',		
		'userpassword',		
		'waktu_mendaftar',
		'file_bukti_bayar',
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