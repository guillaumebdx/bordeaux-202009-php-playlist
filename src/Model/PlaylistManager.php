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
}
