<?php
namespace Controller;

use http\Env\Request;
use Model\User;
use Model\UserManager;
use Model\Comment;
use Model\AdminCommentManager;
use App\Session;
use Model\ArticleManager;


class UserController extends AbstractController
{
    public function __construct()
    {
        parent:: __construct();
        if ($_SERVER['REQUEST_URI'] != '/login' && ($_SERVER['REQUEST_URI'] != '/logout')) {
            $this->verifyUser();
        }
    }



    public function logoutUser()
    {
        session_destroy();
        header('Location: /');
    }



    public function userShow(int $id)
    {

        $userManager = new UserManager($this->getPdo());
        $user = $userManager->selectOneById($id);
        $commentManager = new AdminCommentManager($this->getPdo());
        $comment = $commentManager->selectCommentByUser($id);
        $articleManager = new ArticleManager($this->getPdo());
        $articles = $articleManager->selectArticleByUser($id);
        return $this->twig->render('Admin/AdminUser/adminShow.html.twig', ['user' => $user, 'comments' => $comment, 'articles' => $articles]);

    }

    public function usersIndex()
    {
        $newUsersManager = new UserManager($this->getPdo());
        $newUsers = $newUsersManager->selectAllUsers();
        return $this->twig->render('Admin/AdminUser/indexUsers.html.twig', ['users' => $newUsers]);
    }

    public function userDelete(int $id)
    {
        $newUserManager = new UserManager($this->getPdo());
        $newUserManager->userDelete($id);

    }


    public function suscribeUser()
    {
        $errorRegister = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // appeler le manager
            $userManager = new UserManager($this->getPdo());

            if (!preg_match("/^[a-zA-Z ]*$/",$_POST['lastname']))
            {
                $errorRegister['lastname'] = "Seul les lettres et espaces sont autorisés." ;
            }
            if (strlen($_POST['lastname']) < 2 || strlen($_POST['lastname']) > 15)
            {
                $errorRegister['lastname'] = "Le nom doit comporter entre 2 et 15 caractères";
            }
            if (!preg_match("/^[a-zA-Z ]*$/",$_POST['firstname']))
            {
                $errorRegister['firstname'] = "Le prénom doit comporter seulement des lettres et espaces.";
            }
            if (strlen($_POST['firstname']) < 2 || strlen($_POST['firstname']) > 15)
            {
                $errorRegister['firstname'] = "Le prénom doit comporter entre 2 et 15 caractères";
            }
            if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email']))
            {
                $errorRegister['email'] = "Mauvais format de votre adresse email";
            }
            // Vérifie que l'email qu'on envoi n'est pas en base de donnée
            if ($userManager->existUser($_POST['email']))
            {
                $errorRegister['email'] = "L'adresse email est déja utilisé.";
            }
            if (strlen($_POST['password']) < 8 )
            {
                $errorRegister['password'] = "Le mot de passe doit comporter au minimum 8 caractères";
            }
            if ($_POST['password'] !== ($_POST['password_control']))
            {
                $errorRegister['password'] = "Les mots de passe saisis ne sont pas identiques.";
            }
            if (empty($errorRegister))
            {
                $newUser = new User;
                $newUser->setLastname($_POST['lastname']);
                $newUser->setFirstname($_POST['firstname']);
                $newUser->setEmail($_POST['email']);
                $newUser->setPass($_POST['password']);
                $id = $userManager->suscribe($newUser);
                header('Location: /login');

            }

        }
        return $this->twig->render('signUp.html.twig', ["errorRegister" => $errorRegister, "post" =>$_POST]);

    }

    public function logUser() // User: pascal.dutroux@free.fr  password: azertyuio
    {
        // Si user connecter
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit();
        }


        $errorLoginUser = "";

        if (!empty($_POST)) {

            // appeler le manager
            $auth = new UserManager($this->getPdo());
            $user = $auth->loginUser($_POST['email']);

            if ($user) {
                if (password_verify($_POST['password'], $user->getPass())) {
                    // Si password ok, creation session user avec lastname, firstname, et email.
                    $_SESSION['user'] = [
                        "id" => $user->getId(),
                        "lastname" => $user->getlastname(),
                        "firstname" => $user->getFirstname(),
                        "email" => $user->getEmail(),
                        "message" => 'Vous êtes connecté'
                    ];

                    header('Location: /');

                }else{
                    $errorLoginUser = 'Identifiants incorrects ';

                }
            }
            else {
                $errorLoginUser = 'Identifiants incorrects';
            }
        }
        return $this->twig->render('loginUser.html.twig', ["errorLoginUser" => $errorLoginUser]);
    }



    public function addUser()
    {
        /*$fisrtnameErr = $lastnameErr = $emailErr = $pwdErr = $statusErr = "";
        $fisrtname = $lastname = $email = $pwd = $status = "";*/

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') // affiche si
        {
            if (empty($_POST["firstname"])) {
                //$fisrtnameErr = "Le nom est requis !";
                $errors['firstname'] = "le nom est requis";
            }
            if (empty($_POST["lastname"])) {
                $errors['lastname'] = "Le prénom est requis !";
            }
            if (empty($_POST["email"])) {
                $errors['email'] = "L'email est requis !";
            }
            if (empty($_POST["password"])) {
                $errors['password'] = "Le mot de passe est requis !";
            }
            if (empty($_POST["status"])) {
                $errors['status'] = "Le status est requis !";
            }
            if (empty($errors))
            {
                $newUserManager = new UserManager($this->getPdo());
                $newUser = new User;

                $newUser->setLastname($_POST['firstname']);
                $newUser->setFirstname($_POST['lastname']);
                $newUser->setEmail($_POST['email']);
                $newUser->setPass($_POST['password']);
                $newUser->setStatus($_POST['status']);
                $id = $newUserManager->userAdd($newUser);
                header('Location: /admin/users');
            }
        }

        $active = "add";
        return $this->twig->render('Admin/AdminUser/addUser.html.twig', ["active" => $active, 'errors' => $errors, 'nameErr' =>$_POST]); // traitement
    }

}

