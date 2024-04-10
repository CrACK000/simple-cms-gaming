<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use Delight\Auth\Auth;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

$selectdataNews = $pdo->select()
    ->from('news')
    ->where('id', '=', $_GET['id']);

$stmtNewsData = $selectdataNews->execute();
$getNewsData  = $stmtNewsData->fetch();

$selectNewsAuthor = $pdo->select()
    ->from('users')
    ->where('id', '=', $getNewsData['author']);

$stmtNewsAuthor = $selectNewsAuthor->execute();
$stmtNewsAuthordata = $stmtNewsAuthor->fetch();

if (!$_GET['id']){
    header('Location: http://gamestroke.eu/index.php');
    exit;
} elseif (!$getNewsData['id']){
    header('Location: http://gamestroke.eu/index.php');
    exit;
}

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

$title = 'Najnovšie aktuality';
require 'template/template_headtags.php';

echo'
</head>
<body>';

/*$plusoneread = $getNewsData['read'] + 1;

$updateStatement = $pdo->update(array('read' => $plusoneread))
    ->table('news')
    ->where('id', '=', $_GET['id']);

$affectedRows = $updateStatement->execute();

if ($affectedRows){
    $ok = 1;
}*/

require 'template/template_navbar.php';

echo '
    <div class="uk-container main-container">
    
        <div uk-grid>
            <div class="uk-width-3-5@s driver">
                
                
                
                <div class="panel">
                    <div class="panel-head">
                        <p><span class="uk-margin-small-right" uk-icon="icon: star"></span><span style="color: #df3a3a;">Novinka</span> <span class="uk-text-truncate" title="' . $getNewsData['title'] . '">' . $getNewsData['title'] . '</span></p>
                    </div>
                    <div class="panel-body">
                    
                        <p class="uk-padding-small uk-margin-small-bottom" style="background: #140f17">' . parsebb($getNewsData['text']) . '</p>
                        
                        <p class="uk-padding-small uk-text-center" style="background: #140f17"><span class="colorko-txt" uk-icon="icon: user"></span> <a href="'.URL.'/profile.php?user=' . $getNewsData['author'] . '">' . $stmtNewsAuthordata['username'] . '</a> | <span class="colorko-txt" uk-icon="icon: calendar"></span> ' . date("d. M. Y", $getNewsData['date']) . ' | <span class="colorko-txt" uk-icon="icon: search"></span> Prečítané ' . $getNewsData['read'] . 'x</p>
                    
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