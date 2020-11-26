<?php


namespace App\Controller;

use App\Model\PlaylistManager;
use DateTime;


class PlaylistController extends AbstractController
{
    public function playlistByDay()
    {
        $datePlaylist = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'];
            $playlistManager = new PlaylistManager();
            $datePlaylist = $playlistManager->checkingTrack($date);
        }
        $date = new DateTime();
        $date = $date->format('Y-m-d');
        return $this->twig->render('/Home/selectDayPlaylist.html.twig', [
            'tracks' => $datePlaylist,
            'today'   => $date,
        ]);
    }
}
