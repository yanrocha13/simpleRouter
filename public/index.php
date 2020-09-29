<?php

// load composer dependencies
use Demo\Router;

require '../vendor/autoload.php';
require '../database.php';
require '../renderer.php';


// Start the routing
Router::start();