<?php
    require_once 'app/controllers/api.controller.php';
    require_once 'app/models/albums.model.php';

    class AlbumApiController extends ApiController {
        private $model;

        function __construct() {
            parent::__construct();
            $this->model = new AlbumsModel();
        }

        //Con esta función busco si el parametro ingresado coincide con una de las columnas de la tabla
        function verificarParametro($parametro) {
            //Pido los albums para buscar los nombres de las columnas 
            $albums = $this->model->getAlbums();
            //Tomo solo el primero para optimizar un poco la búsqueda
            $album=$albums[0];
            return array_key_exists($parametro, $album);      
        }

        //GET
        function get($params = []) {
            if (empty($params)){
                //Doy la opción de mostrar albums ordenados por cada campo de la tabla
                if(isset($_GET['sort'])&&(isset($_GET['order']))){                    
                    $ordenador = $_GET['sort'];
                    $orden = $_GET['order']; 
                    //controlo que lo ingresado en $ordenador sea una de las columnas
                    if ($this->verificarParametro($ordenador)){
                        //verifico si $orden esta correctamente ingresado
                        if($orden=='asc'||$orden=='desc'||$orden=='ASC'||$orden=='DESC'){
                            $albums = $this->model->getAlbumsOrdenados($ordenador, $orden);
                            $this->view->response($albums, 200);
                        }
                        else{
                            $this->view->response('El valor del parámetro order es incorrecto.', 400);
                        }
                    }
                    else{
                        $this->view->response(
                            'La tabla no posee tal campo.'
                            , 404);
                    }                                     
                }
                //Permito filtrar por artista
                else if(isset($_GET['artista'])){
                    $artistaDeseado = $_GET['artista'];
                    $albums = $this->model->getAlbumsFiltradosPorArtista($artistaDeseado);
                    if($albums)
                        $this->view->response($albums, 200);
                    else
                    $this->view->response('El artista ingresado no existe.', 404);        
                }
                //Permito filtrar por seleccionados y no seleccionados
                else if (isset($_GET['selected'])){
                    $selected=$_GET['selected'];
                    if($selected==1){
                        $albums = $this->model->getSelectedAlbums($selected);
                        if($albums)
                            $this->view->response($albums, 200);
                        else
                        $this->view->response('No existen albums seleccionados.', 404);
                    }
                    else if($selected==0){
                        $albums = $this->model->getSelectedAlbums($selected);
                        if($albums)
                            $this->view->response($albums, 200);
                        else
                        $this->view->response('No existen albums no seleccionados.', 404);
                    }
                    else{
                        $this->view->response('El valor del parámetro es incorrecto.', 400);
                    }
                }
                else{
                    $albums = $this->model->getAlbums();
                    $this->view->response($albums, 200);                        
                }
            } 
            else{
                $album = $this->model->getAlbumById($params[':ID']);
                if(!empty($album)) {
                    $this->view->response($album, 200);
                } 
                else {
                    $this->view->response(
                        'El album con el id='.$params[':ID'].' no existe.'
                        , 404);
                }
            }
        }

        //DELETE
        function delete($params = []) {
            $id = $params[':ID'];
            $album = $this->model->getAlbumById($id);
            if($album) {
                $this->model->deleteAlbum($id);
                $this->view->response('El album '.$album[0]->album_name.' de '.$album[0]->artist_name.' con id='.$id.' ha sido borrado.', 200);
            } else {
                $this->view->response('El album con id='.$id.' no existe.', 404);
            }
        }

        //POST
        function create($params = []) {
            $body = $this->getData();
            $album_name = $body->album_name;
            $release_date = $body->release_date;
            $id_artist = $body->id_artist;
            $duration = $body->duration;
            
            //Si uno de los datos está vacío no creo el recurso
            if (empty($album_name) || empty($release_date) || empty($id_artist) || empty($duration)) {
                $this->view->response("Complete los datos", 400);
            } else {
                //creo el album
                $id = $this->model->insertAlbum($album_name, $release_date, $id_artist, $duration);
                // devuelvo el recurso creado
                $album = $this->model->getAlbumById($id);
                $this->view->response($album, 201);
            }
    
        }

        //PUT
        function update($params = []) {
            $id = $params[':ID'];
            $album = $this->model->getAlbumById($id);

            if($album) {
                $body = $this->getData();
                $album_name = $body->album_name;
                $release_date = $body->release_date;
                $id_artist = $body->id_artist;
                $duration = $body->duration;

                $this->model->updateAlbumData($id, $album_name, $release_date, $id_artist, $duration);

                $this->view->response('El album '.$album[0]->album_name.' de '.$album[0]->artist_name.' con id='.$id.' ha sido modificado.', 200);
                //Pido el album actualizado para mostrarlo
                $album = $this->model->getAlbumById($id);
                //Muestro el album editado ya que me resulta mejor para la experiencia de usuario
                $this->view->response($album, 201);
            } else {
                $this->view->response('El album con id='.$id.' no existe.', 404);
            }
        }
    }
