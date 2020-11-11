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
        $userManager = new UserManager();
        $user = $userManager->selectAllUsers();
        foreach ($user as $userData => $userPseudo) {
            echo $userPseudo['pseudo'];

            if ($userPseudo ['pseudo'] = $_POST['pseudo']) {
                header('Location: /');
            } else {

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
        }
    }

    public function check()
    {
        $userManager = new UserManager();
        $userData = $userManager->selectOneByPseudo($_POST['pseudo']);

        // Todo to be update
            if ($_SESSION['user']['pseudo'] === $userData) {
                if (password_verify($_POST['password'], $userData['password'])) {
                    $_SESSION['user'] = $userData;
                    header('Location: /');
                } else {
                    header('Location: /User/connect');
                }
            }
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