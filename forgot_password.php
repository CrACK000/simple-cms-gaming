<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use Delight\Auth\Auth;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\ResetDisabledException;
use Delight\Auth\EmailNotVerifiedException;
use Delight\Auth\TooManyRequestsException;
use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

function send_resetpwd_email($for, $selectorcode, $tokencode) {

    require 'app/app_config.php';

    $slimpdo    = new Database($dsn, $usr, $pwd);

    $selectSettings_2 = $slimpdo->select()
        ->from('settings')
        ->where('id', '=', 1);

    $querySettings_2 = $selectSettings_2->execute();
    $dataSettings_2 = $querySettings_2->fetch();

    $mail = new PHPMailer(true);

    $url        = URL.'/reset_password.php?selector=' . \urlencode($selectorcode) . '&token=' . \urlencode($tokencode);

    //Server settings
    $mail->SMTPDebug    = 0;                                    // enable verbose debug output
    $mail->isSMTP();
    $mail->Host         = $dataSettings_2['SMTP_Host'];
    $mail->SMTPAuth     = true;                                 // Enable SMTP authentication
    $mail->Username     = $dataSettings_2['SMTP_Username'];     // SMTP username
    $mail->Password     = $dataSettings_2['SMTP_Password'];     // SMTP password
    $mail->SMTPSecure   = 'ssl';                                // Enable TLS encryption, `ssl` also accepted
    $mail->Port         = $dataSettings_2['SMTP_Port'];         // TCP port to connect to

    //Recipients
    $mail->setFrom($dataSettings_2['SMTP_Username'], $dataSettings_2['title']);
    $mail->addAddress($for);

    //Content
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);                                // Set email format to HTML
    $mail->Subject = 'Obnoviť heslo';

    $mail->Body    = '<!DOCTYPE html>';
    $mail->Body   .= '<html>';
    $mail->Body   .= '<head>';
    $mail->Body   .= '<title>Obnoviť heslo</title>';
    $mail->Body   .= '<style type="text/css">body{background:#ececec;font-family: Calibri;font-size:15px;margin:0px;padding:0px;color:#5a5a5a}.container{width:650px;margin:auto;border:1px solid #d6d6d6;margin-top:10px}.head{background:#1e87f0;width:100%}.head .body{padding:15px 20px;font-size:18px;font-weight:bold;color:#ffffff}.textbody{background:#ffffff;padding:60px 120px;text-align:center}.textbody h3{margin-top:0px;margin-bottom:35px;color:#1e87f0}.textbody .marginbutton{margin-bottom:50px;margin-top:50px}.textbody .marginbutton .button{background:#1e87f0;padding:12px 18px;color:white;border:none;cursor:pointer;text-decoration:none}.textbody .dark{font-size:11px;color:#a0a0a0}.textbody .dark a{color:#a0a0a0;text-decoration:underline}</style>';
    $mail->Body   .= '</head>';
    $mail->Body   .= '<body>';
    $mail->Body   .= '<table class="container" cellspacing="0" cellpadding="0">';
    $mail->Body   .= '<tr class="head">';
    $mail->Body   .= '<td class="body">'.$dataSettings_2['url'].'</td>';
    $mail->Body   .= '</tr>';
    $mail->Body   .= '<tr>';
    $mail->Body   .= '<td class="textbody">';
    $mail->Body   .= '<h3>Ahoj '.$for.',</h3>';
    $mail->Body   .= '<p>Požiadali ste o obnovenie hesla. Ak chcete obnoviť heslo, postupujte podľa nižšie uvedeného odkazu.</p>';
    $mail->Body   .= '<p class="marginbutton"><a target="_blank" class="button" href="'.$url.'">Obnoviť heslo</a></p>';
    $mail->Body   .= '<p class="dark">Alebo kliknite na tento odkaz: <br> <a target="_blank" href="'.$url.'">'.$url.'</a></p>';
    $mail->Body   .= '<p class="dark">Ak ste nepožiadali o zmenu hesla, ignorujte tento e-mail.</p>';
    $mail->Body   .= '<p class="dark">&copy; '.date("Y",time()).' '.$dataSettings_2['url'].'. Všetky práva vyhradené.</p>';
    $mail->Body   .= '</td>';
    $mail->Body   .= '</tr>';
    $mail->Body   .= '</table>';
    $mail->Body   .= '</body>';
    $mail->Body   .= '</html>';

    $mail->AltBody = 'Ahoj '.$for.',
    
Požiadali ste o obnovenie hesla. Ak chcete obnoviť heslo, postupujte podľa nižšie uvedeného odkazu.

Prejdite na túto adresu:
 '.$url.'
 
 Ak ste nepožiadali o zmenu hesla, ignorujte tento e-mail.
 

COPYRIGHT 2017 '.$dataSettings_2['url'].'. Všetky práva vyhradené.';

    $mail->send();

    $notify = '<div class="uk-alert-success" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            <p>Email s novým heslo bol odoslaný.</p>
          </div>';

}

if ($auth->isLoggedIn()) {
    header('Location: http://gamestroke.eu/index.php');
    exit;
}

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

$title = 'Zabudnuté heslo';
require 'template/template_headtags.php';

echo '
</head>
<body>';

require 'template/template_navbar.php';

echo '
    <div class="uk-container main-container">';

if (!$auth->isLoggedIn()) {

    if (isset($_POST['submit'])) {
        try {
            $auth->forgotPassword($_POST['email'], function ($selector, $token) {
                // send `$selector` and `$token` to the user (e.g. via email)
                send_resetpwd_email($_POST['email'], $selector, $token);
            });

            // request has been generated
        }
        catch (InvalidEmailException $e) {
            // invalid email address
            $notify = '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Neplatná emailová adresa.</p>
                      </div>';
        }
        catch (EmailNotVerifiedException $e) {
            // email not verified
            $notify = '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>E-mail nie je overený.</p>
                      </div>';
        }
        catch (ResetDisabledException $e) {
            // password reset is disabled
            $notify = '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Obnovenie hesla je zakázané.</p>
                      </div>';
        }
        catch (TooManyRequestsException $e) {
            // too many requests
            $notify = '<div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p>Príliš veľa požiadaviek.</p>
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
                        
                            <form class="uk-form-horizontal uk-margin-small" action="'.URL.'/forgot_password.php" method="post" accept-charset="utf-8">
                                
                                <div class="uk-margin">
                                    <label class="uk-form-label" for="email">Váš email</label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input" id="email" type="email" name="email" placeholder="@">
                                    </div>
                                </div>
                                
                                <div class="uk-margin uk-text-right">
                                    <div class="uk-inline">
                                        <input class="button" type="submit" name="submit" value="Odoslať">
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