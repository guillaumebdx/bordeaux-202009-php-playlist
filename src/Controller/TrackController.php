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

        $this->checkConnexion();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $playlistManager = new PlaylistManager();

            $url = $_POST['url'];
            $urlPreClean = explode("watch?v=", $url);
            $urlClean = substr(array_pop($urlPreClean), 0, 11);

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
                    'url' => $urlClean,
                    'playlist_id' => $playlist ? $playlist['id'] : $newPlaylistId,
                    'user_id' => $_SESSION['user']['id'],
                    'nblike' => 0,
                ];
                $trackManager->insert($track);
                header('Location:/Home/index/');

        }
        return $this->twig->render('/Home/add.html.twig');
    }

    public function top()
    {
        $top = new TrackManager();
        $tracks = $top->selectTracksLike();
        return $this->twig->render('/Home/_top.html.twig', [
            'tracks' => $tracks
        ]);
    }


}
