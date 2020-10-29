<?php


namespace App\Controller;



use App\Model\PlaylistManager;
use App\Model\TrackManager;

class TrackController extends AbstractController
{




    /**
     * Display item creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */



    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $playlistManager = new PlaylistManager();

                $today = new \DateTime();
                $todayFormat = $today->format('Y-m-d');
                $playlist = $playlistManager->selectPlaylistsByDay($todayFormat);
            if (!$playlist) {
                $newPlaylistId = $playlistManager->createPlaylist($todayFormat);
            }
                $trackManager = new TrackManager();
                $track = [
                    'title' => $_POST['title'],
                    'artist' => $_POST['artist'],
                    'url' => $_POST['url'],
                    'playlist_id' => $playlist ? $playlist['id'] : $newPlaylistId,
                ];
                $trackManager->insert($track);
                header('Location:/Home/index/');
        }
            return $this->twig->render('/Home/form.html.twig');
    }
}
