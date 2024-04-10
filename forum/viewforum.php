<?php

require '../vendor/autoload.php';
require '../app/app_config.php';

use \Delight\Auth\Auth;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

$selectDataForumCat = $pdo->select()
    ->from('forum_underCategory')
    ->where('id', '=', $_GET['id']);

$stmtDataForumCat = $selectDataForumCat->execute();
$dataDataForumCat = $stmtDataForumCat->fetch();

if (!$_GET['id']){
    header('Location: http://gamestroke.eu/index.php');
    exit;
} elseif (!$dataDataForumCat['id']){
    header('Location: http://gamestroke.eu/index.php');
    exit;
}

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

$title = 'Fórum ( '.$dataDataForumCat['title'].' )';
require '../template/template_headtags.php';

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
                        <p>Fórum / Kategória: <span class="colorko-txt">'.$dataDataForumCat['title'].'</span></p>
                    </div>
                    <div class="panel-body">';

                        if ($_GET['add'] == "post") {

                            if ($auth->isLoggedIn()) {

                                if (isset($_POST['post'])) {
                                    $insertStatement = $pdo->insert(array('author', 'title', 'underCategory', 'message', 'date'))
                                        ->into('forum_posts')
                                        ->values(array($auth->getUserId(), $_POST['title'], $_GET['id'], $_POST['message'], time()));

                                    $insertId = $insertStatement->execute(false);

                                    if ($insertId){
                                        echo '<script type="text/javascript">window.location.replace("'.URL.'/forum/viewforum?id='.$_GET['id'].'");</script>';
                                        exit;
                                    }
                                }

                            } else {
                                echo '<script type="text/javascript">window.location.replace("'.URL.'/forum/viewforum?id='.$_GET['id'].'");</script>';
                                exit;
                            }

                            echo '
                            <div class="uk-padding-small">
                                <form class="uk-form-stacked uk-margin-small" method="post" accept-charset="utf-8">
                                
                                    <div class="uk-margin">
                                        <label class="uk-form-label" for="t">Názov</label>
                                        <div class="uk-form-controls">
                                            <input class="uk-input" id="t" type="text" name="title" placeholder="Example">
                                        </div>
                                    </div>
                                    
                                    <div class="uk-margin">
                                        <label class="uk-form-label" for="t">Správa</label>
                                        <div class="uk-form-controls">
                                            <textarea class="uk-textarea" rows="10" name="message" placeholder="Sem napiš správu..."></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="uk-margin">
                                        <button type="submit" name="post" class="button uk-float-right">Odoslať</button>
                                    </div>
                                    
                                </form>
                            </div>';

                        } else {

                            if ($auth->isLoggedIn()) {
                                echo '<a href="' . URL . '/forum/viewforum.php?id=' . $_GET['id'] . '&add=post"><button type="button" class="button uk-float-right uk-margin-small-right" style="margin-top: -38px;">Pridať novú tému</button></a>';
                            }

                            echo '
                            <table class="my-table uk-width-1-1" cellpadding="0" cellspacing="0">
                                <thead>
                                    <tr>
                                        <td colspan="2" class="uk-text-left">
                                            <div class="uk-margin-remove uk-heading-line"><span>' . $dataDataForumCat['title'] . '</span></div>
                                        </td>
                                    </tr>
                                </thead>';

                                $selectPosts = $pdo->select()
                                    ->from('forum_posts')
                                    ->where('underCategory', '=', $_GET['id']);

                                $stmtPosts = $selectPosts->execute();

                                while ($dataPosts = $stmtPosts->fetch()) {

                                    $selectViewforumUser = $pdo->select()
                                        ->from('users')
                                        ->where('id', '=', $dataPosts['author']);

                                    $stmtViewforumUser = $selectViewforumUser->execute();
                                    $dataViewforumUser = $stmtViewforumUser->fetch();

                                    echo '
                                        <tbody>
                                            <tr>
                                                <td width="60%">
                                                    <a href="' . URL . '/forum/post.php?id=' . $dataPosts['id'] . '">' . $dataPosts['title'] . '</a>
                                                </td>
                                                <td width="40%" class="uk-text-center">
                                                    <span class="uk-margin-small-right">napísal: <a class="uk-margin-right" href="'.URL.'/profile.php?user='.$dataViewforumUser['id'].'">'.$dataViewforumUser['username'].'</a> <small class="uk-text-muted">'.date('d.m. Y H:i',$dataPosts['date']).'</small></span>
                                                </td>
                                            </tr>
                                        </tbody>';

                                }

                                echo '
                            </table>';

                        }

                        echo '
                    </div>
                </div>
                
            </div>
            <div class="uk-width-2-5@s right-panel-driver">';
            require '../template/template_panels.php';
            echo '
            </div>
        </div>';


    echo '
    </div>
    ';

require '../template/template_footer.php';

require '../template/template_scripts.php';

echo '
</body>
</html>';