<?php

namespace App\Helpers;
use App\Models\System\ConfigurationModel;

class HelperAuth 
{
  public static function getRealRoleName($value) {
    $role = null;
    switch($value)
    {
      case 'sa':
        $role = 'superadmin';
      break;
      case 'm':
        $role = 'manajemen';
      break;
      case 'pmb':
        $role = 'pmb';
      break;
      case 'k':
        $role = 'keuangan';
      break;
      case 'on':
        $role = 'operator nilai';
      break;
      case 'd':
        $role = 'dosen';
      break;
      case 'dw':
        $role = 'dosoenwali';
      break;
      case 'm':
        $role = 'mahasiswa';
      break;
      case 'mb':
        $role = 'mahasiswabaru';
      break;
      case 'al':
        $role = 'alumni';
      break;
      case 'ot':
        $role = 'orangtuawali';
      break;      
    }
    return $role;
  }
}