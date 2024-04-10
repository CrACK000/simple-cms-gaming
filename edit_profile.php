<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use Delight\Auth\Auth;
use Delight\Auth\NotLoggedInException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

$selectStatement = $pdo->select()
    ->from('users')
    ->where('id', '=', $auth->getUserId());

$stmt = $selectStatement->execute();
$data = $stmt->fetch();

if ($_POST['edit_basic_info']){

    $alredyUsernameQuery = $pdo->select()
        ->from('users')
        ->where('username', '=', $_POST['username']);

    $alredyUsernamStmt = $alredyUsernameQuery->execute();


    if ($_POST['username'] != $data['username']) {

        if( !$alredyUsernamStmt->rowCount() > 0 ) {

            $updateStatement = $pdo->update(array('username' => $_POST['username']))
                ->set(array('steam' => $_POST['steam']))
                ->set(array('skype' => $_POST['skype']))
                ->table('users')
                ->where('id', '=', $auth->getUserId());
            $affectedRows = $updateStatement->execute();

        } else {
            // user already exists
            $edit_basic_info_errors = '<div class="uk-alert-danger" uk-alert>
                                    <a class="uk-alert-close" uk-close></a>
                                    <p>Používateľ už existuje.</p>
                               </div>';
        }

    } else {
        $updateStatement = $pdo->update(array('steam' => $_POST['steam']))
            ->set(array('skype' => $_POST['skype']))
            ->table('users')
            ->where('id', '=', $auth->getUserId());
        $affectedRows = $updateStatement->execute();
    }

    if ($affectedRows) {
        $edit_basic_info_errors = '<div class="uk-alert-success" uk-alert>
                                    <a class="uk-alert-close" uk-close></a>
                                    <p>Váš profil bol úspešne upravený.</p>
                               </div>';
    } else {
        $edit_basic_info_errors = '<div class="uk-alert-danger" uk-alert>
                                    <a class="uk-alert-close" uk-close></a>
                                    <p>Nastala chyba.</p>
                               </div>';
    }

}

if ($_POST['edit_password']) {
    if ($_POST['newPassword'] == $_POST['renewPassword']) {
        try {
            $auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);

            $edit_password_errors = '<div class="uk-alert-success" uk-alert>
                                            <a class="uk-alert-close" uk-close></a>
                                            <p>Vaše heslo bolo úspešne zmenené.</p>
                                       </div>';

        } catch (NotLoggedInException $e) {
            // not logged in
            $edit_password_errors = '<div class="uk-alert-danger" uk-alert>
                                            <a class="uk-alert-close" uk-close></a>
                                            <p>Neprihlásený.</p>
                                       </div>';
        } catch (InvalidPasswordException $e) {
            // invalid password(s)
            $edit_password_errors = '<div class="uk-alert-danger" uk-alert>
                                            <a class="uk-alert-close" uk-close></a>
                                            <p>Neplatné heslo/á.</p>
                                       </div>';
        } catch (TooManyRequestsException $e) {
            // too many requests
            $edit_password_errors = '<div class="uk-alert-danger" uk-alert>
                                            <a class="uk-alert-close" uk-close></a>
                                            <p>Príliš veľa požiadaviek.</p>
                                       </div>';
        }
    } else {
        $edit_password_errors = '<div class="uk-alert-danger" uk-alert>
                                            <a class="uk-alert-close" uk-close></a>
                                            <p>Vaše heslá sa nezhodujú.</p>
                                       </div>';
    }
}

if (!$auth->isLoggedIn()) {
    header('Location: http://gamestroke.eu/index.php');
    exit;
}

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

$title = 'Upraviť profil - '.$data['username'];
require 'template/template_headtags.php';

echo'
</head>
<body>';

require 'template/template_navbar.php';

