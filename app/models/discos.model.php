<?php
require_once 'model.php';
require_once './app/helpers/db.helper.php';

class DiscosModel extends Model {
    
    protected $db;


    public function getAlbums(){
        $query = $this->db->prepare('SELECT discos.*, artistas.artist_name as artist_name FROM discos JOIN artistas ON discos.id_artist = artistas.id_artist');
        $query->execute();
        $albums = $query->fetchAll(PDO::FETCH_OBJ);
        return $albums;
    }

    public function insertAlbum($album_name, $release_date, $id_artist, $duration){
    
        $query = $this->db->prepare('INSERT INTO discos (album_name, release_date, id_artist, duration) VALUES (?,?,?,?)');
        $query->execute([$album_name, $release_date, $id_artist, $duration]);
        return $this->db->lastInsertId();
    }

    public function deleteAlbum($id){
        $query = $this->db->prepare('DELETE FROM discos WHERE id_album = ?');
        $query->execute([$id]);
    }

    public function updateAlbum($id){
        $query = $this->db->prepare('UPDATE discos SET selected = 1 WHERE id_album = ?');
        $query->execute([$id]);
    }

    public function getDiscoById($id){
        $query = $this->db->prepare("SELECT discos.*, artistas.artist_name as artist_name FROM discos JOIN artistas ON discos.id_artist = artistas.id_artist WHERE discos.id_album = ?");
        $query->execute([$id]);
        $disco= $query->fetchAll(PDO::FETCH_OBJ);
        return $disco;
    }


    /*  
    *
    *           ESTOS DOS METODOS SON PARA LUEGO HACER EL PUNTO DE FILTRADO    
    *
    *
    public function getDiscosFiltradosPorArtista($artistaDeseado){
        $query = $this->db->prepare("SELECT discos.*, artistas.artist_name as artist_name FROM discos JOIN artistas ON discos.id_artist = artistas.id_artist WHERE artistas.artist_name = '$artistaDeseado'");
        $query->execute();
        $discos = $query->fetchAll(PDO::FETCH_OBJ);
        return $discos;
    }

    public function getSelectedAlbums(){
        $query = $this->db->prepare('SELECT discos.*, artistas.artist_name as artist_name FROM discos JOIN artistas ON discos.id_artist = artistas.id_artist WHERE discos.selected = 1');
        $query->execute();
        $albums = $query->fetchAll(PDO::FETCH_OBJ);
        return $albums;
    }
    */

    public function updateAlbumData($id, $album_name, $release_date, $id_artist, $duration){
        $query = $this->db->prepare('UPDATE discos SET album_name = ?, release_date = ?, id_artist = ?, duration = ? WHERE id_album = ?');
        $query->execute([$album_name, $release_date, $id_artist, $duration, $id]);

    }

    public function getAlbumsOrdenados($ordenador, $orden){
        $query = $this->db->prepare('SELECT discos.*, artistas.artist_name as artist_name FROM discos JOIN artistas ON discos.id_artist = artistas.id_artist ORDER BY ? ?');
        $query->execute([$ordenador, $orden]);
        $albums = $query->fetchAll(PDO::FETCH_OBJ);
        return $albums;
    }
    
}