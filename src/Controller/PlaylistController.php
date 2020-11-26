<?php


namespace App\Controller;

use App\Model\PlaylistManager;
use DateTime;


class PlaylistController extends AbstractController
{
    public function playlistByDay()
    {
        $datePlaylist = [];
        $date = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'];
            $playlistManager = new PlaylistManager();
            $datePlaylist = $playlistManager->checkingTrack($date);
        }
        $todayDate = new DateTime();
        $todayDate = $todayDate->format('Y-m-d');
        return $this->twig->render('/Home/selectDayPlaylist.html.twig', [
            'tracks' => $datePlaylist,
            'today'   => $todayDate,
            'playlist_date' => $date,
        ]);
    }
}
