<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->post('auth/attemptLogin', 'AuthController::attemptLogin');
$routes->post('logout', 'AuthController::logout');


$routes->group('operator', ['filter' => 'role:operator'], function ($routes) {
    $routes->get('', 'OperatorController::index');

    $routes->get('datasiswa', 'OperatorController::siswa');
    $routes->get('datasiswa/(:any)', 'OperatorController::siswaKelas/$1');
    $routes->post('tambahSiswa', 'OperatorController::tambahSiswa');
    $routes->post('deleteSiswa', 'OperatorController::deleteSiswa');
    $routes->post('editSiswa', 'OperatorController::editSiswa');

    $routes->get('dataguru', 'OperatorController::guru');
    $routes->post('tambahGuru', 'OperatorController::tambahGuru');
    $routes->post('deleteGuru', 'OperatorController::deleteGuru');
    $routes->post('editGuru', 'OperatorController::editGuru');

    $routes->get('datamapel', 'MapelController::index');
    $routes->get('mapelPerkelas', 'MapelController::mapelKelas');
    $routes->post('tambahMapel', 'MapelController::tambahMapel');
    $routes->post('deleteMapel', 'MapelController::deleteMapel');
    $routes->post('editMapel', 'MapelController::editMapel');
    $routes->get('getMapelByKelas/(:any)', 'MapelController::getMapelByKelas/$1');


    $routes->get('datakelas', 'OperatorController::kelas');
    $routes->post('deleteKelas', 'OperatorController::deleteKelas');
    $routes->post('tambahKelas', 'OperatorController::tambahKelas');
    $routes->post('editKelas', 'OperatorController::editKelas');

    $routes->get('jadwalujian', 'UjianController::index');
    $routes->post('tambahIndukUjian', 'UjianController::tambahIndukUjian');
    $routes->get('ujian/(:any)', 'UjianController::ujian/$1');
    $routes->post('tambahUjian', 'UjianController::tambahujian');
    $routes->get('getUjian/(:any)', 'UjianController::getUjian/$1');
    $routes->post('deleteujian/(:any)', 'UjianController::deleteujian/$1');
    $routes->post('updateUjian', 'UjianController::updateUjian');
    $routes->get('hasilujian', 'UjianController::hasilujian');
    $routes->get('hasilujianPerkelas/(:any)', 'UjianController::hasilujianPerkelas/$1');
});

$routes->group('guru', ['filter' => 'role:guru'], function ($routes) {
    $routes->get('', 'GuruController::index');
    $routes->get('kelolaSoal/(:any)', 'GuruController::kelolaSoal/$1');
    $routes->get('download-template', 'SoalController::downloadtemplate');


    $routes->post('tambahSoal', 'SoalController::tambahSoal');
    $routes->post('deleteSoal', 'SoalController::deleteSoal');
    $routes->post('deleteAllSoal', 'SoalController::deleteAll');
    $routes->post('getSoal', 'SoalController::getSoal');
    $routes->post('editSoal', 'SoalController::editSoal');
    $routes->post('importSoal', 'SoalController::importSoal');
});

$routes->group('siswa', ['filter' => 'role:siswa'], function ($routes) {
    $routes->get('', 'SiswaController::index');
    $routes->get('startTest/(:any)', 'UjianController::startTest/$1');

    $routes->post('jawaban/(:any)', 'UjianController::submitUjian/$1');
    $routes->get('ujian/selesai/(:any)', 'UjianController::selesaiUjian/$1');
    $routes->get('hasilujian', 'UjianController::resultTest');
    $routes->get('hasilujian/(:any)', 'UjianController::individualResult/$1');
});
