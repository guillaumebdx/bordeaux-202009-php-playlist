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

    public function selectTracksByDay($idPlaylist)
    {
        return $this->pdo->query("SELECT * FROM   $this->table  WHERE playlist_id= '$idPlaylist'")->fetchAll();
    }
}