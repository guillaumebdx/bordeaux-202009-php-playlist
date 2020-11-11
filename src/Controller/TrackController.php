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

    public function checkConnexion()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /User/connect');
        }
    }


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
        return $this->twig->render('/Home/top.html.twig', [
            'tracks' => $tracks
        ]);
    }

    public function addLike($trackId)
    {
        $trackManager = new TrackManager();
        $track = $trackManager->selectOneById($trackId);
        $nbLikeAfterClick = $track['nblike'] + 1;
        $trackManager->addLike($trackId, $nbLikeAfterClick);
        header('Location: /');
    }

    public function showLike($trackId)
    {
        $trackManager = new TrackManager();
        $trackId = $_POST['id'];
        $nbLike = $trackManager->selectOneById($trackId);
    }

    public function dislike($trackId)
    {
        $trackManager = new TrackManager();
        $track = $trackManager->selectOneById($trackId);
        $nbLikeAfterClick = $track['nblike'] - 1;
        if ($nbLikeAfterClick < 0) {
            $nbLikeAfterClick = 0;
        }
        $trackManager->dislike($trackId, $nbLikeAfterClick);
        header('Location: /');
    }
}
