<?php
    require_once 'app/controllers/api.controller.php';
    require_once 'app/models/discos.model.php';

    class AlbumApiController extends ApiController {
        private $model;

        function __construct() {
            parent::__construct();
            $this->model = new DiscosModel();
        }

        //Con esta función busco si el parametro ingresado coincide con una de las columnas de la tabla
        function verificarParametro($parametro) {
            //Pido los discos para buscar los nombres de las columnas 
            $discos = $this->model->getAlbums();
            //Tomo solo el primero para optimizar un poco la búsqueda
            $disco=$discos[0];
            return array_key_exists($parametro, $disco);      
        }

        //GET
        function get($params = []) {
            if (empty($params)){
                //Doy la opción de mostrar discos ordenados por cada campo de la tabla
                if(isset($_GET['sort'])&&(isset($_GET['order']))){                    
                    $ordenador = $_GET['sort'];
                    $orden = $_GET['order']; 
                    //controlo que lo ingresado en $ordenador sea una de las columnas
                    if ($this->verificarParametro($ordenador)){
                        //verifico si $orden esta correctamente ingresado
                        if($orden=='asc'||$orden=='desc'||$orden=='ASC'||$orden=='DESC'){
                            $discos = $this->model->getAlbumsOrdenados($ordenador, $orden);
                            $this->view->response($discos, 200);
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
                    $discos = $this->model->getDiscosFiltradosPorArtista($artistaDeseado);
                    if($discos)
                        $this->view->response($discos, 200);
                    else
                    $this->view->response('El artista ingresado no existe.', 404);        
                }
                //Permito filtrar por seleccionados y no seleccionados
                else if (isset($_GET['selected'])){
                    $selected=$_GET['selected'];
                    if($selected==1){
                        $discos = $this->model->getSelectedAlbums($selected);
                        if($discos)
                            $this->view->response($discos, 200);
                        else
                        $this->view->response('No existen discos seleccionados.', 404);
                    }
                    else if($selected==0){
                        $discos = $this->model->getSelectedAlbums($selected);
                        if($discos)
                            $this->view->response($discos, 200);
                        else
                        $this->view->response('No existen discos no seleccionados.', 404);
                    }
                    else{
                        $this->view->response('El valor del parámetro es incorrecto.', 400);
                    }
                }
                else{
                    $discos = $this->model->getAlbums();
                    $this->view->response($discos, 200);                        
                }
            } 
            else{
                $disco = $this->model->getDiscoById($params[':ID']);
                if(!empty($disco)) {
                    $this->view->response($disco, 200);
                } 
                else {
                    $this->view->response(
                        'El disco con el id='.$params[':ID'].' no existe.'
                        , 404);
                }
            }
        }

        //DELETE
        function delete($params = []) {
            $id = $params[':ID'];
            $disco = $this->model->getDiscoById($id);
            if($disco) {
                $this->model->deleteAlbum($id);
                $this->view->response('El disco '.$disco[0]->album_name.' de '.$disco[0]->artist_name.' con id='.$id.' ha sido borrado.', 200);
            } else {
                $this->view->response('El disco con id='.$id.' no existe.', 404);
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
                //creo el disco
                $id = $this->model->insertAlbum($album_name, $release_date, $id_artist, $duration);
                // devuelvo el recurso creado
                $disco = $this->model->getDiscoById($id);
                $this->view->response($disco, 201);
            }
    
        }

        //PUT
        function update($params = []) {
            $id = $params[':ID'];
            $disco = $this->model->getDiscoById($id);

            if($disco) {
                $body = $this->getData();
                $album_name = $body->album_name;
                $release_date = $body->release_date;
                $id_artist = $body->id_artist;
                $duration = $body->duration;

                $this->model->updateAlbumData($id, $album_name, $release_date, $id_artist, $duration);

                $this->view->response('El disco '.$disco[0]->album_name.' de '.$disco[0]->artist_name.' con id='.$id.' ha sido modificado.', 200);
                //Pido el disco actualizado para mostrarlo
                $disco = $this->model->getDiscoById($id);
                //Muestro el disco editado ya que me resulta mejor para la experiencia de usuario
                $this->view->response($disco, 201);
            } else {
                $this->view->response('El disco con id='.$id.' no existe.', 404);
            }
        }
    }
