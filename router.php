<?php
    require_once 'config.php';
    require_once 'libs/router.php';

    require_once 'app/controllers/album.api.controller.php';

    $router = new Router();

    #                 endpoint      verbo     controller           método
    $router->addRoute('discos',     'GET',    'AlbumApiController', 'get'   ); # AlbumApiController->get($params)
    $router->addRoute('discos',     'POST',   'AlbumApiController', 'create');
    $router->addRoute('discos/:ID', 'GET',    'AlbumApiController', 'get'   );
    $router->addRoute('discos/:ID', 'PUT',    'AlbumApiController', 'update');
    $router->addRoute('discos/:ID', 'DELETE', 'AlbumApiController', 'delete');
    
    $router->route($_GET['resource']        , $_SERVER['REQUEST_METHOD']);
