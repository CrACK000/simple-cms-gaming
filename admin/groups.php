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

    $title = 'Skupiny';
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

                                if ($_GET['edit']) {

                                    $selectEditPerms = $pdo->select()
                                        ->from('users_permissions')
                                        ->where('id', '=', $_GET['edit']);

                                    $stmtEditPerms = $selectEditPerms->execute();
                                    $dataEditPerms = $stmtEditPerms->fetch();

                                    if ($_GET['edit'] >= 18) {
                                        header('Location: '.URL.'/admin/groups.php');
                                        exit;
                                    } elseif (!$dataEditPerms['id']) {
                                        header('Location: '.URL.'/admin/groups.php');
                                        exit;
                                    }

                                    if (isset($_POST['editPerm'])){
                                        $updateEditPerms = $pdo->update(array('name' => $_POST['namePerm']))
                                                               ->table('users_permissions')
                                                               ->where('id', '=', $_GET['edit']);

                                        $dataUpdateEditPerms = $updateEditPerms->execute();

                                        if ($dataUpdateEditPerms){
                                            header('Location: '.URL.'/admin/groups.php');
                                            exit;
                                        }
                                    }

                                    echo '
                                    <form method="post" class="uk-form-stacked uk-width-1-2 uk-text-center uk-margin-auto">
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-stacked-text">Názov skupiny</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" name="namePerm" id="form-stacked-text" type="text" value="'.$dataEditPerms['name'].'">
                                                <small>Skupina s prioritou '.$dataEditPerms['id'].'.</small>
                                            </div>
                                        </div>
                                        <div class="uk-margin">
                                            <button class="button" name="editPerm" type="submit">Upraviť</button>
                                        </div>
                                    </form>';

                                } else {

                                    echo '
                                    <table class="my-table uk-width-1-1 uk-table-middle" cellspacing="0" cellpadding="0">
                                        <thead>
                                            <tr>
                                                <td width="15%">Priorita</td>
                                                <td width="60%">Názov</td>
                                                <td width="25%">Akcia</td>
                                            </tr>
                                        </thead>
                                        <tbody>';

                                        $selectPermissions = $pdo->select()
                                            ->from('users_permissions')
                                            ->orderBy('id', 'DESC');

                                        $stmtPermissions = $selectPermissions->execute();

                                        while ($dataPermissions = $stmtPermissions->fetch()) {

                                            echo '
                                                <tr>
                                                    <td><strong>' . $dataPermissions['id'] . '</strong>.</td>
                                                    <td>' . $dataPermissions['name'] . '</td>
                                                    <td>';
                                            if ($dataPermissions['id'] < 18) {
                                                echo '<a href="' . URL . '/admin/groups.php?edit=' . $dataPermissions['id'] . '"><button class="button uk-margin-remove">Upraviť</button></a>';
                                            }
                                            echo '</td>
                                                </tr>';

                                        }

                                        echo '
                                        </tbody>
                                    </table>';

                                }

                                echo '
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

