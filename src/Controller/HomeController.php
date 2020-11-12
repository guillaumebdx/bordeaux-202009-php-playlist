<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\PlaylistManager;
use App\Model\TrackManager;

class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $playlistManager = new PlaylistManager();
        $today = new \DateTime();
        $playlist = $playlistManager->selectPlaylistsByDay($today->format('Y-m-d'));
        $trackManager = new TrackManager();
        $tracks = [];
        if ($playlist) {
            $tracks = $trackManager->selectTracksByDay($playlist['id']);
        }
        $tops = $trackManager->selectTracksLike();
        return $this->twig->render('Home/index.html.twig', [
            'tracks' => $tracks,
            'playlist' => $playlist,
            'tops' => $tops,

        ]);
    }
}
