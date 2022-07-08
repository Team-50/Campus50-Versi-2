<?php

namespace App\Helpers;
use App\Models\System\ConfigurationModel;

class HelperAuth 
{
  /**
	 * daftar role
	 */
	private static $daftar_role=[
		'sa'=>'superadmin',
		'm'=>'manajemen',
    'pmb'=>'pmb',
    'k'=>'keuangan',
    'on'=>'operator nilai',
    'd'=>'dosen',
    'dw'=>'dosen wali',
    'mh'=>'mahasiswa',
    'mb'=>'mahasiswa baru',
    'al'=>'alumni',
    'ot'=>'orangtua wali',      
	];  
  //digunakan untuk mendapat nama role
  public static function getRealRoleName($role = null) {    
    if ($role == null)
    {
      return HelperAuth::$daftar_role;
    }
    else
    {
      return HelperAuth::$daftar_role[$role];
    }
  }
  /**
	* digunakan untuk membuat hash password
	* @return array
	*/
	public static function createHashPassword($password, $salt='', $new=true) {
		if ($new) {
			$salt = substr(md5(uniqid(rand(), true)), 0, 6);	
			$password = hash('sha256', $salt . hash('sha256', $password));
			$data =array('salt'=>$salt,'password'=>$password);			
		}else {
			$data = hash('sha256', $salt . hash('sha256', $password));	
			$data =array('salt'=>$salt,'password'=>$password);		
		}
		return $data;
	}
}