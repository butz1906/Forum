<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
//on importe les namespaces l'autoloader et du router
use App\AutoLoader;
use App\Core\Router;

//on inclut l'autoloader
include '../Autoloader.php';
Autoloader::register();

// vérifie si la requête est à la racine du site
if ($_SERVER['REQUEST_URI'] === '/') {
    // redirige vers la page du dossier public
    header('Location:index.php');
    exit();
}

//on instancie le routeur
$route = new Router();

//on lance l'application
$route->routes();
