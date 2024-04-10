<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use \Delight\Auth\Auth;
use \Delight\Auth\InvalidEmailException;
use \Delight\Auth\InvalidPasswordException;
use \Delight\Auth\EmailNotVerifiedException;
use \Delight\Auth\TooManyRequestsException;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

if ($auth->isLoggedIn()) {
    header('Location: http://gamestroke.eu/index.php');
    exit;
}

if (isset($_POST['login'])) {
    try {
        $auth->login($_POST['email'], $_POST['password']);

        header('Location: http://gamestroke.eu/index.php');

    } catch (InvalidEmailException $e) {
        // wrong email address
        $notify = '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Nesprávna e-mailová adresa.</p>
                      </div>';
    } catch (InvalidPasswordException $e) {
        // wrong password
        $notify = '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Zlé heslo.</p>
                      </div>';
    } catch (EmailNotVerifiedException $e) {
        // email not verified
        $notify = '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>E-mail nie je overený.</p>
                      </div>';
    } catch (TooManyRequestsException $e) {
        // too many requests
        $notify = '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Príliš veľa požiadaviek.</p>
                      </div>';
    }
}

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

    $title = 'Prihlásiť sa';
    require 'template/template_headtags.php';

echo '
</head>
<body>';

    require 'template/template_navbar.php';

    echo '
    <div class="uk-container main-container">';

    if (!$auth->isLoggedIn()) {

        echo '
        <div uk-grid>
            <div class="uk-width-3-5@s driver">
                
                <div class="panel">
                    <div class="panel-head">
                        <p>'.$title.'</p>
                    </div>
                    <div class="panel-body">
                        
                        <div class="uk-padding">
                        
                            '.$notify.'
                        
                            <form class="uk-form-horizontal uk-margin-small" action="'.URL.'/login.php" method="post" accept-charset="utf-8">
                               
                                <div class="uk-margin">
                                    <label class="uk-form-label" for="email">Váš email</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" id="email" type="email" name="email" placeholder="@">
                                    </div>
                                </div>
                                
                                <div class="uk-margin">
                                    <label class="uk-form-label" for="pass">Heslo</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" id="pass" type="password" name="password" placeholder="*******">
                                    </div>
                                </div>
                                
                                <div class="uk-margin">
                                    <div class="uk-inline uk-float-right">
                                        <input class="button" type="submit" name="login" value="Prihlásiť sa">
                                    </div>
                                    <div class="uk-inline uk-float-left uk-margin-small-top">
                                        <a href="'.URL.'/forgot_password.php">Zabudol si heslo ?</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                            
                    </div>
                </div>
                
            </div>
            <div class="uk-width-2-5@s right-panel-driver">';
                require 'template/template_panels.php';
                echo '
            </div>
        </div>';

    }

    echo '
    </div>
    ';

require 'template/template_footer.php';

require 'template/template_scripts.php';

    echo '
</body>
</html>';