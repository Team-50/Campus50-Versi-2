<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{   
  /**
   * nama tabel model ini.
   *
   * @var string
   */
  protected $table = 'pe2_users';
  /**
   * primary key tabel ini.
   *
   * @var string
   */
  protected $primaryKey = 'user_id';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 
    'nomor_hp',
    'nomor_hp2',
    'foto',
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
