<?php
namespace Controller;

use http\Env\Request;
use Model\ArticleManager;
use Model\Article;
use Model\UserManager;
use Model\AdminCommentManager;


class AdminController extends AbstractController
{
    public function __construct()
    {
        parent:: __construct();
        if ($_SERVER['REQUEST_URI'] != '/admin/logAdmin') {
            $this->verifyAdmin();
        }
    }

    public function showDashboard()
    {
        $connexionMessage = null;
        $article = "home";
        $countArticle = new ArticleManager($this->getPdo());            // connexion au pdo de l'article manager
        $numberArticles = $countArticle->count();                       // comptage du nombre d'article
        $lastArticles = new ArticleManager($this->getPdo());
        $lastArticles = $lastArticles->selectArticlesForIndex();
        $countUsers = new UserManager($this->getPdo());                 // idem mais pour les utilisateurs
        $numberUsers = $countUsers->count();
        $lastUsers = new UserManager($this->getPdo());                 // idem mais pour les utilisateurs
        $lastUsers = $lastUsers->selectUsersForIndex();
        $countComment = new AdminCommentManager($this->getPdo());       // idem pour les commentaires
        $numberComments = $countComment->count();
        $lastComments = new AdminCommentManager($this->getPdo());       // idem pour les commentaires
        $lastComments = $lastComments->selectCommentsForIndex();
        if (isset($_SESSION['admin']) && isset($_SESSION['admin']['message'])) {
            $connexionMessage = $_SESSION['admin']['message'];
            unset($_SESSION['admin']['message']);
        };
        return $this->twig->render('Admin/admin_dashboard.html.twig', ["active" => $article, "user" => $_SESSION['admin'],
            'totalArticles' => $numberArticles, 'totalUsers' => $numberUsers, 'totalComments' => $numberComments,
            "session" => $_SESSION, 'connexionMessage' => $connexionMessage, 'isLogged' => $this->isLoggedAdmin(),
            'lastarticles' => $lastArticles, 'lastusers' => $lastUsers, 'lastcomments' => $lastComments,
        ]);
    }


    //show user and his comments
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


// show all users to manage them
    public function usersIndex()
    {
        $usersManager = new UserManager($this->getPdo());
        $users = $usersManager->selectAllUsers();
        $active = "utilisateurs";
        return $this->twig->render('Admin/AdminUser/indexUsers.html.twig', ['users' => $users, "active" => $active]);
    }

    // delete a user
    public function userDelete(int $id)
    {
        $newUserManager = new UserManager($this->getPdo());
        $newUserManager->userDelete($id);

    }

// logout for admin
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /admin/logAdmin');
    }

    // show one article to admin in order to modify or not
    public function adminShow(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);

        return $this->twig->render('Admin/AdminArticle/adminShow.html.twig', ['article' => $article]);
    }

    // show all articles for admin
    public function indexAdmin()
    {
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectAllArticles();
        $active = "articles";
        return $this->twig->render('Admin/AdminArticle/indexAdmin.html.twig', ['articles' => $articles, "active" => $active]);
    }


