<?php

namespace App\Http\Controllers\Plugins\H2H\IndoBestArthaKreasi;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\System\ConfigurationModel;


class AuthController extends Controller
{
	/**
	 * Get a JWT via given credentials.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login(Request $request)
	{
		$this->validate($request, [            
			'username'=>'required',
			'password'=>'required',            
		]);
		$credentials = $request->only('username', 'password');
		$credentials['active']=1;

		if (! $token = $this->guard()->attempt($credentials, ['exp' => \Carbon\Carbon::now()->addDays(1)->timestamp])) {
			$result=[
				'status'=>'11',
				'message'=>'Username atau Password salah'
			];
			return response()->json([
														'Result' => $result
													], 200);
		}
		//log user loggin
		\App\Models\System\ActivityLog::log($request,[
																										'object' => $this->guard()->user(), 
																										'object_id'=>$this->getUserid(), 
																										'user_id' => $this->getUserid(), 
																										'message' => 'user '.$credentials['username'].' berhasil login'
																								]);

		ConfigurationModel::toCache();  
		return response()->json([
				'Result' => [
				'status'=>'00',
				'token'=>$token,
				'message'=>'Request berhasil'
			]
		], 200);   
	}
	/**
	 * Get the authenticated User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me()
	{
		$user = $this->guard()->user();  
		if (is_null($user))
		{
			$result=[
				'status'=>'98',
				'message'=>'Token tidak terdaftar'
			];
			return response()->json([
				'Result'=>$result
			]);
		}
		else
		{
			return response()->json($user->toArray());
		}         
	}
	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token)
	{
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => $this->guard()->factory()->getTTL() * 1440
		]);
	}
}