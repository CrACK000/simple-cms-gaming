<?php

use Delight\Auth\Role;

if (isset($_POST['shout'])){

    $text = $_POST['text'];

    $insertStatement = $pdo->insert(array('author', 'text', 'date'))
                               ->into('shoutbox')
                               ->values(array($auth->getUserId(), $text, time()));

    $insert = $insertStatement->execute(false);

    if ($insert){
        echo '<script type="text/javascript">window.location.replace("'.URL.$_SERVER['PHP_SELF'].'");</script>';
        exit;
    }

}

$selectShoutBox = $pdo->select()
                       ->from('shoutbox')
                       ->orderBy('id', 'DESC')
                       ->limit(5);

$stmtShoutbox = $selectShoutBox->execute();

echo '<div class="panel">
    <div class="panel-head">
        <p><span class="uk-margin-small-right" uk-icon="icon: comments"></span> Shoutbox</p>
    </div>
    <div class="panel-body">
        <div class="panel-shout">';

        if ($auth->isLoggedIn()) {
            echo '
            <form method="post" action="" style="margin-bottom: 50px;">
        
                <textarea class="uk-textarea" rows="3" placeholder="Sem napiš správu..." name="text"></textarea>
                <button type="submit" name="shout" class="uk-float-right button">Odoslať</button>
                
            </form>';
        } else {
            echo '<div class="uk-margin uk-text-center">Aby si mohol odoslať správu musíš sa prihlásiť.</div>';
        }

            while ($shoutbox = $stmtShoutbox->fetch()) {

                $selectShoutBoxAuthor = $pdo->select()
                                            ->from('users')
                                            ->where('id', '=', $shoutbox['author']);

                $stmtShoutboxAuthor = $selectShoutBoxAuthor->execute();
                $stmtShoutboxAuthordata = $stmtShoutboxAuthor->fetch();

                $whileText = parsebb($shoutbox['text']);

                if ($shoutbox['author'] == $auth->getUserId() || $auth->hasAnyRole(Role::SUBSCRIBER, Role::SUPER_ADMIN, Role::SUPER_EDITOR, Role::SUPER_MODERATOR, Role::TRANSLATOR)) {

                    if (isset($_POST['shoutRemoveID'.$shoutbox['id']])) {

                        $deleteShout = $pdo->delete()
                            ->from('shoutbox')
                            ->where('id', '=', $shoutbox['id']);

                        $delShout = $deleteShout->execute();

                        if ($delShout) {
                            echo '<script type="text/javascript">window.location.replace("'.URL.$_SERVER['PHP_SELF'].'");</script>';
                            exit;
                        }

                    }

                    if (isset($_POST['shoutEditID' . $shoutbox['id']])) {

                        $editShoutText2 = $pdo->update(array('text' => $_POST['editShoutText' . $shoutbox['id']]))
                            ->table('shoutbox')
                            ->where('id', '=', $shoutbox['id']);

                        $editShoutText3 = $editShoutText2->execute();

                        if ($editShoutText3){
                            echo '<script type="text/javascript">window.location.replace("'.URL.$_SERVER['PHP_SELF'].'");</script>';
                            exit;
                        }

                    }

                    echo '
                    <div id="edit-shoutID' . $shoutbox['id'] . '" class="uk-flex-top" uk-modal xmlns="http://www.w3.org/1999/html">
                        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
                    
                            <button class="uk-modal-close-default" type="button" uk-close></button>
                            
                            <form method="POST">
                            
                                <p>
                                    <div class="uk-margin-small-bottom uk-margin-small-top"><small>Správa</small></div>
                                    <textarea name="editShoutText' . $shoutbox['id'] . '" class="uk-textarea" rows="4">' . $shoutbox['text'] . '</textarea>
                                </p>
                        
                                <button class="button uk-float-right" type="submit" name="shoutEditID' . $shoutbox['id'] . '">Upraviť správu</button>
                    
                            </form>
                    
                        </div>
                    </div>';
                }

                echo '
                <div class="shout-driver"></div>
                
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-1-5">
                        <div class="avatar"><img src="'.URL.'/uploads/avatars/'.$stmtShoutboxAuthordata['avatar'].'" alt=""></div>
                        <div class="uk-text-center name"><a href="' . URL . '/profile.php?user=' . $shoutbox['author'] . '" class="colorko-txt" style="text-decoration: none">'.$stmtShoutboxAuthordata['username'].'</a></div>
                    </div>
                    <div class="uk-width-4-5">
                        <p class="shout-text">
                            '.$whileText.'
                        </p>
                    </div>
                </div>
                <div style="margin-top: 10px;margin-bottom: 0;" uk-grid>
                    <div class="uk-width-1-2">
                        <p class="shout-detail">'.date('d.m.Y H:i', $shoutbox['date']).'</p>
                    </div>';

                    if ($shoutbox['author'] == $auth->getUserId() || $auth->hasAnyRole(Role::SUBSCRIBER, Role::SUPER_ADMIN, Role::SUPER_EDITOR, Role::SUPER_MODERATOR, Role::TRANSLATOR)) {
                        echo '
                        <div class="uk-width-1-2 uk-text-right">
                            <form method="POST" style="margin-bottom: -25px;">
                                <p class="shout-detail"><a href="#edit-shoutID'.$shoutbox['id'].'" class="colorko-txt" uk-toggle>Upraviť</a> / <button type="submit" name="shoutRemoveID'.$shoutbox['id'].'">Odstrániť</button></p>
                            </form>
                        </div>';
                    }

                echo '
                </div>';

            }
            
        echo '            
        </div>
    </div>
</div>';