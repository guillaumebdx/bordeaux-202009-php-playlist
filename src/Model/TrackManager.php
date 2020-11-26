<?php


namespace App\Model;


class TrackManager extends AbstractManager
{

    const TABLE = 'track';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $track): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (title, artist, url, playlist_id, user_id, nblike)
         VALUES (:title, :artist, :url, :playlist_id, :user_id, :nblike)");
        $statement->bindValue(':title', $track['title'], \PDO::PARAM_STR);
        $statement->bindValue(':artist', $track['artist'], \PDO::PARAM_STR);
        $statement->bindValue(':url', $track['url'], \PDO::PARAM_STR);
        $statement->bindValue(':playlist_id', $track['playlist_id'], \PDO::PARAM_STR);
        $statement->bindValue(':user_id', $track['user_id'], \PDO::PARAM_STR);
        $statement->bindValue(':nblike', 0, \PDO::PARAM_STR);
        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    /**
     * For _dayPlaylist.hmtl.twig
     */
    public function selectTracksByDay($idPlaylist)
    {
        return $this->pdo->query("SELECT t.id, t.title, t.artist, t.url, t.nblike, t.playlist_id, u.pseudo 
                FROM " . self::TABLE . " t
                JOIN " . UserManager::TABLE . " u ON t.user_id=u.id
                WHERE t.playlist_id= '$idPlaylist'")->fetchAll();
    }

    /**
     * For _top.hmtl.twig
     */
    public function selectTracksLike()
    {
        return $this->pdo->query('SELECT t.id, t.title, t.artist, t.url, t.nblike, p.date, u.pseudo 
            FROM ' . $this->table . ' t 
            JOIN ' . UserManager::TABLE . ' u ON t.user_id=u.id
            JOIN ' . PlaylistManager::TABLE . ' p ON t.playlist_id=p.id
            ORDER BY t.nblike DESC LIMIT 10')->fetchAll();
    }

    public function addLike($trackId, $nbLike)
    {
        $statement = $this->pdo->query("UPDATE " . self::TABLE . " SET `nblike` = $nbLike WHERE id = $trackId");

        return $statement->execute();
    }

    public function showLike($trackId)
    {
        return $this->pdo->query("SELECT nblike FROM  $this->table  WHERE id = $trackId ")->fetch();
    }

    public function delete(int $id)
    {

        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
