<?php

namespace App\Controller;


use App\Model\UserManager;
use App\Service\FormValidator;

class UserController extends AbstractController
{
    public function register()
    {
        return $this->twig->render('/User/register.html.twig');
    }

    public function connect()
    {
        return $this->twig->render('/User/connect.html.twig');
    }

    public function add()
    {
        $userManager = new UserManager();
        $users = $userManager->selectAllUsers();
        foreach ($users as $userData => $userPseudo) {
            $userData = [];
            $errorMessages = [];
            $formValidator = new FormValidator();
            $formValidator->setFields($_POST);
            $formValidator->checkField();
            $errorMessages = $formValidator->getErrors();
            if ($userPseudo['pseudo'] === $_POST['pseudo'] || $userPseudo['email'] === $_POST['email']) {
                $errorMessages['alreadyRegistered'] = 'Le compte existe déjà, recommence';
                if ($userPseudo['pseudo'] !== $_POST['pseudo']) {
                    $userData['pseudo'] = $_POST['pseudo'];
                    $errorMessages['email'] = 'Remplir ce champ';
                    $errorMessages['password'] = 'Remplir ce champ';
                }
                if ($userPseudo['email'] !== $_POST['email']) {
                    $userData['email'] = $_POST['email'];
                    $errorMessages['pseudo'] = 'Remplir ce champ';
                    $errorMessages['password'] = 'Remplir ce champ';
                }
                return $this->twig->render('/User/register.html.twig', [
                    'errors' => $errorMessages,
                    'userData' => $userData,
                ]);
            } else {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $formValidator = new FormValidator();
                    $formValidator->setFields($_POST);
                    $formValidator->checkField();
                    $errorMessages = $formValidator->getErrors();
                    if (empty($errorMessages)) {
                        $userManager = new UserManager();
                        $userData ['pseudo'] = $_POST['pseudo'];
                        $userData ['email'] = $_POST['email'];
                        $userData ['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $userId = $userManager->createUser($userData);
                        header('Location: /');
                        exit();
                    } else {
                        $userData ['pseudo'] = $_POST['pseudo'];
                        $userData ['email'] = $_POST['email'];
                        $userData ['password'] = $_POST['password'];

                        return $this->twig->render('/User/register.html.twig', [
                            'errors' => $errorMessages,
                            'userData' => $userData,
                        ]);
                    }
                }
            }
        }
        $this->check();
    }

    public function check()
    {
        $errorMessages = [];
        $userData = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formValidator = new FormValidator();
            $formValidator->setFields($_POST);
            $formValidator->checkField();
            $errorMessages = $formValidator->getErrors();

            if (!empty($errorMessages)) {
                $userData['pseudo'] = $_POST['pseudo'];
                $userData['password'] = $_POST['password'];

                return $this->twig->render('/User/connect.html.twig', [
                    'errors' => $errorMessages,
                    'userData' => $userData,
                ]);
            }
        }
        $userManager = new UserManager();
        $userData = $userManager->selectOneByPseudo($_POST['pseudo']);

        if ($userData && password_verify($_POST['password'], $userData['password'])) {
            $_SESSION['user'] = $userData;
            header('Location: /');
            exit();
        }
        return $this->twig->render('/User/connect.html.twig', [
            'errors' => $errorMessages,
            'userData' => $userData,
        ]);
    }

    public function showUsersTotalTracks()
    {
        $userManager = new UserManager();
        $userTotalTracks = $userManager->selectUserTotalTracksById();
        return $this->twig->render('/Home/explorer.html.twig', [
            'users' => $userTotalTracks,
        ]);
    }
    

    public function disconnect()
    {
        session_destroy();
        header('Location: /');
        exit();
    }

    public function showAllTracksByProfil($id)
    {
        $userManager = new UserManager();
        $tracks = $userManager->selectAllTracksByProfil($id);
        $pseudo = $tracks[0]['pseudo'];
        return $this->twig->render('Home/_profil.html.twig', [
            'tracks' => $tracks,
            'pseudo' => $pseudo,
        ]);
    }

    public function showProfil()
    {
        $id = $_SESSION['user']['id'];
        $userManager = new UserManager();
        $userTotalTracks = $userManager->selectUserTotalTracksById();
        $tracks = $userManager->selectAllTracksByProfil($id);
        $pseudo = $_SESSION['user']['pseudo'];
        $nbtracks = 0;
        foreach($tracks as $nbUserTrack) {
            $nbtracks += 1;
        }
        if(!empty($tracks)){
            $pseudo = $tracks[0]['pseudo'];
            $error =[];
        } else {
            $_SESSION['error'] = "Tu n'as pas encore ajouté de musique... 😔";
            $error = $_SESSION['error'];
        }
        return $this->twig->render('User/profile.html.twig', [
            'tracks' => $tracks,
            'pseudo' => $pseudo,
            'error' => $error,
            'nbtrack' => $nbtracks,
        ]);
    }
}