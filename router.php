<?php
    require_once 'config.php';
    require_once 'libs/router.php';

    require_once 'app/controllers/album.api.controller.php';

    $router = new Router();

    #                 endpoint      verbo     controller           método
    $router->addRoute('albums',     'GET',    'AlbumApiController', 'get'   ); # AlbumApiController->get($params)
    $router->addRoute('albums',     'POST',   'AlbumApiController', 'create');
    $router->addRoute('albums/:ID', 'GET',    'AlbumApiController', 'get'   );
    $router->addRoute('albums/:ID', 'PUT',    'AlbumApiController', 'update');
    $router->addRoute('albums/:ID', 'DELETE', 'AlbumApiController', 'delete');
    
    $router->route($_GET['resource']        , $_SERVER['REQUEST_METHOD']);
