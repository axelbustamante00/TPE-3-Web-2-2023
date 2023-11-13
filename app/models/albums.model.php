<?php
require_once 'model.php';
require_once './app/helpers/db.helper.php';

class AlbumsModel extends Model {
    
    protected $db;


    public function getAlbums(){
        $query = $this->db->prepare('SELECT albums.*, artistas.artist_name as artist_name FROM albums JOIN artistas ON albums.id_artist = artistas.id_artist');
        $query->execute();
        $albums = $query->fetchAll(PDO::FETCH_ASSOC);
        return $albums;
    }

    public function insertAlbum($album_name, $release_date, $id_artist, $duration){    
        $query = $this->db->prepare('INSERT INTO albums (album_name, release_date, id_artist, duration) VALUES (?,?,?,?)');
        $query->execute([$album_name, $release_date, $id_artist, $duration]);
        return $this->db->lastInsertId();
    }

    public function deleteAlbum($id){
        $query = $this->db->prepare('DELETE FROM albums WHERE id_album = ?');
        $query->execute([$id]);
    }

    public function updateAlbum($id){
        $query = $this->db->prepare('UPDATE albums SET selected = 1 WHERE id_album = ?');
        $query->execute([$id]);
    }

    public function getAlbumById($id){
        $query = $this->db->prepare("SELECT albums.*, artistas.artist_name as artist_name FROM albums JOIN artistas ON albums.id_artist = artistas.id_artist WHERE albums.id_album = ?");
        $query->execute([$id]);
        $album= $query->fetchAll(PDO::FETCH_OBJ);
        return $album;
    }

    public function getAlbumsFiltradosPorArtista($artistaDeseado){
        $query = $this->db->prepare("SELECT albums.*, artistas.artist_name as artist_name FROM albums JOIN artistas ON albums.id_artist = artistas.id_artist WHERE artistas.artist_name = '$artistaDeseado'");
        $query->execute();
        $albums = $query->fetchAll(PDO::FETCH_OBJ);
        return $albums;
    }

    public function getSelectedAlbums($selected){
        $query = $this->db->prepare('SELECT albums.*, artistas.artist_name as artist_name FROM albums JOIN artistas ON albums.id_artist = artistas.id_artist WHERE albums.selected = ?');
        $query->execute([$selected]);
        $albums = $query->fetchAll(PDO::FETCH_OBJ);
        return $albums;
    }
    

    public function updateAlbumData($id, $album_name, $release_date, $id_artist, $duration){
        $query = $this->db->prepare('UPDATE albums SET album_name = ?, release_date = ?, id_artist = ?, duration = ? WHERE id_album = ?');
        $query->execute([$album_name, $release_date, $id_artist, $duration, $id]);

    }

    public function getAlbumsOrdenados($ordenador, $orden){
        $query = $this->db->prepare('SELECT albums.*, artistas.artist_name as artist_name FROM albums JOIN artistas ON albums.id_artist = artistas.id_artist ORDER BY '.$ordenador.' '.$orden);
        $query->execute();
        $albums = $query->fetchAll(PDO::FETCH_OBJ);
        return $albums;
    }
    
}