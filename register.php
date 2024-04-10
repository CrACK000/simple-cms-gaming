<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use Delight\Auth\Auth;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UserAlreadyExistsException;
use PHPMailer\PHPMailer\PHPMailer;
//use \PHPMailer\PHPMailer\Exception;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

/*function send_verify_email($for, $forname, $selectorcode, $tokencode) {

    $mail = new PHPMailer(true);

    $url        = 'http://gamestroke.eu/verify_email.php?selector=' . \urlencode($selectorcode) . '&token=' . \urlencode($tokencode);
    $deleteurl  = 'http://gamestroke.eu/delete_user.php?email='.$for;

    //Server settings
    $mail->SMTPDebug    = 0;                                  // enable verbose debug output
    $mail->isSMTP();
    $mail->Host         = 'smtp.websupport.sk';
    $mail->SMTPAuth     = true;                               // Enable SMTP authentication
    $mail->Username     = 'admin@pallax.systems';     // SMTP username
    $mail->Password     = 'WU7ZSZTX0I';     // SMTP password
    $mail->SMTPSecure   = 'ssl';                              // Enable TLS encryption, `ssl` also accepted
    $mail->Port         = 465;         // TCP port to connect to

    //Recipients
    $mail->setFrom('admin@pallax.systems', 'Gamestroke');
    $mail->addAddress($for, $forname);

    //Content
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);                                // Set email format to HTML
    $mail->Subject = 'Overte svoj e-mail';

    $mail->Body    = '<!DOCTYPE html>';
    $mail->Body   .= '<html>';
    $mail->Body   .= '<head>';
    $mail->Body   .= '<title>Overte svoj e-mail</title>';
    $mail->Body   .= '<style type="text/css">body{background:#ececec;font-family: Calibri;font-size:15px;margin:0px;padding:0px;color:#5a5a5a}.container{width:650px;margin:auto;border:1px solid #d6d6d6;margin-top:10px}.head{background:#1e87f0;width:100%}.head .body{padding:15px 20px;font-size:18px;font-weight:bold;color:#ffffff}.textbody{background:#ffffff;padding:60px 120px;text-align:center}.textbody h3{margin-top:0px;margin-bottom:35px;color:#1e87f0}.textbody .marginbutton{margin-bottom:50px;margin-top:50px}.textbody .marginbutton .button{background:#1e87f0;padding:12px 18px;color:white;border:none;cursor:pointer;text-decoration:none}.textbody .dark{font-size:11px;color:#a0a0a0}.textbody .dark a{color:#a0a0a0;text-decoration:underline}</style>';
    $mail->Body   .= '</head>';
    $mail->Body   .= '<body>';
    $mail->Body   .= '<table class="container" cellspacing="0" cellpadding="0">';
    $mail->Body   .= '<tr class="head">';
    $mail->Body   .= '<td class="body">gamestroke.eu</td>';
    $mail->Body   .= '</tr>';
    $mail->Body   .= '<tr>';
    $mail->Body   .= '<td class="textbody">';
    $mail->Body   .= '<h3>Ahoj '.$forname.'! Prosím overte svoju e-mailovú adresu!</h3>';
    $mail->Body   .= '<p>Niekto (dúfajme, že vy) použil tento e-mail na adrese <strong>gamestroke.eu</strong>. Kliknite na tlačidlo nižšie a overte si vlastníctvo tohto účtu.</p>';
    $mail->Body   .= '<p>Toto overenie je pre overovanie účtu <strong>'.$forname.'</strong></p>';
    $mail->Body   .= '<p class="marginbutton"><a target="_blank" class="button" href="'.$url.'">Overte svoj e-mail</a></p>';
    $mail->Body   .= '<p class="dark">Alebo kliknite na tento odkaz: <br> <a target="_blank" href="'.$url.'">'.$url.'</a></p>';
    $mail->Body   .= '<p class="dark" style="margin-bottom: 20px;margin-top: 20px;">Ak ste sa nezaregistrovali na adrese gamestroke.eu, <a href="'.$deleteurl.'">kliknite na toto</a>.</p>';
    $mail->Body   .= '<p class="dark">&copy; '.date("Y",time()).' gamestroke.eu. Všetky práva vyhradené.</p>';
    $mail->Body   .= '</td>';
    $mail->Body   .= '</tr>';
    $mail->Body   .= '</table>';
    $mail->Body   .= '</body>';
    $mail->Body   .= '</html>';

    $mail->AltBody = 'Ahoj '.$forname.'! Prosím overte svoju e-mailovú adresu!
    
Niekto (dúfajme, že vy) použil tento e-mail na adrese gamestroke.eu. Kliknite na tlačidlo nižšie a overte si vlastníctvo tohto účtu.

Toto overenie je pre overovanie účtu '.$forname.'

Prejdite na túto adresu:
 '.$url.'
 
Ak ste sa nezaregistrovali na adrese gamestroke.eu, prejdite na túto adresu:
 '.$deleteurl.'

COPYRIGHT '.date("Y",time()).' gamestroke.eu. Všetky práva vyhradené.';

    $mail->send();

    $notify =  '<div class="uk-alert-success" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            <p>Overovací e-mail bol odoslaný.</p>
          </div>';

}*/

