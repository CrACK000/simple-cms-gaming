<?php

require '../vendor/autoload.php';
require '../app/app_config.php';

use Delight\Auth\Auth;
use Delight\Auth\Role;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

$selectDataPosts = $pdo->select()
    ->from('forum_posts')
    ->where('id', '=', $_GET['id']);

$stmtDataPosts = $selectDataPosts->execute();
$dataDataPosts = $stmtDataPosts->fetch();

$selectDataPostsAuthor = $pdo->select()
    ->from('users')
    ->where('id', '=', $dataDataPosts['author']);

$stmtDataPostsAuthor = $selectDataPostsAuthor->execute();
$dataDataPostsAuthor = $stmtDataPostsAuthor->fetch();

if (!$_GET['id']){
    header('Location: http://gamestroke.eu/index.php');
    exit;
} elseif (!$dataDataPosts['id']){
    header('Location: http://gamestroke.eu/index.php');
    exit;
}

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

$title = $dataDataPosts['title'];
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
                        <p>'.$title.'</p>
                    </div>
                    <div class="panel-body">';

                        if ($auth->hasAnyRole(Role::SUBSCRIBER, Role::SUPER_ADMIN, Role::SUPER_EDITOR, Role::SUPER_MODERATOR, Role::TRANSLATOR) && $dataDataPosts['lock'] == "0") {

                            if (isset($_POST['lock'])) {
                                $updateStatementaww = $pdo->update(array('lock' => '1'))
                                    ->table('forum_posts')
                                    ->where('id', '=', $_GET['id']);

                                $affectedRowswq = $updateStatementaww->execute();

                                if ($affectedRowswq){
                                    echo '<script type="text/javascript">window.location.replace("' . URL . '/forum/post.php?id='.$_GET['id'].'");</script>';
                                    exit;
                                }
                            }

                            echo '<form method="post" class="uk-display-inline uk-margin-remove">
                                    <button type="submit" name="lock" class="button uk-float-right uk-margin-small-right" style="margin-top: -58px;">Zamknúť tému</button>
                                  </form>';
                        }

                        echo '
                        <ul class="uk-comment-list uk-padding-small">
                            <li>
                                <article class="uk-comment uk-visible-toggle">
                                    <header class="uk-comment-header uk-position-relative">
                                        <div class="uk-grid-medium uk-flex-middle" uk-grid>
                                            <div class="uk-width-auto">
                                                <img class="uk-comment-avatar uk-border-rounded" src="' . URL . '/uploads/avatars/' . $dataDataPostsAuthor['avatar'] . '" width="80" height="80" alt="">
                                            </div>
                                            <div class="uk-width-expand">
                                                <h4 class="uk-comment-title uk-margin-remove"><a class="uk-link-reset" href="' . URL . '/profile.php?user=' . $dataDataPostsAuthor['id'] . '"><span class="colorko-txt">' . $dataDataPostsAuthor['username'] . '</span></a></h4>
                                                <p class="uk-comment-meta uk-margin-remove-top">' . time_since($dataDataPosts['date']) . '</p>
                                            </div>
                                        </div>';

                                        if ($dataDataPosts['lock'] == "0") {
                                            if ($dataDataPosts['author'] == $auth->getUserId() || $auth->hasAnyRole(Role::SUBSCRIBER, Role::SUPER_ADMIN, Role::SUPER_EDITOR, Role::SUPER_MODERATOR, Role::TRANSLATOR)) {

                                                echo '
                                                <div class="uk-position-top-right uk-position-small uk-hidden-hover">
                                                    <ul class="uk-iconnav">
                                                        <li><a href="' . URL . '/forum/editmainpost.php?id=' . $dataDataPosts['id'] . '"> Upraviť </a></li>
                                                    </ul>
                                                </div>';

                                            }
                                        }

                                        echo '
                                    </header>
                                    <div class="uk-comment-body forum-message-main">
                                        <p>' . parsebb($dataDataPosts['message']) . '</p>
                                    </div>
                                </article>
                            </li>
                        </ul>
                        
                        <div class="myheading uk-heading-line uk-text-right"><span>Odpovede</span></div>';

                        $selectStatementMessage = $pdo->select()
                                               ->from('forum_messages')
                                               ->where('post', '=', $_GET['id']);

                        $stmtMessage = $selectStatementMessage->execute();

                        while ($dataMessage = $stmtMessage->fetch()) {

                            $selectDataMessageAuthor = $pdo->select()
                                ->from('users')
                                ->where('id', '=', $dataMessage['author']);

                            $stmtDataMessageAuthor = $selectDataMessageAuthor->execute();
                            $dataDataMessageAuthor = $stmtDataMessageAuthor->fetch();

                            if (isset($_POST['deleteMessageID'.$dataMessage['id']])) {
                                $deleteDeleteMessage = $pdo->delete()
                                    ->from('forum_messages')
                                    ->where('id', '=', $dataMessage['id']);

                                $affectedRowsDeleteMessage = $deleteDeleteMessage->execute();

                                if ($affectedRowsDeleteMessage){
                                    echo '<script type="text/javascript">window.location.replace("' . URL . '/forum/post?id='.$_GET['id'].'");</script>';
                                    exit;
                                }
                            }

                            echo '
                            <div class="uk-margin-small uk-margin-small-left uk-margin-small-right">
                                <small>napísal</small> <a href="' . URL . '/profile?user=' . $dataDataMessageAuthor['id'] . '" class="colorko-txt">' . $dataDataMessageAuthor['username'] . '</a> <span class="uk-margin-left"><small>dňa <i>'.date("d. M. Y H:i", $dataMessage['date']).'</i></small></span>';

                            if ($dataDataPosts['lock'] == "0") {
                                if ($dataMessage['author'] == $auth->getUserId() || $auth->hasAnyRole(Role::SUBSCRIBER, Role::SUPER_ADMIN, Role::SUPER_EDITOR, Role::SUPER_MODERATOR, Role::TRANSLATOR)) {
                                    echo '<div class="uk-float-right uk-text-small"><a href="' . URL . '/forum/editpost.php?id=' . $dataMessage['id'] . '"><button type="button" class="uk-button uk-button-link uk-text-muted uk-margin-remove uk-text-capitalize">Upraviť</button></a> / <form method="post" class="uk-display-inline"><button type="submit" name="deleteMessageID' . $dataMessage['id'] . '" class="uk-button uk-button-link uk-text-muted uk-margin-remove uk-text-capitalize">Zmazať</button></form></div>';
                                }
                            }

                            echo '
                            </div>

                            <div class="uk-grid-small uk-margin-bottom" uk-grid>
                            
                                <div class="uk-width-auto@m">
                                    
                                    <img class="uk-border-rounded" src="' . URL . '/uploads/avatars/' . $dataDataMessageAuthor['avatar'] . '" width="80" height="80" alt="">
                                        
                                </div>
                                <div class="uk-width-expand@m">
                                    <div class="forum-message">' . parsebb($dataMessage['text']) . '</div>
                                </div>
                                    
                            </div>
                            
                            <hr>';

                        }

                        if ($dataDataPosts['lock'] == "0") {

                            if ($auth->getUserId()) {

                                if (isset($_POST['f_submit'])) {

                                    if ($_POST['f_message'] != "") {

                                        $f_text = htmlspecialchars($_POST['f_message']);

                                        $insertNewMessage = $pdo->insert(array('author', 'post', 'text', 'date'))
                                            ->into('forum_messages')
                                            ->values(array($auth->getUserId(), $_GET['id'], $f_text, time()));

                                        $addNewMessage = $insertNewMessage->execute(false);

                                        if ($addNewMessage) {
                                            echo '<script type="text/javascript">window.location.replace("' . URL . '/forum/post.php?id=' . $_GET['id'].'");</script>';
                                            exit;
                                        }
                                    }

                                }

                                echo '
                                <div class="myheading uk-heading-line uk-text-center"><span>Poslať odpoveď</span></div>
                                
                                <form method="post" action="' . URL . '/forum/post.php?id=' . $_GET['id'] . '">
                                    <div class="uk-margin-small">
                                        <div class="uk-margin-auto uk-width-4-5 uk-text-center">
                                            <textarea class="uk-textarea" rows="3" name="f_message" placeholder="Sem napiš správu..."></textarea>
                                            <button type="submit" name="f_submit" class="button">Poslať odpoveď</button>
                                        </div>
                                    </div>
                                </form>';
                            }

                        } else {
                            echo '<div class="uk-text-center">Téma je zamknutá</div>';
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


    echo '</div>';

    require '../template/template_footer.php';

    require '../template/template_scripts.php';

echo '
</body>
</html>';