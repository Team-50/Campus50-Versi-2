<?php
$router->get('/', function () use ($router) {
	return 'Campus50v2 API';
});

$router->group(['prefix'=>'v2'], function () use ($router)
{
	//auth login
	$router->post('/auth/login',['uses'=>'AuthController@login','as'=>'auth.login']);

	//untuk uifront
	$router->get('/system/setting/uifront',['uses'=>'System\UIController@frontend','as'=>'uifront.frontend']);
});

$router->group(['prefix'=>'v2', 'middleware'=>'auth:api'], function () use ($router)
{
	//authentication
	$router->post('/auth/logout',['uses'=>'AuthController@logout','as'=>'auth.logout']);
	$router->get('/auth/refresh',['uses'=>'AuthController@refresh','as'=>'auth.refresh']);
	$router->get('/auth/me',['uses'=>'AuthController@me','as'=>'auth.me']);

	//ui admin
	$router->get('/system/setting/uiadmin',['uses'=>'System\UIController@admin','as'=>'ui.admin']);

	//kemahasiswaan	
	$router->post('/kemahasiswaan/daftarmhs',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|keuangan'],'uses'=>'Kemahasiswaan\DaftarMahasiswaController@index','as'=>'daftarmhs.index']);
	$router->get('/kemahasiswaan/daftarmhs/all',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|keuangan'],'uses'=>'Kemahasiswaan\DaftarMahasiswaController@all','as'=>'daftarmhs.all']);

});

//payment - [bank riau kepri]
$router->group(['prefix'=>'h2h/iak', 'middleware'=>'auth:api'], function () use ($router)
{
	//inquiry tagihan
	$router->post('/inquiry-tagihan',['uses'=>'Plugins\H2H\IndoBestArthaKreasi\TransaksiController@inquiryTagihan','as'=>'iak.transaksi.inquiry-tagihan']);
	//payment
	$router->post('/payment',['uses'=>'Plugins\H2H\IndoBestArthaKreasi\TransaksiController@payment','as'=>'iak.transaksi.payment']);
});

//android - [gss]
$router->group(['prefix'=>'android'], function () use ($router)
{
	//khs mahasiswa
	$router->get('/khs',['uses'=>'Plugins\Android\AndroidKHSController@index','as'=>'android.khs.index']);
});