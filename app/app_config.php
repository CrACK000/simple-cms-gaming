<?php

require_once 'app_global.php';

$dsn = 'mysql:host='.$_ENV["DB_HOST"].';port='.$_ENV["DB_PORT"].';dbname='.$_ENV["DB_NAME"].';charset=utf8mb4';
$usr = $_ENV['DB_USERNAME'];
$pwd = $_ENV['DB_PASSWORD'];