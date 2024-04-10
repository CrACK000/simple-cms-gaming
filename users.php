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

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

$title = 'Servery';
require 'template/template_headtags.php';

echo'
</head>
<body>';

require 'template/template_navbar.php';

echo '
    <div class="uk-container main-container">
    
        <div uk-grid>
            <div class="uk-width-3-5@s driver">
            
                <table class="my-table uk-width-1-1" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <td width="10%">
                                ID#
                            </td>
                            <td width="60%">
                                Meno
                            </td>
                            <td width="30%" class="uk-text-center">
                                Úroveň
                            </td>
                        </tr>
                    </thead>
                    <tbody>';

                    $selectStatement = $pdo->select()
                                           ->from('users')
                                           ->orderBy('roles_mask', 'DESC');

                    $stmt = $selectStatement->execute();

                    while ($data = $stmt->fetch()){

                        if ($data['roles_mask'] <= 1) {
                            $role = 0;
                        } elseif ($data['roles_mask'] <= 2) {
                            $role = 1;
                        } elseif ($data['roles_mask'] <= 4) {
                            $role = 2;
                        } elseif ($data['roles_mask'] <= 8) {
                            $role = 3;
                        } elseif ($data['roles_mask'] <= 16) {
                            $role = 4;
                        } elseif ($data['roles_mask'] <= 32) {
                            $role = 5;
                        } elseif ($data['roles_mask'] <= 64) {
                            $role = 6;
                        } elseif ($data['roles_mask'] <= 128) {
                            $role = 7;
                        } elseif ($data['roles_mask'] <= 256) {
                            $role = 8;
                        } elseif ($data['roles_mask'] <= 512) {
                            $role = 9;
                        } elseif ($data['roles_mask'] <= 1024) {
                            $role = 10;
                        } elseif ($data['roles_mask'] <= 2048) {
                            $role = 11;
                        } elseif ($data['roles_mask'] <= 4096) {
                            $role = 12;
                        } elseif ($data['roles_mask'] <= 8192) {
                            $role = 13;
                        } elseif ($data['roles_mask'] <= 16384) {
                            $role = 14;
                        } elseif ($data['roles_mask'] <= 32768) {
                            $role = 15;
                        } elseif ($data['roles_mask'] <= 65536) {
                            $role = 16;
                        } elseif ($data['roles_mask'] <= 131072) {
                            $role = 17;
                        } elseif ($data['roles_mask'] <= 262144) {
                            $role = 18;
                        } elseif ($data['roles_mask'] <= 524288) {
                            $role = 19;
                        } elseif ($data['roles_mask'] <= 1048576) {
                            $role = 20;
                        } elseif ($data['roles_mask'] <= 2097152) {
                            $role = 21;
                        } elseif ($data['roles_mask'] <= 4194304) {
                            $role = 22;
                        }

                        $selectRole = $pdo->select()
                            ->from('users_permissions')
                            ->where('id', '=', $role);

                        $stmtRole = $selectRole->execute();
                        $roleName = $stmtRole->fetch();

                        echo '
                        <tr>
                            <td class="uk-text-center">
                                '.$data['id'].'
                            </td>
                            <td>
                                <a href="'.URL.'/profile.php?user='.$data['id'].'" class="colorko-txt">'.$data['username'].'</a>
                            </td>
                            <td class="uk-text-right">
                                <div class="uk-margin-right">'.($role == 0 ? 'Užívateľ' : $roleName['name']).'</div>
                            </td>
                        </tr>';

                    }

                    echo '
                    </tbody>
                </table>
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