echo '
    <div class="uk-container main-container">
    
        <div uk-grid>
            <div class="uk-width-3-5@s driver">
                
                <div class="panel">
                    <div class="panel-head">
                        <p>'.$title.'</p>
                    </div>
                    <div class="panel-body">
                        
                        <div class="uk-padding-small">
                        
                            <div class="uk-text-center uk-margin-medium-bottom">
                            
                                <div class="uk-inline">
                        
                                    <div id="imgAvatar">
                                        <img class="uk-border-circle uk-margin-bottom" style="width: 150px;height: 150px;" src="'.URL.'/uploads/avatars/'.$data['avatar'].'">
                                    </div>
                                    
                                    <form id="uploadAvatar" method="POST" class="uk-form-stacked">
                                        <div class="js-upload" uk-form-custom>
                                            <input type="file" name="userImage" id="upload_input" accept="image/*">
                                            <input type="submit" id="upload_submit" style="display: none">
                                            <button class="button"><span class="uk-margin-small-right" uk-icon="icon: cloud-upload"></span> Nahrať</button>
                                        </div>
                                    </form>
                                
                                </div>
                            
                            </div>
                        
                            <div uk-grid>
                            
                                <div class="uk-width-1-2">
                            
                                    <h4 class="uk-margin-remove colorko-txt">Základné informácie <span class="uk-float-right colorko-txt" uk-icon="icon: info" title="Po úprave profilu sa musíte odhlásiť a prihlásiť sa, aby sa zobrazili zmeny." uk-tooltip></span></h4>
                            
                                    <hr class="uk-margin-small">
                                
                                    '.$edit_basic_info_errors.'
                            
                                    <form class="uk-form-stacked" method="post" action="'.URL.'/edit_profile.php">
                
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-email">E-mail</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="form-email" type="email" name="email" value="'.$data['email'].'" placeholder="E-mail" disabled>
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-username">Užívateľské meno</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="form-username" type="text" name="username" value="'.$data['username'].'" placeholder="Užívateľské meno">
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-steam">Steam profile ID</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="form-steam" type="text" name="steam" value="'.$data['steam'].'" placeholder="napr. 76561198395639645">
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-skype">Skype</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="form-skype" type="text" name="skype" value="'.$data['skype'].'" placeholder="skype.name">
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <input class="button" type="submit" name="edit_basic_info" value="Zmeniť">
                                        </div>
                                    
                                    </form>
                                
                                </div>
                                
                                <div class="uk-width-1-2">
                                
                                    <h4 class="uk-margin-remove colorko-txt">Zmeniť heslo</h4>
                            
                                    <hr class="uk-margin-small">
                                    
                                    '.$edit_password_errors.'
                                    
                                    <form class="uk-form-stacked" method="post" action="'.URL.'/edit_profile.php">
                
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-oldpass">Staré heslo</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="form-oldpass" type="password" name="oldPassword" placeholder="Staré heslo">
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-newpass">Nové heslo</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="form-newpass" type="password" name="newPassword" placeholder="Nové heslo">
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-renewpass">Zopakujte nové heslo</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="form-renewpass" type="password" name="renewPassword" placeholder="Zopakujte nové heslo">
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <input class="button" type="submit" name="edit_password" value="Zmeniť heslo">
                                        </div>
                                    
                                    </form>
                                    
                                </div>
                            
                            </div>
                            
                        </div>
                            
                    </div>
                </div>
                
            </div>
            <div class="uk-width-2-5@s right-panel-driver">';
                require 'template/template_panels.php';
                echo '
            </div>
        </div>
        
    </div>';

require 'template/template_footer.php';

require 'template/template_scripts.php';

echo '
    <script type="text/javascript">
    
        $(document).ready(function (e) {
            
            document.getElementById("upload_input").onchange = function() {

                document.getElementById("upload_submit").click();
    
            };
            
            $("#uploadAvatar").on("submit",(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "app/app_upload_avatar.php",
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data)
                    {
                    $("#imgAvatar").html(data);
                    },
                    error: function() 
                    {
                    }           
               });
            }));
        });
    </script>
    
</body>
</html>';