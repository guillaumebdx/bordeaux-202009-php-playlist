<?php


namespace App\Model;


class PlaylistManager extends AbstractManager
{
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
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":date", $date, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }

    /**
     * For selectDayPlaylist.hmtl.twig
     */
    public function checkingTrack($date)
    {
        $statement = $this->pdo->prepare(
            "SELECT t.title, t.artist, t.url, t.playlist_id, t.nblike, t.user_id, u.pseudo  
                    FROM " . TrackManager::TABLE . " t 
                    JOIN " . self::TABLE . " p ON t.playlist_id = p.id 
                    JOIN " . UserManager::TABLE . " u ON t.user_id=u.id
                    WHERE p.date = '$date'"
        );
        $statement->bindValue(':date', $date, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}
