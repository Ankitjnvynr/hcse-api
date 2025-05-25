<?php
require_once __DIR__ . '/../controllers/UserController.php';

$router->add('GET', '/api/users', ['UserController', 'getAllUsers']);
$router->add('GET', '/api/users/{id}', ['UserController', 'getUserById']);
$router->add('POST', '/api/users', ['UserController', 'createUser']);
$router->add('PUT', '/api/users/{id}', ['UserController', 'updateUser']);
$router->add('DELETE', '/api/users/{id}', ['UserController', 'deleteUser']);


$router->add('POST', '/api/register', ['AuthController', 'register']);
$router->add('POST', '/api/login', ['AuthController', 'login']);