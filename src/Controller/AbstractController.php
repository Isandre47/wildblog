<?php
namespace Controller;

use App\Session;
use Twig_Loader_Filesystem;
use Twig_Environment;
use App\Connection;

/**
 *
 */
abstract class AbstractController
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     *  Initializes this class.
     */


    protected $session;


    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem(APP_VIEW_PATH);
        $this->twig = new Twig_Environment(
            $loader,
            [
                'cache' => !APP_DEV,
                'debug' => APP_DEV,
            ]
        );
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addExtension(new \Twig_Extensions_Extension_Text());
        $connection = new Connection();
        $this->pdo = $connection->getPdoConnection();
        // Initialise l'objet et dans la class Session.php fait un session-start dans la fonction construct
        $this->session = new Session();
    }

    /**
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

    public function verifyAdmin()
    {
        if(!isset($_SESSION['admin'])){
            header('Location: /admin/logAdmin');
            exit();
        }
    }

    public function verifyUser()
    {
        if(isset($_SESSION['user'])){
            header('Location: \login');
            exit;
        }
    }

    public function isLogged()
    {
        return (isset($_SESSION['user']));
    }

    public function isLoggedAdmin()
    {
        return (isset($_SESSION['admin']));
    }
    
}