// add an article
    public function add()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') // affiche si
        {   $articleManager = new ArticleManager($this->getPdo());
            $article = new Article();
            if (empty($_POST["title"])) {
                $errors["title"] = "Le titre est requis !";
            }

            if (empty($_POST["content"])) {
                $errors["content"] = "Le contenu est requis !";
            }
            if (empty($_FILES['image']['name'])) {
                $errors['image'] = 'Ajoutez une image';
            } elseif (!empty($_POST) && !empty($_FILES['image'])){
                    $allowExtension = ['.jpg', '.jpeg', '.gif', '.png'];
                    $maxSize = 1000000;
                    $extension = strtolower(strrchr($_FILES['image']['name'], '.'));
                    $size = $_FILES['image']['size'];
                    if (!in_array($extension, $allowExtension)) {
                        $errors['image'] = 'Seuls les fichiers image .jpg, .jpeg, .gif et .png sont autorisés.';
                    }
                    if (($size > $maxSize) || ($size == 0)) {
                        $errors['image'] = 'Votre fichier est trop volumineux. Taille maximale autorisée : 1Mo.';
                    }
                    if(empty($errors)) {

                    }

            }
            if (empty($_FILES['imageMin']['name'])) {
                $errors['imageMin'] = 'Ajoutez une miniature';
            } elseif (!empty($_POST) && !empty($_FILES['imageMin'])) {
            // TODO show message when miniature error
                $allowExtension = ['.jpg', '.jpeg', '.gif', '.png'];
                $maxSize = 1000000;
                $extension = strtolower(strrchr($_FILES['image']['name'], '.'));
                $size = $_FILES['imageMin']['size'];

                if (!in_array($extension, $allowExtension)) {
                    $errors['imageMin'] = 'Seuls les fichiers image .jpg, .jpeg, .gif et .png sont autorisés.';
                }
                if (($size > $maxSize) || ($size == 0)) {
                    $errors['imageMin'] = 'Votre fichier est trop volumineux. Taille maximale autorisée : 1Mo.';
                }
            }


            if (empty($errors)) {
                $filename = 'image-' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../public/assets/images/' . $filename);
                $filenameMin = 'image-' . $_FILES['imageMin']['name'];
                move_uploaded_file($_FILES['imageMin']['tmp_name'], '../public/assets/images/' . $filenameMin);
                $article->setUserId($_SESSION['admin']['id']);
                $article->setTitle($_POST['title']);
                $article->setContent($_POST['content']);
                $article->setCategoryId($_POST['category']);
                $article->setMiniature($filenameMin);
                $article->setPicture($filename);
                $id = $articleManager->insert($article);
                header('Location:/admin/article/' . $id);
            }

        }
        $active = "add";
        return $this->twig->render('Admin/AdminArticle/add.html.twig', ["active" => $active, 'errors' => $errors, 'content' => $_POST]); // traitement
    }


    public function logAdmin()
    {

        // Si admin connecter
        if (isset($_SESSION['admin'])) {
            header('Location: /admin/dashboard');
            exit();
        }

        $errorLogin = "";

        if (!empty($_POST)) {
            // Verifier si les données sont postées puis initialise le composant d'authentification.
            $auth = new \Model\AuthManager($this->getPdo());
            $admin = $auth->login($_POST['email']);


            if ($admin) {


                if (password_verify($_POST['password'], $admin->getPass())) {
                    //Si password ok, creation session admin avec lastname, firstname, et email.
                    $_SESSION['admin'] = [
                        "id" => $admin->getId(),
                        "lastname" => $admin->getlastname(),
                        "firstname" => $admin->getFirstname(),
                        "email" => $admin->getEmail(),
                        "message" => 'Vous êtes connecté'
                    ];

                    header('Location: /admin/dashboard');
                } else {
                    $errorLogin = 'Identifiant incorrect';
                }
            } else {
                $errorLogin = 'Identifiant incorrect';
            }


        }
        return $this->twig->render('Admin/logAdmin.html.twig', ["errorLogin" => $errorLogin]);


    }

//delete an article
    public function delete(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $articleManager->delete($id);

    }

// edit an article, change title, content, picture

    /**
     * @param int $id
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(int $id)
    {
        $errors = [];
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST["title"] == "") {
                $errors['title'] = "Le titre est requis !";
            }
            if ($_POST["content"] == "") {
                $errors['content'] = "Le contenu est requis !";
            }
            if (!empty($_FILES['image']['name'])) {
                $allowExtension = ['.jpg', '.jpeg', '.gif', '.png'];
                $maxSize = 1000000;
                $extension = strtolower(strrchr($_FILES['image']['name'], '.'));
                $size = $_FILES['image']['size'];

                if (!in_array($extension, $allowExtension)) {
                    $errors['image'] = 'Seuls les fichiers image .jpg, .jpeg, .gif et .png sont autorisés.';
                }
                if (($size > $maxSize) || ($size == 0)) {
                    $errors['image'] = 'Votre fichier est trop volumineux. Taille maximale autorisée : 1Mo.';
                }
                if(!$errors){
                    $filename = 'image-' . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], '../public/assets/images/' . $filename);
                    $article->setPicture($filename);
                }
            }
            if(!empty($_FILES['imageMin']['name'])) {
                $allowExtension = ['.jpg', '.jpeg', '.gif', '.png'];
                $maxSize = 1000000;
                $extensionMin = strtolower(strrchr($_FILES['imageMin']['name'], '.'));
                $sizeMin = $_FILES['imageMin']['size'];
                if (!in_array($extensionMin, $allowExtension)) {
                    $errors['imageMin'] = 'Seuls les fichiers image .jpg, .jpeg, .gif et .png sont autorisés.';
                }
                if (($sizeMin > $maxSize) || ($sizeMin == 0)) {
                    $errors['imageMin'] = 'Votre image miniature est trop volumineuse. Taille maximale autorisée : 1Mo.';
                }
                if(!$errors) {
                    $filenameMin = 'image-' . $_FILES['imageMin']['name'];
                    move_uploaded_file($_FILES['imageMin']['tmp_name'], '../public/assets/images/' . $filenameMin);
                    $article->setMiniature($filenameMin);
                }
            }

            if(empty($errors)) {
                $article->setTitle($_POST['title']);
                $article->setContent($_POST['content']);
                header('Location: /admin/article/' . $id);
                $id = $articleManager->update($article);
            }

        }
        return $this->twig->render('Admin/AdminArticle/edit.html.twig', ["article" => $article, 'errors' => $errors, 'content' => $_POST]);
    }



}







