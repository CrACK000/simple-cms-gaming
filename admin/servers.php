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

    $title = 'Servery';
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

                                if (isset($_POST['addServer'])) {
                                    $insertStatement = $pdo->insert(array('game', 'addr', 'query'))
                                        ->into('servers')
                                        ->values(array($_POST['serverGame'], $_POST['serverAddr'], $_POST['serverQuery']));

                                    $insertId = $insertStatement->execute(false);

                                    if ($insertId){
                                        header('Location: ' . URL . '/admin/servers.php');
                                    }
                                }

                                echo '
                                <form method="post" class="uk-grid-small" uk-grid>
                                    <div class="uk-width-1-4@s">
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-stacked-selectg">Hra</label>
                                            <div class="uk-form-controls">
                                                <select class="uk-select" name="serverGame" id="form-stacked-selectg">
                                                    <option value="cs16">Counter Strike 1.6</option>
                                                    <option value="csgo">Counter Strike Global Offensive</option>
                                                    <option value="samp">San Andreas Multiplayer</option>
                                                    <option value="minecraft">Minecraft</option>
                                                    <option value="teamspeak3">Teamspeak 3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-2@s">
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-stacked-texts">IP:Port</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="form-stacked-texts" name="serverAddr" type="text" placeholder="IP adresa plus Port">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-4@s">
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-stacked-textq">Query Port</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="form-stacked-textq" name="serverQuery" type="text" placeholder="Query Port">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1@s">
                                        <div class="uk-margin uk-text-center">
                                            <button class="button" type="submit" name="addServer">Pridať server</button>
                                        </div>
                                    </div>
                                </form>
                                
                                <table class="my-table uk-width-1-1 uk-table-middle uk-margin-auto  uk-margin-small-top" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <td class="uk-text-center" width="6%">ID</td>
                                            <td width="26%">Hra</td>
                                            <td width="38%">IP:Port</td>
                                            <td width="38%">Query Port</td>
                                            <td width="30%">Akcia</td>
                                        </tr>
                                    </thead>
                                    <tbody>';

                                        $selectPermissions = $pdo->select()
                                            ->from('servers')
                                            ->orderBy('id', 'ASC');

                                        $stmtPermissions = $selectPermissions->execute();

                                        while ($dataPermissions = $stmtPermissions->fetch()) {

                                            if (isset($_POST['deleteServer' . $dataPermissions['id']])) {
                                                $deleteDeleteForumCategory = $pdo->delete()
                                                    ->from('servers')
                                                    ->where('id', '=', $dataPermissions['id']);

                                                $affectedRowsDeleteForumCategory = $deleteDeleteForumCategory->execute();

                                                if ($affectedRowsDeleteForumCategory){
                                                    header('Location: ' . URL . '/admin/servers.php');
                                                }
                                            }

                                            echo '
                                            <tr>
                                                <td class="uk-text-center"><strong>' . $dataPermissions['id'] . '</strong>.</td>
                                                <td>' . $dataPermissions['game'] . '</td>
                                                <td>' . $dataPermissions['addr'] . '</td>
                                                <td>' . $dataPermissions['query'] . '</td>
                                                <td>
                                                    <form method="post" class="uk-display-inline uk-margin-remove"><button type="submit" name="deleteServer' . $dataPermissions['id'] . '" class="button uk-margin-remove">Zmazať</button></form>
                                                </td>
                                            </tr>';

    }

                                    echo '
                                    </tbody>
                                </table>
                            
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

