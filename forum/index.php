<?php

require '../vendor/autoload.php';
require '../app/app_config.php';

use \Delight\Auth\Auth;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

$title = 'Fórum';
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
                    <div class="panel-body">
                        
                        <table class="my-table uk-width-1-1" cellpadding="0" cellspacing="0">';

                            $selectForumCategory = $pdo->select()
                                                       ->from('forum_category');

                            $stmtForumCategory = $selectForumCategory->execute();

                            while ($dataForumCategory = $stmtForumCategory->fetch()) {

                                echo '
                                <thead>
                                    <tr>
                                        <td colspan="2" class="uk-text-left">
                                            <div class="uk-margin-remove uk-heading-line"><span>' . $dataForumCategory['title'] . '</span></div>
                                        </td>
                                    </tr>
                                </thead>';

                                $selectForumUnderCategory = $pdo->select()
                                                                ->from('forum_underCategory')
                                                                ->where('category', '=', $dataForumCategory['id']);

                                $stmtForumUnderCategory = $selectForumUnderCategory->execute();

                                while ($dataForumUnderCategory = $stmtForumUnderCategory->fetch()) {

                                    $countPosts = $pdo->select(array('COUNT(*)'))->from('forum_posts')->where('underCategory', '=', $dataForumUnderCategory['id']);
                                    $resultPostsCount = $countPosts->execute();
                                    $count = $resultPostsCount->fetchColumn();

                                    echo '
                                    <tbody>
                                        <tr>
                                            <td width="90%">
                                                <a href="' . URL . '/forum/viewforum.php?id=' . $dataForumUnderCategory['id'] . '">' . $dataForumUnderCategory['title'] . '</a>
                                            </td>
                                            <td width="10%" class="uk-text-center">
                                                <span class="uk-badge">' . $count . '</span>
                                            </td>
                                        </tr>
                                    </tbody>';
                                }

                            }

                            echo '
                        </table>
                            
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