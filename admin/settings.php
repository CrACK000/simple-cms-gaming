<?php

require '../vendor/autoload.php';
require '../app/app_config.php';

use Delight\Auth\Auth;
use Delight\Auth\Role;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

if ($auth->hasAnyRole(Role::SUBSCRIBER, Role::SUPER_ADMIN, Role::SUPER_EDITOR, Role::SUPER_MODERATOR, Role::TRANSLATOR)) {

    echo '
    <!DOCTYPE html>
    <html lang="sk">
    <head>';

    $title = 'Nastavenia';
    require '../template/template_headtags.php';

    echo'
    </head>
    <body>';

    require '../template/template_navbar.php';

    echo '
        <div class="uk-container main-container">
        
            <div uk-grid>
                <div class="uk-width-3-5@s driver">
                
                    <div class="panel">
                    
                        <div class="panel-head">
                            <p>Administrácia - ' . $title . '</p>
                        </div>
                        <div class="panel-body">
                        
                            <div class="uk-padding-small">';

                                $selectStatementSettings = $pdo->select()
                                    ->from('settings')
                                    ->where('id', '=', 1);

                                $stmtSettings = $selectStatementSettings->execute();
                                $dataSettings = $stmtSettings->fetch();

                                if (isset($_POST['updateSettings'])) {
                                    $updateStatement = $pdo->update(array('title'           => $_POST['settingTitle']))
                                                              ->set(array('HTTP_Secure'     => $_POST['settingSecure']))
                                                              ->set(array('url'             => $_POST['settingURL']))
                                                              ->set(array('SMTP_Host'       => $_POST['SMTP_Host']))
                                                              ->set(array('SMTP_Username'   => $_POST['SMTP_Username']))
                                                              ->set(array('SMTP_Password'   => $_POST['SMTP_Password']))
                                                              ->set(array('SMTP_Port'       => $_POST['SMTP_Port']))
                                                              ->table('settings')
                                                              ->where('id', '=', 1);

                                    $affectedRows = $updateStatement->execute();

                                    if ($affectedRows){
                                        header('Location: '.URL.'/admin/settings.php');
                                    }
                                }

                                echo '
                                <form method="post" class="uk-form-stacked uk-width-1-2 uk-text-center uk-margin-auto">
                                    <div class="uk-margin-small">
                                        <label class="uk-form-label" for="t">Title</label>
                                        <div class="uk-form-controls">
                                            <input class="uk-input" name="settingTitle" id="t" type="text" value="'.$dataSettings['title'].'" placeholder="example.com">
                                        </div>
                                    </div>
                                    <div class="uk-margin-small">
                                        <label class="uk-form-label" for="s">Bezpečnosť (SSL)</label>
                                        <div class="uk-form-controls">
                                            <input class="uk-input" name="settingSecure" id="s" type="text" value="'.$dataSettings['HTTP_Secure'].'" placeholder="http://">
                                        </div>
                                    </div>
                                    <div class="uk-margin-small">
                                        <label class="uk-form-label" for="u">URL Adresa</label>
                                        <div class="uk-form-controls">
                                            <input class="uk-input" name="settingURL" id="u" type="text" value="'.$dataSettings['url'].'" placeholder="http://">
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="uk-margin-small">
                                        <label class="uk-form-label" for="h">SMTP Host</label>
                                        <div class="uk-form-controls">
                                            <input class="uk-input" name="SMTP_Host" id="h" type="text" value="'.$dataSettings['SMTP_Host'].'">
                                        </div>
                                    </div>
                                    
                                    <div class="uk-margin-small">
                                        <label class="uk-form-label" for="us">SMTP Username</label>
                                        <div class="uk-form-controls">
                                            <input class="uk-input" name="SMTP_Username" id="us" type="text" value="'.$dataSettings['SMTP_Username'].'">
                                        </div>
                                    </div>
                                    
                                    <div class="uk-margin-small">
                                        <label class="uk-form-label" for="pw">SMTP Password</label>
                                        <div class="uk-form-controls">
                                            <input class="uk-input" name="SMTP_Password" id="pw" type="text" value="'.$dataSettings['SMTP_Password'].'">
                                        </div>
                                    </div>
                                    
                                    <div class="uk-margin-small">
                                        <label class="uk-form-label" for="prt">SMTP Port</label>
                                        <div class="uk-form-controls">
                                            <input class="uk-input" name="SMTP_Port" id="prt" type="text" value="'.$dataSettings['SMTP_Port'].'">
                                        </div>
                                    </div>
                                    
                                    <div class="uk-margin-small">
                                        <button class="button" name="updateSettings" type="submit">Uložiť</button>
                                    </div>
                                </form>
                            
                            </div>
                        
                        </div>
                        
                    </div>
                
                </div>
                <div class="uk-width-2-5@s right-panel-driver">';
                require '../template/template_panels.php';
                echo '
                </div>
            </div>
            
        </div>';

    require '../template/template_scripts.php';

    echo '
    </body>
    </html>';

} else {

    $selectSettings = $pdo->select()
        ->from('settings')
        ->where('id', '=', 1);

    $querySettings = $selectSettings->execute();
    $dataSettings = $querySettings->fetch();

    define( 'URL',  $dataSettings['HTTP_Secure'].$dataSettings['url']);

    header('Location: '.URL.'/index.php');
    exit;
}

