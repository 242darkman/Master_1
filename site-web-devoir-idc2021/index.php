<?php

require_once 'configTwig/bootstrap.php';
//var_export($twig);

use Miniframework\App\Http\Request;
use Miniframework\App\Http\Response;
use Miniframework\App\Controller\FrontController;
use Miniframework\App\AuthManager\AuthentificationManager;

// démarrage de la session
session_start();


$request = new Request($_GET, $_POST, $_SESSION, $_FILES, $_SERVER, $_REQUEST);
$authManager = new AuthentificationManager($request);
$response = new Response();
$frontController = new FrontController($request, $response, $authManager, $twig);
$frontController->execute();