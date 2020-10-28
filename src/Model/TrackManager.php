<?php


namespace App\Model;


class TrackManager extends AbstractManager
{

    /**
     *
     */

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
        // prepared request

        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (title, artist, url)
         VALUES (:title, :artist, :url)");
        $statement->bindValue(':title', $track['title'], \PDO::PARAM_STR);
        $statement->bindValue(':artist', $track['artist'], \PDO::PARAM_STR);
        $statement->bindValue(':url', $track['url'], \PDO::PARAM_STR);
        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }


    public function selectTracksByDay($idPlaylist)
    {
        return $this->pdo->query("SELECT * FROM  $this->table  WHERE playlist_id= '$idPlaylist'")->fetchAll();
    }
}

