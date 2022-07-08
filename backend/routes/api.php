<?php
$router->get('/', function () use ($router) {
	return 'PortalEKampus API';
});

$router->group(['prefix'=>'v2'], function () use ($router)
{
	//auth login
	$router->post('/auth/login',['uses'=>'AuthController@login','as'=>'auth.login']);

	//untuk uifront
	$router->get('/system/setting/uifront',['uses'=>'System\UIController@frontend','as'=>'uifront.frontend']);
	
	//setting - cron
	$router->get('/system/cron/run',['uses'=>'System\CronController@run','as'=>'cron.run']);

	//perkuliahan - jadwal kuliah
	$router->post('/perkuliahan/jadwalkuliah',['uses'=>'Perkuliahan\JadwalKuliahController@index','as'=>'perkuliahan-jadwalkuliah.index']);				
	$router->get('/perkuliahan/jadwalkuliah/{id}/peserta',['uses'=>'Perkuliahan\JadwalKuliahController@peserta','as'=>'perkuliahan-jadwalkuliah.peserta']);				
	
});

$router->group(['prefix'=>'v2', 'middleware'=>'auth:api'], function () use ($router)
{
	//authentication
	$router->post('/auth/logout',['uses'=>'AuthController@logout','as'=>'auth.logout']);
	$router->get('/auth/refresh',['uses'=>'AuthController@refresh','as'=>'auth.refresh']);
	$router->get('/auth/me',['uses'=>'AuthController@me','as'=>'auth.me']);

	//ui admin
	$router->get('/system/setting/uiadmin',['uses'=>'System\UIController@admin','as'=>'ui.admin']);

	//dmaster - dosen - kategori kegiatan
	//kemahasiswaan - jenis aktivitas
	$router->get('/dmaster/dosen/kategorikegiatan',['middleware'=>['role:superadmin|manajemen|programstudi|dosen'],'uses'=>'DMaster\KategoriKegiatanDosenController@index','as'=>'dmaster-dosen-kategorikegiatan.index']);				
	$router->post('/dmaster/dosen/kategorikegiatan/store',['middleware'=>['role:superadmin|manajemen'],'uses'=>'DMaster\KategoriKegiatanDosenController@store','as'=>'dmaster-dosen-kategorikegiatan.store']);
	$router->get('/dmaster/dosen/kategorikegiatan/{id}',['middleware'=>['role:superadmin|manajemen|'],'uses'=>'DMaster\KategoriKegiatanDosenController@show','as'=>'dmaster-dosen-kategorikegiatan.show']);
	$router->put('/dmaster/dosen/kategorikegiatan/{id}',['middleware'=>['role:superadmin|manajemen|'],'uses'=>'DMaster\KategoriKegiatanDosenController@update','as'=>'dmaster-dosen-kategorikegiatan.update']);
	$router->delete('/dmaster/dosen/kategorikegiatan/{id}',['middleware'=>['role:superadmin|manajemen|'],'uses'=>'DMaster\KategoriKegiatanDosenController@destroy','as'=>'dmaster-dosen-kategorikegiatan.destroy']);
	
	//kemahasiswaan	
	$router->post('/kemahasiswaan/daftarmhs',['middleware'=>['role:superadmin|manajemen|operator nilai|keuangan'],'uses'=>'Kemahasiswaan\DaftarMahasiswaController@index','as'=>'daftarmhs.index']);
	$router->get('/kemahasiswaan/daftarmhs/all',['middleware'=>['role:superadmin|manajemen|operator nilai|keuangan'],'uses'=>'Kemahasiswaan\DaftarMahasiswaController@all','as'=>'daftarmhs.all']);
	
	//kemahasiswaan - jenis aktivitas
	$router->get('/kemahasiswaan/jenisaktivitas',['middleware'=>['role:superadmin|manajemen|programstudi|dosen'],'uses'=>'Kemahasiswaan\JenisAktivitasController@index','as'=>'kemahasiswaan-jenisaktivitas.index']);				
	$router->post('/kemahasiswaan/jenisaktivitas/store',['middleware'=>['role:superadmin|manajemen|programstudi|'],'uses'=>'Kemahasiswaan\JenisAktivitasController@store','as'=>'kemahasiswaan-jenisaktivitas.store']);
	$router->get('/kemahasiswaan/jenisaktivitas/{id}',['middleware'=>['role:superadmin|manajemen|programstudi|'],'uses'=>'Kemahasiswaan\JenisAktivitasController@show','as'=>'kemahasiswaan-jenisaktivitas.show']);
	$router->put('/kemahasiswaan/jenisaktivitas/{id}',['middleware'=>['role:superadmin|manajemen|programstudi|'],'uses'=>'Kemahasiswaan\JenisAktivitasController@update','as'=>'kemahasiswaan-jenisaktivitas.update']);
	$router->delete('/kemahasiswaan/jenisaktivitas/{id}',['middleware'=>['role:superadmin|manajemen|programstudi|'],'uses'=>'Kemahasiswaan\JenisAktivitasController@destroy','as'=>'kemahasiswaan-jenisaktivitas.destroy']);
	
	//kemahasiswaan - data aktivitas
	$router->get('/kemahasiswaan/dataaktivitas',['middleware'=>['role:superadmin|manajemen|programstudi|dosen'],'uses'=>'Kemahasiswaan\DataAktivitasController@index','as'=>'kemahasiswaan-dataaktivitas.index']);				
	$router->post('/kemahasiswaan/dataaktivitas/store',['middleware'=>['role:superadmin|manajemen|programstudi|dosen|'],'uses'=>'Kemahasiswaan\DataAktivitasController@store','as'=>'kemahasiswaan-dataaktivitas.store']);
	$router->post('/kemahasiswaan/dataaktivitas/storepeserta',['middleware'=>['role:superadmin|manajemen|programstudi|dosen|'],'uses'=>'Kemahasiswaan\DataAktivitasController@storepeserta','as'=>'kemahasiswaan-dataaktivitas.storepeserta']);
	$router->get('/kemahasiswaan/dataaktivitas/{id}',['middleware'=>['role:superadmin|manajemen|programstudi|dosen|'],'uses'=>'Kemahasiswaan\DataAktivitasController@show','as'=>'kemahasiswaan-dataaktivitas.show']);
	$router->put('/kemahasiswaan/dataaktivitas/{id}',['middleware'=>['role:superadmin|manajemen|programstudi|dosen'],'uses'=>'Kemahasiswaan\DataAktivitasController@update','as'=>'kemahasiswaan-dataaktivitas.update']);
	$router->delete('/kemahasiswaan/dataaktivitas/{id}',['middleware'=>['role:superadmin|manajemen|programstudi|dosen'],'uses'=>'Kemahasiswaan\DataAktivitasController@destroy','as'=>'kemahasiswaan-dataaktivitas.destroy']);
	$router->delete('/kemahasiswaan/dataaktivitas/{id}/deletepeserta',['middleware'=>['role:superadmin|manajemen|programstudi|dosen'],'uses'=>'Kemahasiswaan\DataAktivitasController@destroypeserta','as'=>'kemahasiswaan-dataaktivitas.destroypeserta']);
	$router->get('/kemahasiswaan/dataaktivitas/{id}/peserta',['middleware'=>['role:superadmin|manajemen|programstudi|dosen|'],'uses'=>'Kemahasiswaan\DataAktivitasController@peserta','as'=>'kemahasiswaan-dataaktivitas.peserta']);
	$router->get('/kemahasiswaan/dataaktivitas/{id}/penguji',['middleware'=>['role:superadmin|manajemen|programstudi|dosen|'],'uses'=>'Kemahasiswaan\DataAktivitasController@penguji','as'=>'kemahasiswaan-dataaktivitas.penguji']);
	$router->get('/kemahasiswaan/dataaktivitas/{id}/pembimbing',['middleware'=>['role:superadmin|manajemen|programstudi|dosen|'],'uses'=>'Kemahasiswaan\DataAktivitasController@pembimbing','as'=>'kemahasiswaan-dataaktivitas.pembimbing']);
	
	//feeder - koneksi
	$router->get('/feeder/teskoneksi',['middleware'=>['role:superadmin|manajemen'],'uses'=>'Feeder\FeederController@teskoneksi','as'=>'feeder.teskoneksi']);				
	$router->post('/feeder/mahasiswa/getkrsmahasiswa',['middleware'=>['role:superadmin|manajemen'],'uses'=>'Feeder\FeederController@getkrsmahasiswa','as'=>'feeder-mahasiswa.getkrsmahasiswa']);				

	//setting - permissions
	$router->get('/system/setting/permissions',['middleware'=>['role:superadmin|akademik|pmb'],'uses'=>'System\PermissionsController@index','as'=>'permissions.index']);
	$router->get('/system/setting/permissions/all',['middleware'=>['role:superadmin|akademik|pmb'],'uses'=>'System\PermissionsController@all','as'=>'permissions.all']);
	$router->post('/system/setting/permissions/store',['middleware'=>['role:superadmin'],'uses'=>'System\PermissionsController@store','as'=>'permissions.store']);
	$router->delete('/system/setting/permissions/{id}',['middleware'=>['role:superadmin'],'uses'=>'System\PermissionsController@destroy','as'=>'permissions.destroy']);
	
	//setting - roles
	$router->get('/system/setting/roles',['middleware'=>['role:superadmin'],'uses'=>'System\RolesController@index','as'=>'roles.index']);
	$router->get('/system/setting/roles/{id}',['middleware'=>['role:superadmin'],'uses'=>'System\RolesController@show','as'=>'roles.show']);
	$router->post('/system/setting/roles/store',['middleware'=>['role:superadmin'],'uses'=>'System\RolesController@store','as'=>'roles.store']);
	$router->post('/system/setting/roles/storerolepermissions',['middleware'=>['role:superadmin'],'uses'=>'System\RolesController@storerolepermissions','as'=>'roles.storerolepermissions']);
	$router->post('/system/setting/roles/revokerolepermissions',['middleware'=>['role:superadmin'],'uses'=>'System\RolesController@revokerolepermissions','as'=>'users.revokerolepermissions']);
	$router->put('/system/setting/roles/{id}',['middleware'=>['role:superadmin'],'uses'=>'System\RolesController@update','as'=>'roles.update']);	
	$router->delete('/system/setting/roles/{id}',['middleware'=>['role:superadmin'],'uses'=>'System\RolesController@destroy','as'=>'roles.destroy']);
	$router->get('/system/setting/roles/{id}/permission',['middleware'=>['role:superadmin'],'uses'=>'System\RolesController@rolepermissions','as'=>'roles.rolepermissions']);
	$router->get('/system/setting/roles/{id}/allpermissions',['middleware'=>['role:superadmin'],'uses'=>'System\RolesController@roleallpermissions','as'=>'roles.roleallpermissions']);
	$router->get('/system/setting/rolesbyname/{id}/permission',['middleware'=>['role:superadmin'],'uses'=>'System\RolesController@rolepermissionsbyname','as'=>'roles.permissionbyname']);
	
	//setting - users
	$router->get('/system/users',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@index','as'=>'users.index']);
	$router->post('/system/users/store',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@store','as'=>'users.store']);
	$router->put('/system/users/updatepassword/{id}',['uses'=>'System\UsersController@updatepassword','as'=>'users.updatepassword']);
	$router->post('/system/users/uploadfoto/{id}',['uses'=>'System\UsersController@uploadfoto','as'=>'users.uploadfoto']);
	$router->post('/system/users/resetfoto/{id}',['uses'=>'System\UsersController@resetfoto','as'=>'users.resetfoto']);
	$router->post('/system/users/syncallpermissions',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@syncallpermissions','as'=>'users.syncallpermissions']);
	$router->post('/system/users/storeuserpermissions',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@storeuserpermissions','as'=>'users.storeuserpermissions']);
	$router->post('/system/users/revokeuserpermissions',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@revokeuserpermissions','as'=>'users.revokeuserpermissions']);
	$router->put('/system/users/{id}',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@update','as'=>'users.update']);
	$router->get('/system/users/{id}',['uses'=>'System\UsersController@show','as'=>'users.show']);
	$router->delete('/system/users/{id}',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@destroy','as'=>'users.destroy']);
	//lokasi method userpermission ada di file UserController
	$router->get('/system/users/{id}/permission',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@userpermissions','as'=>'users.permission']);
	//digunakan untuk mendapatkan daftar user permission beserta seluruh role permissionnya
	$router->get('/system/users/{id}/rolepermission',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@userrolepermission','as'=>'users.userrolepermission']);
	$router->get('/system/users/{id}/mypermission',['uses'=>'System\UsersController@mypermission','as'=>'users.mypermission']);
	$router->get('/system/users/{id}/prodi',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@usersprodi','as'=>'users.prodi']);
	$router->get('/system/users/{id}/roles',['uses'=>'System\UsersController@roles','as'=>'users.roles']);

	//setting - users keuangan
	$router->get('/system/userskeuangan',['middleware'=>['role:superadmin|keuangan'],'uses'=>'System\UsersKeuanganController@index','as'=>'userskeuangan.index']);
	$router->post('/system/userskeuangan/store',['middleware'=>['role:superadmin|keuangan'],'uses'=>'System\UsersKeuanganController@store','as'=>'userskeuangan.store']);
	$router->put('/system/userskeuangan/{id}',['middleware'=>['role:superadmin|keuangan'],'uses'=>'System\UsersKeuanganController@update','as'=>'userskeuangan.update']);
	$router->delete('/system/userskeuangan/{id}',['middleware'=>['role:superadmin|keuangan'],'uses'=>'System\UsersKeuanganController@destroy','as'=>'userskeuangan.destroy']);

	//setting - users pmb
	$router->get('/system/userspmb',['middleware'=>['role:superadmin|pmb'],'uses'=>'System\UsersPMBController@index','as'=>'userspmb.index']);
	$router->post('/system/userspmb/store',['middleware'=>['role:superadmin|pmb'],'uses'=>'System\UsersPMBController@store','as'=>'userspmb.store']);
	$router->put('/system/userspmb/{id}',['middleware'=>['role:superadmin|pmb'],'uses'=>'System\UsersPMBController@update','as'=>'userspmb.update']);
	$router->delete('/system/userspmb/{id}',['middleware'=>['role:superadmin|pmb'],'uses'=>'System\UsersPMBController@destroy','as'=>'userspmb.destroy']);

	//setting - users superadmin
	$router->get('/system/userssuperadmin',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersSuperadminController@index','as'=>'userssuperadmin.index']);
	$router->get('/system/userssuperadmin/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersSuperadminController@show','as'=>'userssuperadmin.show']);
	$router->post('/system/userssuperadmin/store',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersSuperadminController@store','as'=>'userssuperadmin.store']);
	$router->put('/system/userssuperadmin/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersSuperadminController@update','as'=>'userssuperadmin.update']);
	$router->delete('/system/userssuperadmin/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersSuperadminController@destroy','as'=>'userssuperadmin.destroy']);
	
	//setting - users akademik / manajemen
	$router->get('/system/usersmanajemen',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersManajemenController@index','as'=>'usersmanajemen.index']);
	$router->get('/system/usersmanajemen/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersManajemenController@show','as'=>'usersmanajemen.show']);
	$router->post('/system/usersmanajemen/store',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersManajemenController@store','as'=>'usersmanajemen.store']);
	$router->put('/system/usersmanajemen/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersManajemenController@update','as'=>'usersmanajemen.update']);
	$router->delete('/system/usersmanajemen/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersManajemenController@destroy','as'=>'usersmanajemen.destroy']);

	//setting - users program studi
	$router->get('/system/usersprodi',['middleware'=>['role:superadmin|programstudi'],'uses'=>'System\UsersProdiController@index','as'=>'usersprodi.index']);
	$router->post('/system/usersprodi/store',['middleware'=>['role:superadmin|programstudi'],'uses'=>'System\UsersProdiController@store','as'=>'usersprodi.store']);
	$router->put('/system/usersprodi/{id}',['middleware'=>['role:superadmin|programstudi'],'uses'=>'System\UsersProdiController@update','as'=>'usersprodi.update']);
	$router->get('/system/usersprodi/{id}',['middleware'=>['role:superadmin|programstudi'],'uses'=>'System\UsersProdiController@show','as'=>'usersprodi.show']);
	$router->delete('/system/usersprodi/{id}',['middleware'=>['role:superadmin|programstudi'],'uses'=>'System\UsersProdiController@destroy','as'=>'usersprodi.destroy']);

	//setting - users puslahta
	$router->get('/system/userspuslahta',['middleware'=>['role:superadmin|puslahta'],'uses'=>'System\UsersPuslahtaController@index','as'=>'userspuslahta.index']);
	$router->post('/system/userspuslahta/store',['middleware'=>['role:superadmin|puslahta'],'uses'=>'System\UsersPuslahtaController@store','as'=>'userspuslahta.store']);
	$router->put('/system/userspuslahta/{id}',['middleware'=>['role:superadmin|puslahta'],'uses'=>'System\UsersPuslahtaController@update','as'=>'userspuslahta.update']);
	$router->get('/system/userspuslahta/{id}',['middleware'=>['role:superadmin|puslahta'],'uses'=>'System\UsersPuslahtaController@show','as'=>'userspuslahta.show']);
	$router->delete('/system/userspuslahta/{id}',['middleware'=>['role:superadmin|puslahta'],'uses'=>'System\UsersPuslahtaController@destroy','as'=>'userspuslahta.destroy']);

	//setting - users dosen
	$router->get('/system/usersdosen',['uses'=>'System\UsersDosenController@index','as'=>'usersdosen.index']);
	$router->post('/system/usersdosen/store',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersDosenController@store','as'=>'usersdosen.store']);
	$router->get('/system/usersdosen/{id}',['middleware'=>['role:superadmin|akademik|programstudi|dosen'],'uses'=>'System\UsersDosenController@show','as'=>'usersdosen.show']);
	$router->get('/system/usersdosen/biodatadiri/{id}',['middleware'=>['role:superadmin|akademik|programstudi|dosen'],'uses'=>'System\UsersDosenController@biodatadiri','as'=>'usersdosen.biodatadiri']);
	$router->put('/system/usersdosen/biodatadiri/{id}',['middleware'=>['role:superadmin|akademik|programstudi|dosen'],'uses'=>'System\UsersDosenController@updatebiodatadiri','as'=>'usersdosen.updatebiodatadiri']);
	$router->put('/system/usersdosen/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersDosenController@update','as'=>'usersdosen.update']);
	$router->delete('/system/usersdosen/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersDosenController@destroy','as'=>'usersdosen.destroy']);

});

//payment - [bank riau kepri]
$router->group(['prefix'=>'v2/h2h/iak', 'middleware'=>'auth:api'], function () use ($router)
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