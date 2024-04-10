<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use Delight\Auth\Auth;
use Slim\PDO\Database;
use Delight\Auth\InvalidEmailException;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

$selectSettings = $pdo->select()
    ->from('settings')
    ->where('id', '=', 1);

$querySettings = $selectSettings->execute();
$dataSettings = $querySettings->fetch();

define( 'URL',  $dataSettings['HTTP_Secure'].$dataSettings['url']);

$selectStatement = $pdo->select()
    ->from('users')
    ->where('email', '=', $_GET['email']);

$stmt = $selectStatement->execute();
$data = $stmt->fetch();

if ($data['verified'] == 0) {

    try {
        $auth->admin()->deleteUserByEmail($_GET['email']);
        header('Location: '.URL.'/index.php');
    }
    catch (InvalidEmailException $e) {
        header('Location: '.URL.'/index.php');
    }

} else {
    header('Location: '.URL.'/index.php');
}