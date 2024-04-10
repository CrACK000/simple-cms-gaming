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

    $title = 'Novinky';
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

                                if ($_GET['action'] == 'add'){

                                    if (isset($_POST['addNews'])){
                                        $insertStatement = $pdo->insert(array('image_url', 'title', 'author', 'date', 'text'))
                                                                ->into('news')
                                                                ->values(array($_POST['image_url'], $_POST['title'], $auth->getUserId(), time(), $_POST['text']));

                                        $insertId = $insertStatement->execute(false);

                                        if ($insertId){
                                            header('Location: ' . URL . '/admin/news.php');
                                        }
                                    }

                                    echo '
                                    <form class="uk-form-stacked uk-margin-small" method="post" accept-charset="utf-8">
                                    
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="t">Názov</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="t" type="text" name="title" placeholder="Example">
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="o">Obrázok (URL)</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="o" type="text" name="image_url" placeholder="http://example.com/image.png">
                                                <small>Max 160x235px</small>
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="t">Správa</label>
                                            <div class="uk-form-controls">
                                                <textarea class="uk-textarea" rows="8" name="text" placeholder="Sem napiš správu..."></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <button type="submit" name="addNews" class="button uk-float-right">Odoslať</button>
                                        </div>
                                        
                                    </form>';

                                } elseif ($_GET['action'] == 'edit' && $_GET['id']){

                                    $selectStatement = $pdo->select()
                                        ->from('news')
                                        ->where('id', '=', $_GET['id']);

                                    $stmt = $selectStatement->execute();
                                    $data = $stmt->fetch();

                                    if (isset($_POST['editNews'])){
                                        $updateStatement = $pdo->update(array('image_url' => $_POST['image_url']))
                                                                  ->set(array('title' => $_POST['title']))
                                                                  ->set(array('text' => $_POST['text']))
                                                                  ->table('news')
                                                                  ->where('id', '=', $_GET['id']);

                                        $affectedRows = $updateStatement->execute();

                                        if ($affectedRows){
                                            header('Location: ' . URL . '/admin/news.php');
                                        }
                                    }

                                    echo '
                                    <form class="uk-form-stacked uk-margin-small" method="post" accept-charset="utf-8">
                                    
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="t">Názov</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="t" type="text" value="'.$data['title'].'" name="title" placeholder="Example">
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="o">Obrázok (URL)</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" id="o" type="text" value="'.$data['image_url'].'" name="image_url" placeholder="http://example.com/image.png">
                                                <small>Max 160x235px</small>
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="t">Správa</label>
                                            <div class="uk-form-controls">
                                                <textarea class="uk-textarea" rows="8" name="text" placeholder="Sem napiš správu...">'.$data['text'].'</textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="uk-margin">
                                            <button type="submit" name="editNews" class="button uk-float-right">Odoslať</button>
                                        </div>
                                        
                                    </form>';

                                } else {

                                    echo '
                                    <a href="' . URL . '/admin/news.php?action=add"><button type="button" class="button uk-float-right uk-margin-small-right" style="margin-top: -53px;">Napísať novinku</button></a>
                                    <table class="my-table uk-width-1-1 uk-table-middle uk-margin-auto" cellspacing="0" cellpadding="0">
                                        <thead>
                                            <tr>
                                                <td class="uk-text-center" width="6%">ID</td>
                                                <td width="64%">Názov</td>
                                                <td width="30%">Akcia</td>
                                            </tr>
                                        </thead>
                                        <tbody>';

                                        $selectNews = $pdo->select()
                                            ->from('news')
                                            ->orderBy('id', 'ASC');

                                        $stmtNews = $selectNews->execute();

                                        while ($dataNews = $stmtNews->fetch()) {

                                            if (isset($_POST['deleteNews' . $dataNews['id']])) {
                                                $deleteDeleteForumCategory = $pdo->delete()
                                                    ->from('news')
                                                    ->where('id', '=', $dataNews['id']);

                                                $affectedRowsDeleteForumCategory = $deleteDeleteForumCategory->execute();

                                                if ($affectedRowsDeleteForumCategory){
                                                    header('Location: ' . URL . '/admin/news.php');
                                                }
                                            }

                                            echo '
                                            <tr>
                                                <td class="uk-text-center"><strong>' . $dataNews['id'] . '</strong>.</td>
                                                <td><span class="uk-text-truncate">' . $dataNews['title'] . '</span></td>
                                                <td>
                                                    <a href="'.URL.'/admin/news.php?action=edit&id=' . $dataNews['id'] . '"><button type="button" class="button uk-margin-remove">Upraviť</button></a>
                                                    <form method="post" class="uk-display-inline uk-margin-remove"><button type="submit" name="deleteNews' . $dataNews['id'] . '" class="button uk-margin-remove">Zmazať</button></form>
                                                </td>
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

