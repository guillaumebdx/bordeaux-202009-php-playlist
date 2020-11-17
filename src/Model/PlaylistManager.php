<?php


namespace App\Model;


class PlaylistManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'playlist';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectPlaylistsByDay($date)
    {
        return $this->pdo->query("SELECT * FROM   $this->table  WHERE date = '$date'")->fetch();
    }


    public function createPlaylist($day)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`date`) VALUES (:day)");
        $statement->bindValue(':day', $day, \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function nbTrackofTheDay($date)
    {
        $query = "SELECT p.date, COUNT(*) AS nb_track 
    FROM " . self::TABLE . " p JOIN " . TrackManager::TABLE . " t 
    ON t.playlist_id=p.id WHERE p.date='$date'";
        $statment = $this->pdo->prepare($query);
        $statment->bindValue(":date", $date, \PDO::PARAM_STR);
        $statment->execute();
        return $statment->fetch();
    }
    public function chekingTrack($date)
    {
        $statement = $this->pdo->prepare(
            "SELECT t.title, t.artist, t.url, t.playlist_id, t.user_id FROM " . TrackManager::TABLE . " t 
        JOIN " . self::TABLE . " p ON t.playlist_id = p.id WHERE p.date = '$date'"
        );
        $statement->bindValue(':date', $date, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}
