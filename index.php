<?php

require_once 'core/Env.php';
Env::load();


require_once __DIR__ . '/core/Router.php';

// Create the router instance BEFORE loading routes
$router = new Router();

require_once __DIR__ . '/routes/api.php';

// Start resolving
$router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);