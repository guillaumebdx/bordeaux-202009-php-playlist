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
        $nbTracksMax = 10;
        $today = new \DateTime();
        $todayFormat = $today->format('Y-m-d');
        $check = new PlaylistManager();
        $nbTracks = $check->nbTrackofTheDay($todayFormat);
        $nbTracks = (int)$nbTracks['nb_track'];
        if ($nbTracks >= $nbTracksMax) {
            header('Location: / ');
        }
        $checkData = $check->chekingTrack($todayFormat);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $url = $_POST['url'];
            $urlPreClean = explode("watch?v=", $url);
            $urlClean = substr(array_pop($urlPreClean), 0, 11);
            foreach ($checkData as $track => $trackid) {
                if ($trackid['title'] === $_POST['title'] && $trackid['url'] === $urlClean) {
                    // TODO retour message session';
                }
            }
            $playlist = $check->selectPlaylistsByDay($todayFormat);
            if (!$playlist) {
                $newPlaylistId = $check->createPlaylist($todayFormat);
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
            header('Location: /');
        }
        // TODO 'retour message session';
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
