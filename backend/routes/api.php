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

	//perkuliahan	
	$router->post('/kemahasiswaan/daftarmhs',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|keuangan'],'uses'=>'Kemahasiswaan\DaftarMahasiswaController@index','as'=>'daftarmhs.index']);
	$router->get('/kemahasiswaan/daftarmhs/all',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|keuangan'],'uses'=>'Kemahasiswaan\DaftarMahasiswaController@all','as'=>'daftarmhs.all']);
	
	//kemahasiswaan	
	$router->post('/kemahasiswaan/daftarmhs',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|keuangan'],'uses'=>'Kemahasiswaan\DaftarMahasiswaController@index','as'=>'daftarmhs.index']);
	$router->get('/kemahasiswaan/daftarmhs/all',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|keuangan'],'uses'=>'Kemahasiswaan\DaftarMahasiswaController@all','as'=>'daftarmhs.all']);

	//akademik - perkuliahan - krs
	$router->post('/akademik/perkuliahan/krs',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|mahasiswa|dosenwali'],'uses'=>'Akademik\KRSController@index','as'=>'krs.index']);
	$router->post('/akademik/perkuliahan/krs/store',['middleware'=>['role:superadmin|akademik|programstudi|mahasiswa|dosenwali'],'uses'=>'Akademik\KRSController@store','as'=>'krs.store']);
	//digunakan untuk mendapatkan daftar matakuliah yang diselenggarakan dan belum terdaftar di krsnya mhs
	$router->post('/akademik/perkuliahan/krs/penyelenggaraan',['middleware'=>['role:superadmin|akademik|programstudi|mahasiswa|dosenwali'],'uses'=>'Akademik\KRSController@penyelenggaraan','as'=>'krs.penyelenggaraan']);
	$router->post('/akademik/perkuliahan/krs/storematkul',['middleware'=>['role:superadmin|akademik|programstudi|mahasiswa|dosenwali'],'uses'=>'Akademik\KRSController@storematkul','as'=>'krs.storematkul']);
	$router->get('/akademik/perkuliahan/krs/{id}',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|mahasiswa|dosenwali'],'uses'=>'Akademik\KRSController@show','as'=>'krs.show']);
	$router->put('/akademik/perkuliahan/krs/{id}/verifikasi',['middleware'=>['role:superadmin|dosenwali'],'uses'=>'Akademik\KRSController@verifikasi','as'=>'krs.verifikasi']);
	$router->post('/akademik/perkuliahan/krs/cekkrs',['middleware'=>['role:superadmin|akademik|programstudi|mahasiswa|dosenwali'],'uses'=>'Akademik\KRSController@cekkrs','as'=>'krs.cekkrs']);
	$router->put('/akademik/perkuliahan/krs/updatestatus/{id}',['middleware'=>['role:superadmin|akademik|programstudi|mahasiswa|dosenwali'],'uses'=>'Akademik\KRSController@updatestatus','as'=>'krs.updatestatus']);
	$router->delete('/akademik/perkuliahan/krs/{id}',['middleware'=>['role:superadmin|akademik|programstudi|mahasiswa|dosenwali'],'uses'=>'Akademik\KRSController@destroy','as'=>'krs.destroy']);
	$router->delete('/akademik/perkuliahan/krs/deletematkul/{id}',['middleware'=>['role:superadmin|akademik|programstudi|mahasiswa|dosenwali'],'uses'=>'Akademik\KRSController@destroymatkul','as'=>'krs.destroymatkul']);
	//id krs
	$router->get('/akademik/perkuliahan/krs/printpdf/{id}',['middleware'=>['role:superadmin|akademik|programstudi|mahasiswa|dosenwali|puslahta'],'uses'=>'Akademik\KRSController@printpdf','as'=>'krs.printpdf']);

	//akademik - perkuliahan - pkrs
	$router->post('/akademik/perkuliahan/pkrs',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|mahasiswa|dosenwali'],'uses'=>'Akademik\PKRSController@index','as'=>'pkrs.index']);
	$router->post('/akademik/perkuliahan/pkrs/store',['middleware'=>['role:superadmin|dosenwali'],'uses'=>'Akademik\PKRSController@store','as'=>'pkrs.store']);
	//digunakan untuk mendapatkan daftar matakuliah yang diselenggarakan dan belum terdaftar di krsnya mhs
	$router->post('/akademik/perkuliahan/pkrs/penyelenggaraan',['middleware'=>['role:superadmin||dosenwali'],'uses'=>'Akademik\PKRSController@penyelenggaraan','as'=>'pkrs.penyelenggaraan']);
	$router->post('/akademik/perkuliahan/pkrs/storematkul',['middleware'=>['role:superadmin||dosenwali'],'uses'=>'Akademik\PKRSController@storematkul','as'=>'pkrs.storematkul']);
	$router->get('/akademik/perkuliahan/pkrs/{id}',['middleware'=>['role:superadmin|dosenwali'],'uses'=>'Akademik\PKRSController@show','as'=>'pkrs.show']);    
	$router->put('/akademik/perkuliahan/pkrs/updatestatus/{id}',['middleware'=>['role:superadmin|dosenwali'],'uses'=>'Akademik\PKRSController@updatestatus','as'=>'pkrs.updatestatus']);    
	$router->delete('/akademik/perkuliahan/pkrs/deletematkul/{id}',['middleware'=>['role:superadmin|dosenwali'],'uses'=>'Akademik\PKRSController@destroymatkul','as'=>'pkrs.destroymatkul']);

	//akademik - perkuliahan - pembagian kelas
	$router->post('/akademik/perkuliahan/pembagiankelas',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|mahasiswa|dosen'],'uses'=>'Akademik\PembagianKelasController@index','as'=>'pembagiankelas.index']);
	$router->post('/akademik/perkuliahan/pembagiankelas/store',['middleware'=>['role:superadmin|akademik|programstudi'],'uses'=>'Akademik\PembagianKelasController@store','as'=>'pembagiankelas.store']);
	$router->post('/akademik/perkuliahan/pembagiankelas/pengampu',['middleware'=>['role:superadmin|akademik|programstudi'],'uses'=>'Akademik\PembagianKelasController@pengampu','as'=>'pembagiankelas.pengampu']);
	$router->get('/akademik/perkuliahan/pembagiankelas/matakuliah/{id}',['middleware'=>['role:superadmin|akademik|programstudi'],'uses'=>'Akademik\PembagianKelasController@matakuliah','as'=>'pembagiankelas.matakuliah']);
	$router->get('/akademik/perkuliahan/pembagiankelas/peserta/{id}',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|dosen'],'uses'=>'Akademik\PembagianKelasController@peserta','as'=>'pembagiankelas.peserta']);
	$router->post('/akademik/perkuliahan/pembagiankelas/storematakuliah',['middleware'=>['role:superadmin|akademik|programstudi'],'uses'=>'Akademik\PembagianKelasController@storematakuliah','as'=>'pembagiankelas.storematakuliah']);
	$router->post('/akademik/perkuliahan/pembagiankelas/storepeserta',['middleware'=>['role:superadmin|akademik|programstudi|mahasiswa'],'uses'=>'Akademik\PembagianKelasController@storepeserta','as'=>'pembagiankelas.storepeserta']);
	$router->get('/akademik/perkuliahan/pembagiankelas/{id}',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|dosen'],'uses'=>'Akademik\PembagianKelasController@show','as'=>'pembagiankelas.show']);
	//digunakan untuk mendapatkan daftar kelas berdasarkan penyelenggaraan id di sini adalah penyelenggaraan_id
	$router->get('/akademik/perkuliahan/pembagiankelas/{id}/penyelenggaraan',['middleware'=>['role:superadmin|akademik|programstudi|puslahta|dosen|mahasiswa'],'uses'=>'Akademik\PembagianKelasController@penyelenggaraan','as'=>'pembagiankelas.penyelenggaraan']);
	$router->put('/akademik/perkuliahan/pembagiankelas/{id}',['middleware'=>['role:superadmin|akademik|programstudi'],'uses'=>'Akademik\PembagianKelasController@update','as'=>'pembagiankelas.update']);
	$router->delete('/akademik/perkuliahan/pembagiankelas/{id}',['middleware'=>['role:superadmin|akademik|programstudi'],'uses'=>'Akademik\PembagianKelasController@destroy','as'=>'pembagiankelas.destroy']);
	$router->delete('/akademik/perkuliahan/pembagiankelas/deletematkul/{id}',['middleware'=>['role:superadmin|akademik|programstudi'],'uses'=>'Akademik\PembagianKelasController@destroymatkul','as'=>'pembagiankelas.destroymatkul']);
	$router->delete('/akademik/perkuliahan/pembagiankelas/deletepeserta/{id}',['middleware'=>['role:superadmin|akademik|programstudi'],'uses'=>'Akademik\PembagianKelasController@destroypeserta','as'=>'pembagiankelas.destroypeserta']);

	// store nilai maksudnya menyimpan komponen nilai
	$router->get('/akademik/perkuliahan/pembagiankelas/nilaikomponen/{id}',['uses'=>'Akademik\PembagianKelasController@nilaikomponen','as'=>'pembagiankelas.nilaikomponen']);
	$router->post('/akademik/perkuliahan/pembagiankelas/storenilai',['middleware'=>['role:dosen'],'uses'=>'Akademik\PembagianKelasController@storenilai','as'=>'pembagiankelas.storenilai']);

	//akademik - perkuliahan - nilai
	$router->post('/akademik/nilai/matakuliah',['middleware'=>['role:superadmin|akademik|puslahta'],'uses'=>'Akademik\NilaiMatakuliahController@index','as'=>'nilaimatakuliah.index']);
	//id disini adalah kelas_mhs_id
	$router->get('/akademik/nilai/matakuliah/pesertakelas/{id}',['middleware'=>['role:puslahta|dosen'],'uses'=>'Akademik\NilaiMatakuliahController@pesertakelas','as'=>'nilaimatakuliah.pesertakelas']);
	$router->post('/akademik/nilai/matakuliah/perkelas/storeperkelas',['middleware'=>['role:puslahta'],'uses'=>'Akademik\NilaiMatakuliahController@storeperkelas','as'=>'nilaimatakuliah.storeperkelas']);
	$router->post('/akademik/nilai/matakuliah/perdosen/storeperdosen',['middleware'=>['role:dosen'],'uses'=>'Akademik\NilaiMatakuliahController@storeperdosen','as'=>'nilaimatakuliah.storeperdosen']);
	$router->post('/akademik/nilai/matakuliah/perdosen/impornilai',['middleware'=>['role:dosen'],'uses'=>'Akademik\NilaiMatakuliahController@impornilai','as'=>'nilaimatakuliah.impornilai']);
	$router->get('/akademik/nilai/matakuliah/perkrs/{id}',['middleware'=>['role:puslahta'],'uses'=>'Akademik\NilaiMatakuliahController@perkrs','as'=>'nilaimatakuliah.perkrs']);
	$router->post('/akademik/nilai/matakuliah/perkrs/storeperkrs',['middleware'=>['role:puslahta'],'uses'=>'Akademik\NilaiMatakuliahController@storeperkrs','as'=>'nilaimatakuliah.storeperkrs']);
	//id disini adalah kelas_mhs_id	
	$router->post('/akademik/nilai/matakuliah/perdosen/printtemplatenilai/{id}',['middleware'=>['role:puslahta|dosen'],'uses'=>'Akademik\NilaiMatakuliahController@printtemplatenilai','as'=>'nilaimatakuliah.printtemplatenilai']);
	$router->post('/akademik/nilai/matakuliah/perdosen/printtoexcel1/{id}',['middleware'=>['role:puslahta|dosen'],'uses'=>'Akademik\NilaiMatakuliahController@printtoexcelperdosen1','as'=>'nilaimatakuliah.printtoexcelperdosen1']);

	//khs kartu hasil studi
	$router->post('/akademik/nilai/khs',['uses'=>'Akademik\NilaiKHSController@index','as'=>'khs.index']);
	$router->get('/akademik/nilai/khs/{id}',['uses'=>'Akademik\NilaiKHSController@show','as'=>'khs.show']);
	// id == krs id
	$router->get('/akademik/nilai/khs/printpdf/{id}',['uses'=>'Akademik\NilaiKHSController@printpdf','as'=>'khs.printpdf']);
	$router->post('/akademik/nilai/khs/printtoexcel1',['uses'=>'Akademik\NilaiKHSController@printtoexcel1','as'=>'khs.printtoexcel1']);

	//transkrip kurikulum
	$router->post('/akademik/nilai/transkripkurikulum',['uses'=>'Akademik\TranskripKurikulumController@index','as'=>'transkripkurikulum.index']);
	$router->get('/akademik/nilai/transkripkurikulum/{id}',['uses'=>'Akademik\TranskripKurikulumController@show','as'=>'transkripkurikulum.show']);
	$router->post('/akademik/nilai/transkripkurikulum/{id}/history',['uses'=>'Akademik\TranskripKurikulumController@history','as'=>'transkripkurikulum.history']);
	$router->get('/akademik/nilai/transkripkurikulum/printpdf1/{id}',['uses'=>'Akademik\TranskripKurikulumController@printpdf1','as'=>'transkripkurikulum.printpdf1']);
	$router->get('/akademik/nilai/transkripkurikulum/printpdf2/{id}',['uses'=>'Akademik\TranskripKurikulumController@printpdf2','as'=>'transkripkurikulum.printpdf2']);
	$router->post('/akademik/nilai/transkripkurikulum/printtoexcel1',['uses'=>'Akademik\TranskripKurikulumController@printtoexcel1','as'=>'transkripkurikulum.printtoexcel1']);

	
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