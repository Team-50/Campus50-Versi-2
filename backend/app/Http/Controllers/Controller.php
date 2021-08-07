<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
	/**
	 * @return object auth api
	 */
	public function guard() 
	{
		return Auth::guard('api');
	}
	/**
	 * @return boolean roles of user in array
	 */
	public function getRoleName() 
	{
		$user = $this->guard()->user();
		switch($user->page)
		{
			case 'sa':
				return $user->page;
			break;
			default:
				return null;
		}
		
	}
}
