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

    public function check()
    {
        $userManager = new UserManager();
        $userData = $userManager->selectOneByPseudo($_POST['pseudo']);

        // Todo to be update
        /*
         * if(password_verify($_POST['password'], $userData['password'])) {*/
         $_SESSION['user'] = $userData ;
         header('Location: /') ;
         /* } else {
         *  header('Location: /user/connect');
         * }
        */
    }
}