if ($auth->isLoggedIn()) {
    header('Location: http://gamestroke.eu/index.php');
    exit;
}

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

    $title = 'Registrácia';
    require 'template/template_headtags.php';

echo '
</head>
<body>';

    require 'template/template_navbar.php';

    echo '
    <div class="uk-container main-container">';

    if (!$auth->isLoggedIn()) {

        if (isset($_POST['register'])) {
            if ($_POST['password'] == $_POST['repassword']) {
                try {

                    $userId = $auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
                        // send `$selector` and `$token` to the user (e.g. via email)

                        echo '<script type="text/javascript">window.location.replace("http://gamestroke.eu/verify_email.php?selector=' . \urlencode($selector) . '&token=' . \urlencode($token).'");</script>';
                        exit;

                    });

                    // we have signed up a new user with the ID `$userId`
                } catch (InvalidEmailException $e) {
                    // invalid email address
                    $notify = '<div class="uk-alert-danger" uk-alert>
                            <a class="uk-alert-close" uk-close></a>
                            <p>Neplatná emailová adresa.</p>
                          </div>';
                } catch (InvalidPasswordException $e) {
                    // invalid password
                    $notify = '<div class="uk-alert-danger" uk-alert>
                            <a class="uk-alert-close" uk-close></a>
                            <p>Neplatné heslo.</p>
                          </div>';
                } catch (UserAlreadyExistsException $e) {
                    // user already exists
                    $notify = '<div class="uk-alert-danger" uk-alert>
                            <a class="uk-alert-close" uk-close></a>
                            <p>Používateľ už existuje.</p>
                          </div>';
                } catch (TooManyRequestsException $e) {
                    // too many requests
                    $notify = '<div class="uk-alert-danger" uk-alert>
                            <a class="uk-alert-close" uk-close></a>
                            <p>Príliš veľa požiadaviek.</p>
                          </div>';
                }
            } else {
                $notify = '<div class="uk-alert-danger" uk-alert>
                            <a class="uk-alert-close" uk-close></a>
                            <p>Heslá sa nezhodujú.</p>
                          </div>';
            }
        }

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
                        
                            <form class="uk-form-horizontal uk-margin-small" action="'.URL.'/register.php" method="post" accept-charset="utf-8">
                            
                            
                                <div class="uk-margin">
                                    <label class="uk-form-label" for="email">Váš email</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" id="email" type="email" name="email" placeholder="@">
                                    </div>
                                </div>
                                
                                <div class="uk-margin">
                                    <label class="uk-form-label" for="name">Nick / Meno</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" id="name" type="text" name="username" placeholder="James Down">
                                    </div>
                                </div>
                                
                                <div class="uk-margin">
                                    <label class="uk-form-label" for="pass">Heslo</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" id="pass" type="password" name="password" placeholder="*******">
                                    </div>
                                </div>
                                
                                <div class="uk-margin">
                                    <label class="uk-form-label" for="repass">Zopakujte heslo</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" id="repass" type="password" name="repassword" placeholder="*******">
                                    </div>
                                </div>
                                
                                <div class="uk-margin uk-text-center">
                                    <small>Registráciou súhlasíte s podminkami ktoré najdete <a href="#">TU</a>. </small>
                                </div>
                                
                                <div class="uk-margin uk-text-right">
                                    <div class="uk-inline">
                                        <input class="button" type="submit" name="register" value="Registrovať sa">
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