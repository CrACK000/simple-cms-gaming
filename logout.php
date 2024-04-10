<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use Delight\Auth\Auth;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

if (!$auth->isLoggedIn()) {
    header('Location: http://gamestroke.eu/index.php');
    exit;
}

$auth->logOutAndDestroySession();

header("Refresh:2; url=http://gamestroke.eu/index.php");

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

    $title = 'Odhlasovanie';
    require 'template/template_headtags.php';

echo '
</head>
<body>';

    echo '
    <div class="uk-flex uk-height-1-1 uk-width-1-1 uk-text-center" style="position: absolute;">
        <div class="uk-margin-auto uk-margin-auto-vertical uk-text-center">
            <div class="uk-margin-small-bottom" uk-spinner></div>
            <div>Odhlasovanie</div>
        </div>
    </div>
    ';

    require 'template/template_scripts.php';

    echo '
</body>
</html>';