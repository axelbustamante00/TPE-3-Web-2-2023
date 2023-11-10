<?php
    require_once 'app/controllers/api.controller.php';
    require_once 'app/models/discos.model.php';

    class AlbumApiController extends ApiController {
        private $model;

        function __construct() {
            parent::__construct();
            $this->model = new DiscosModel();
        }

        //GET
        function get($params = []) {
            if (empty($params)){
                //Doy la opción de mostrar discos ordenados por duracion
                if(isset($_GET['sort'])&&(isset($_GET['order']))){                    
                    $ordenador = $_GET['sort'];
                    $orden = $_GET['order'];                    
                    $discos = $this->model->getAlbumsOrdenados($ordenador, $orden);
                    $this->view->response($discos, 200);                        
                }
                else{
                    $discos = $this->model->getAlbums();
                    $this->view->response($discos, 200);                        
                }
            } 
            else {
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
