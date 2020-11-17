<?php


namespace App\Controller;

use App\Model\PlaylistManager;


class PlaylistController extends AbstractController
{
    public function playlistByDay()
    {

        $datePlaylist = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'];
            $playlistManager = new PlaylistManager();
            $datePlaylist = $playlistManager->chekingTrack($date);
        }

        return $this->twig->render('/Home/selectDayPlaylist.html.twig', [
            'tracks' => $datePlaylist,
        ]);
    }
}
