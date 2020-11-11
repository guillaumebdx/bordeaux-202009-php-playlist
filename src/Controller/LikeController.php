<?php


namespace App\Controller;


use App\Model\LikeManager;
use App\Model\TrackManager;

class LikeController extends AbstractController
{


    public function add()
    {
        $session = $this->twig->addGlobal('like', $_SESSION);
        $json = file_get_contents('php://input');
        $jsonData = json_decode($json, true);
        $nblike = $jsonData['like'];
        $trackId = $jsonData['trackId'];
        $trackManager = new TrackManager();
        $track = $trackManager->selectOneById($trackId);
        $nbLikeAfterClick = $nblike + 1;
        $trackManager->addLike($trackId, $nbLikeAfterClick);


        $response = [
            'status' => 'success',
            'like' => $nblike,
            'after' => $nbLikeAfterClick,
            'trackId' => $trackId,

        ];
        return json_encode($response);
    }
}
