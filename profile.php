<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use Delight\Auth\Auth;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

$selectStatementProfile = $pdo->select()
    ->from('users')
    ->where('id', '=', $_GET['user']);

$stmtProfile = $selectStatementProfile->execute();
$dataProfile = $stmtProfile->fetch();

if (!$_GET['user']){
    header('Location: http://gamestroke.eu/index.php');
    exit;
} elseif (!$dataProfile['id']){
    header('Location: http://gamestroke.eu/index.php');
    exit;
}

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

    $title = 'Profil - '.$dataProfile['username'];
    require 'template/template_headtags.php';

echo'
</head>
<body>';

    if ($dataProfile['roles_mask'] <= 1) {
        $role = 0;
    } elseif ($dataProfile['roles_mask'] <= 2) {
        $role = 1;
    } elseif ($dataProfile['roles_mask'] <= 4) {
        $role = 2;
    } elseif ($dataProfile['roles_mask'] <= 8) {
        $role = 3;
    } elseif ($dataProfile['roles_mask'] <= 16) {
        $role = 4;
    } elseif ($dataProfile['roles_mask'] <= 32) {
        $role = 5;
    } elseif ($dataProfile['roles_mask'] <= 64) {
        $role = 6;
    } elseif ($dataProfile['roles_mask'] <= 128) {
        $role = 7;
    } elseif ($dataProfile['roles_mask'] <= 256) {
        $role = 8;
    } elseif ($dataProfile['roles_mask'] <= 512) {
        $role = 9;
    } elseif ($dataProfile['roles_mask'] <= 1024) {
        $role = 10;
    } elseif ($dataProfile['roles_mask'] <= 2048) {
        $role = 11;
    } elseif ($dataProfile['roles_mask'] <= 4096) {
        $role = 12;
    } elseif ($dataProfile['roles_mask'] <= 8192) {
        $role = 13;
    } elseif ($dataProfile['roles_mask'] <= 16384) {
        $role = 14;
    } elseif ($dataProfile['roles_mask'] <= 32768) {
        $role = 15;
    } elseif ($dataProfile['roles_mask'] <= 65536) {
        $role = 16;
    } elseif ($dataProfile['roles_mask'] <= 131072) {
        $role = 17;
    } elseif ($dataProfile['roles_mask'] <= 262144) {
        $role = 18;
    } elseif ($dataProfile['roles_mask'] <= 524288) {
        $role = 19;
    } elseif ($dataProfile['roles_mask'] <= 1048576) {
        $role = 20;
    } elseif ($dataProfile['roles_mask'] <= 2097152) {
        $role = 21;
    } elseif ($dataProfile['roles_mask'] <= 4194304) {
        $role = 22;
    }

    $selectRole = $pdo->select()
        ->from('users_permissions')
        ->where('id', '=', $role);

    $stmtRole = $selectRole->execute();
    $roleName = $stmtRole->fetch();

    require 'template/template_navbar.php';

    $status = array("0" => "NORMAL", "1" => "ARCHIVED", "2" => "BANNED", "3" => "LOCKED", "4" => "PENDING REVIEW", "5" => "SUSPENDED");

    echo '
    <div class="uk-container main-container">

        <div uk-grid>
        
            <div class="uk-width-3-5@s driver">
        
                <div class="panel">
                
                    <div class="panel-head">
                        <p><span class="uk-margin-small-right" uk-icon="icon: user"></span> Profil - <span class="colorko-txt">'.$dataProfile['username'].'</span></p>
                    </div>
                    
                    <div class="panel-body">
                        
                        <div class="uk-padding-small">
                            
                            <div class="uk-text-center">
                                <div class="uk-inline">
                                    <img class="uk-border-rounded profile-avatar" src="' . URL . '/uploads/avatars/'.$dataProfile['avatar'].'">
                                    <div class="uk-position-top-right" style="margin-top: 5px;margin-right: -5px;"><span class="uk-label uk-label-my2">'.$status[$dataProfile['status']].'</span></div>';

                                    if ($dataProfile['skype']) {
                                        echo '<div class="uk-position-top-right" style="margin-top: 30px;margin-right: -5px;"><a href="skype:' . $dataProfile['skype'] . '?add" class="uk-label uk-label-my3">Skype</a></div>';
                                    }

                                echo '                                
                                </div>
                            </div>
                            
                            <table class="uk-table uk-table-divider uk-table-small profile-table">
                                <tr>
                                    <td class="uk-width-1-2">Meno užívateľa: <strong>'.$dataProfile['username'].'</strong></td>
                                    <td class="uk-width-1-2">Zaregistrovaný dňa: <strong>'.date("d. M. Y H:i", $dataProfile['registered']).'</strong></td>
                                </tr>
                                <tr>
                                    <td>Úroveň užívateľa: <strong>'.($roleName['name'] == 0 ? 'Užívateľ' : $roleName['name']).'</strong></td>
                                    <td>Posledná návšteva: <strong>'.date("d. M. Y H:i", $dataProfile['last_login']).'</strong></td>
                                </tr>
                            </table>
                            
                            <div class="myheading uk-heading-line uk-text-right"><span>Všetky úrovne užívateľa</span></div>';

                            if ($dataProfile['permissions']) {

                                $tmpArray[$dataProfile['id']] = explode(",", $dataProfile['permissions']);

                                foreach ($tmpArray[$dataProfile['id']] as $rights[$dataProfile['id']]) {
                                    $selectStatementp = $pdo->select()
                                        ->from('users_permissions')
                                        ->where('id', '=', $rights[$dataProfile['id']]);

                                    $stmtp = $selectStatementp->execute();
                                    $dataperm = $stmtp->fetch();

                                    $perms[$dataProfile['id']][] = ucfirst(strtolower($dataperm['name'])) . ',';
                                }

                                $resultperm[$dataProfile['id']] = implode(' ', $perms[$dataProfile['id']]);

                                echo '<i>' . rtrim($resultperm[$dataProfile['id']], ",") . '</i>';

                            } else {
                                echo '<i>Užívateľ</i>';
                            }

                            $xml=simplexml_load_file("http://steamcommunity.com/profiles/".$dataProfile['steam']."?xml=1") or die("Error: Cannot create object");

                            $steamName       = $xml->steamID;
                            $steamAvatar     = $xml->avatarIcon;
                            $steamStatus     = $xml->onlineState;
                            $steamStatusText = $xml->stateMessage;

                            if ($steamStatusText == "Online"){
                                $steamPlayerText = '<span style="color: #4b90ab;">Online</span>';
                            } else {
                                $steamPlayerText = $steamStatusText;
                            }

                            if ($steamStatus == "in-game") {
                                $steamPlayerText = '<span style="color: #83a837;">'.str_replace( array('<br/>', '&', '"'),' ',$steamStatusText).'</span>';
                            }



                            if ($dataProfile['steam']) {

                                echo '<div class="myheading uk-heading-line uk-text-right"><span>Steam účet</span></div>
                            
                                <img class="uk-margin-small-right uk-border-rounded profile-steam-' . $steamStatus . '-avatar" src="' . $steamAvatar . '">
                                
                                <a href="http://steamcommunity.com/profiles/' . $dataProfile['steam'] . '" target="_blank" class="uk-text-middle uk-margin-right uk-text-bold">' . $steamName . '</a>
                                
                                <span class="uk-text-middle">' . $steamPlayerText . '</span>';

                            }

                            echo '
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
</body>
</html>';