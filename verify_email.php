<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use \Delight\Auth\Auth;
use \Delight\Auth\InvalidSelectorTokenPairException;
use \Delight\Auth\TokenExpiredException;
use \Delight\Auth\UserAlreadyExistsException;
use \Delight\Auth\TooManyRequestsException;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

header("Refresh:2; url=http://gamestroke.eu/login.php");

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

    $title = 'Overenie';
    require 'template/template_headtags.php';

echo '
</head>
<body>';

    try {
        $auth->confirmEmail($_GET['selector'], $_GET['token']);

        // email address has been verified
        echo '<div class="uk-flex uk-height-1-1 uk-width-1-1 uk-text-center" style="position: absolute;">
                <div class="uk-margin-auto uk-margin-auto-vertical uk-text-center">
                    <div class="uk-margin-small-bottom uk-border-circle uk-margin-auto" style="border: 3px solid #0f7ae5;width: 80px;height: 80px;">
                        <span style="color: #0f7ae5;padding-top: 12px;" uk-icon="icon: check; ratio: 3"></span>
                    </div>
                    <span style="color: #0f7ae5;">Overené.</span><br>
                    Teraz sa môžete prihlásiť, <a href="'.URL.'/login.php">Prihlásiť sa</a>
                </div>
              </div>';
    }
    catch (InvalidSelectorTokenPairException $e) {
        // invalid token
        echo '<div class="uk-flex uk-height-1-1 uk-width-1-1 uk-text-center" style="position: absolute;">
                <div class="uk-margin-auto uk-margin-auto-vertical uk-text-center">
                    <div class="uk-margin-small-bottom uk-border-circle uk-margin-auto" style="border: 3px solid #db3636;width: 80px;height: 80px;">
                        <span style="color: #db3636;padding-top: 12px;" uk-icon="icon: close; ratio: 3"></span>
                    </div>
                    <span style="color: #db3636;">Neplatný Token.</span>
                </div>
              </div>';
    }
    catch (TokenExpiredException $e) {
        // token expired
        echo '<div class="uk-flex uk-height-1-1 uk-width-1-1 uk-text-center" style="position: absolute;">
                <div class="uk-margin-auto uk-margin-auto-vertical uk-text-center">
                    <div class="uk-margin-small-bottom uk-border-circle uk-margin-auto" style="border: 3px solid #db3636;width: 80px;height: 80px;">
                        <span style="color: #db3636;padding-top: 12px;" uk-icon="icon: close; ratio: 3"></span>
                    </div>
                    <span style="color: #db3636;">Token vypršal.</span>
                </div>
              </div>';
    }
    catch (UserAlreadyExistsException $e) {
        // email address already exists
        echo '<div class="uk-flex uk-height-1-1 uk-width-1-1 uk-text-center" style="position: absolute;">
                <div class="uk-margin-auto uk-margin-auto-vertical uk-text-center">
                    <div class="uk-margin-small-bottom uk-border-circle uk-margin-auto" style="border: 3px solid #db3636;width: 80px;height: 80px;">
                        <span style="color: #db3636;padding-top: 12px;" uk-icon="icon: close; ratio: 3"></span>
                    </div>
                    <span style="color: #db3636;">Emailová adresa už existuje.</span>
                </div>
              </div>';
    }
    catch (TooManyRequestsException $e) {
        // too many requests
        echo '<div class="uk-flex uk-height-1-1 uk-width-1-1 uk-text-center" style="position: absolute;">
                <div class="uk-margin-auto uk-margin-auto-vertical uk-text-center">
                    <div class="uk-margin-small-bottom uk-border-circle uk-margin-auto" style="border: 3px solid #db3636;width: 80px;height: 80px;">
                        <span style="color: #db3636;padding-top: 12px;" uk-icon="icon: close; ratio: 3"></span>
                    </div>
                    <span style="color: #db3636;">Príliš veľa požiadaviek.</span>
                </div>
              </div>';
    }

    require 'template/template_scripts.php';

    echo '
</body>
</html>';