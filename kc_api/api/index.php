<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/db.php';
require __DIR__ . '/../Models/Session.php';

$app = new \Slim\App;

require __DIR__ . '/../src/routes/students.php';
require __DIR__ . '/../src/routes/auth.php';
require __DIR__ . '/../auth/middleware.php';

$app->run();