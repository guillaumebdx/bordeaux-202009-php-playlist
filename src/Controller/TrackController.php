<?php


namespace App\Controller;



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
            $trackManager = new TrackManager();
            $track = [
                'title'  => $_POST['title'],
                'artist' => $_POST['artist'],
                'url'    => $_POST['url'],
            ];
             $trackManager->insert($track);
            header('Location:/Home/index/');
        }
        return $this->twig->render('/Home/_form.html.twig');
    }
}