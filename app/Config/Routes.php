<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->post('auth/attemptLogin', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');


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


    $routes->get('datakelas', 'OperatorController::kelas');
    $routes->post('deleteKelas', 'OperatorController::deleteKelas');
    $routes->post('tambahKelas', 'OperatorController::tambahKelas');
    $routes->post('editKelas', 'OperatorController::editKelas');

    $routes->get('dataMapel', 'OperatorController::kelas');
});

$routes->group('dashboard', ['filter' => 'role:guru'], function ($routes) {
    $routes->get('guru', 'Dashboard::guru');
});

$routes->group('dashboard', ['filter' => 'role:siswa'], function ($routes) {
    $routes->get('siswa', 'Dashboard::siswa');
});
