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
    }