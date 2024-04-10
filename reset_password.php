<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use \Delight\Auth\Auth;
use \Delight\Auth\InvalidSelectorTokenPairException;
use \Delight\Auth\TokenExpiredException;
use \Delight\Auth\ResetDisabledException;
use \Delight\Auth\TooManyRequestsException;
use \Delight\Auth\InvalidPasswordException;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

if (!$_GET['selector']) {
    header('Location: http://gamestroke.eu/index.php');
    exit;
} if (!$_GET['token']) {
    header('Location: http://gamestroke.eu/index.php');
    exit;
}
if ($auth->isLoggedIn()) {
    header('Location: http://gamestroke.eu/index.php');
    exit;
}

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

$title = 'Obnoviť heslo';
require 'template/template_headtags.php';

echo '
</head>
<body>';

require 'template/template_navbar.php';

echo '
    <div class="uk-container uk-container-large">
    
    <ul class="uk-breadcrumb uk-margin-bottom">
        <li><span>Obnoviť heslo</span></li>
    </ul>';

if (!$auth->isLoggedIn()) {

    if (isset($_POST['reset'])) {
        try {
            $auth->resetPassword($_GET['selector'], $_GET['token'], $_POST['password']);

            // password has been reset
            echo '<div class="uk-alert-success" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Heslo bolo obnovené.</p>
                      </div>';

        }
        catch (InvalidSelectorTokenPairException $e) {
            // invalid token
            echo '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Neplatný Token.</p>
                      </div>';
        }
        catch (TokenExpiredException $e) {
            // token expired
            echo '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Token vypršal.</p>
                      </div>';
        }
        catch (ResetDisabledException $e) {
            // password reset is disabled
            echo '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Obnovenie hesla je zakázané.</p>
                      </div>';
        }
        catch (InvalidPasswordException $e) {
            // invalid password
            echo '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Nesprávne heslo.</p>
                      </div>';
        }
        catch (TooManyRequestsException $e) {
            // too many requests
            echo '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Príliš veľa požiadaviek.</p>
                      </div>';
        }
    }

    echo '
        <form action="" method="post" accept-charset="utf-8">
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                    <input class="uk-input" type="password" name="password" placeholder="Nové heslo">
                </div>
            </div>
            <div class="uk-margin">
                <div class="uk-inline">
                    <input class="uk-button uk-button-primary" type="submit" name="reset" value="Resetovať">
                </div>
            </div>
        </form>';

}

echo '
    </div>
    ';

require 'template/template_footer.php';

require 'template/template_scripts.php';

echo '
</body>
</html>';