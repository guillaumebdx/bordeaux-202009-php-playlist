<?php


namespace App\Controller;


use App\Model\UserManager;

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();
            //TODO check if username already exist
            $userData = [];
            $userData ['pseudo'] = $_POST['pseudo'];
            $userData ['email'] = $_POST['email'];
            $userData ['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userId = $userManager->createUser($userData);
            //TODO login directly
            header('Location: /');
        } else {
            echo 'Methode interdite';
        }
    }

    public function check()
    {
        $userManager = new UserManager();
        $userData = $userManager->selectOneByPseudo($_POST['pseudo']);

        // Todo to be update

        if (password_verify($_POST['password'], $userData['password'])) {
            $_SESSION['user'] = $userData;
            header('Location: /');
        } else {
            header('Location: /user/connect');
        }
    }
}