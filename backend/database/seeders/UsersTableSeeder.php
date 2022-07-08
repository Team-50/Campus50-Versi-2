<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Helpers\HelperAuth;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$daftar_users = \DB::table('user')->select(\DB::raw('userid,page'))->get();
		foreach ($daftar_users as $k=>$v) {
			$user = User::find($v->userid);			
			$user->assignRole(HelperAuth::getRealRoleName($v->page));
		}		
	}
}
