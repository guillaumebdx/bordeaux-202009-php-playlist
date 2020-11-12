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
        $user = $userManager->selectAllUsers();
        $errorMessages = [];
        foreach ($user as $userData => $userPseudo) {
            if ($userPseudo['pseudo'] === $_POST['pseudo'] || $userPseudo['email'] === $_POST['email']) {
                $errorMessages = 'Le compte existe déjà';
                return $this->twig->render('/User/register.html.twig', [
                    'errors' => $errorMessages,
                ]);
            } else {

                $userData = [];
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $formValidator = new FormValidator();
                    $formValidator->setFields($_POST);
                    $formValidator->checkField();
                    $errorMessages = $formValidator->getErrors();
                    if (empty($errorMessages)) {
                        $userManager = new UserManager();
                        //TODO check if username already exist
                        $userData ['pseudo'] = $_POST['pseudo'];
                        $userData ['email'] = $_POST['email'];
                        $userData ['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $userId = $userManager->createUser($userData);
                        //TODO login directly

                        header('Location: /');
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
    }

    public function check()
    {
        var_dump($_SESSION);die;
        $userManager = new UserManager();
        $userData = $userManager->selectOneByPseudo($_POST['pseudo']);


        // Todo to be update
        if ($userData && password_verify($_POST['password'], $userData['password'])) {

            $_SESSION['user'] = $userData;
            return $this->twig->render('/Home/index.html.twig');
        }
        $this->checkConnexion();
        header('Location: /User/connect');
    }

    public function checkFormConnect()
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
        } return $this->check();
    }


    public function checkConnexion()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /User/connect');
        } else {
            header('Location: /');
        }
    }
}