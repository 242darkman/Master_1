<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

$loader = new FilesystemLoader(__DIR__ . '/../src/App/View');

$twig = new Environment($loader);