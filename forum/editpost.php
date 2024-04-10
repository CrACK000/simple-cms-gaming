<?php

require '../vendor/autoload.php';
require '../app/app_config.php';

use Delight\Auth\Auth;
use Delight\Auth\Role;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

$selectDataMess = $pdo->select()
    ->from('forum_messages')
    ->where('id', '=', $_GET['id']);

$stmtDataMess = $selectDataMess->execute();
$dataDataMess = $stmtDataMess->fetch();

$selectDataPosts = $pdo->select()
    ->from('forum_posts')
    ->where('id', '=', $dataDataMess['post']);

$stmtDataPosts = $selectDataPosts->execute();
$dataDataPosts = $stmtDataPosts->fetch();

$selectDataUsr = $pdo->select()
    ->from('users')
    ->where('id', '=', $dataDataMess['author']);

$stmtDataUsr = $selectDataUsr->execute();
$dataDataUsr = $stmtDataUsr->fetch();

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

$title = 'Úprava';
require '../template/template_headtags.php';

if ($dataDataMess['author'] == $auth->getUserId() || $auth->hasAnyRole(Role::SUBSCRIBER, Role::SUPER_ADMIN, Role::SUPER_EDITOR, Role::SUPER_MODERATOR, Role::TRANSLATOR)) {

echo '
</head>
<body>';

require '../template/template_navbar.php';

echo '
    <div class="uk-container main-container">';

echo '
        <div uk-grid>
            <div class="uk-width-3-5@s driver">
                
                <div class="panel">
                    <div class="panel-head">
                        <p>'.$title.'</p>
                    </div>
                    <div class="panel-body">
                        
                        <div class="uk-padding-small">';

                            if (isset($_POST['editmess'])){
                                $updateStatement = $pdo->update(array('text' => $_POST['text']))
                                    ->table('forum_messages')
                                    ->where('id', '=', $_GET['id']);

                                $affectedRows = $updateStatement->execute();

                                if ($affectedRows){
                                    echo '<script type="text/javascript">window.location.replace("' . URL . '/forum/post.php?id='.$dataDataMess['post'].'");</script>';
                                    exit;
                                }
                            }

                            echo '

                            <p>Téma: <b>'.$dataDataPosts['title'].'</b> <span class="uk-float-right">Autor: <b>'.$dataDataUsr['username'].'</b></span></p>

                            <form method="post" class="uk-form-stacked">

                                <div class="uk-margin">
                                    <label class="uk-form-label" for="form-stacked-text">Správa</label>
                                    <div class="uk-form-controls">
                                        <textarea class="uk-textarea" rows="8" name="text">'.$dataDataMess['text'].'</textarea>
                                    </div>
                                </div>
                                
                                <div class="uk-margin">
                                    <button class="button" type="submit" name="editmess">Upraviť</button>
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
        </div>';


    echo '</div>';

} else {
    echo '<script type="text/javascript">window.location.replace("' . URL . '/forum/post.php?id='.$dataDataMess['post'].'");</script>';
    exit;
}

require '../template/template_footer.php';

require '../template/template_scripts.php';

echo '
</body>
</html>';