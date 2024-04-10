<?php

require 'vendor/autoload.php';
require 'app/app_config.php';

use Delight\Auth\Auth;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

echo '
<!DOCTYPE html>
<html lang="sk">
<head>';

    $title = 'Najnovšie aktuality';
    require 'template/template_headtags.php';

echo'
</head>
<body>';

    require 'template/template_navbar.php';

    echo '
    <div class="uk-container main-container">
    
        <div uk-grid>
            <div class="uk-width-3-5@s driver">';

                require 'template/panels/servers.php';

                echo '
                <div class="panel">
                    <div class="panel-head">
                        <p><span class="uk-margin-small-right" uk-icon="icon: star"></span> Najnovšie aktuality nie len s <span style="color: #df3a3a;">Webu</span>, ale aj zo <span style="color: #df3a3a;">Sveta</span></p>
                    </div>
                    <div class="panel-body">';

                    $selectNews = $pdo->select()
                                      ->from('news');

                    $stmtNews = $selectNews->execute();

                    while ($newsData = $stmtNews->fetch()) {

                        $selectNewsAuthor = $pdo->select()
                            ->from('users')
                            ->where('id', '=', $newsData['author']);

                        $stmtNewsAuthor = $selectNewsAuthor->execute();
                        $stmtNewsAuthordata = $stmtNewsAuthor->fetch();

                        echo '
                        <div class="news-box">
                            <div class="news-in-box uk-flex-inline uk-inline uk-width-1-1@m">
                            
                                <div class="uk-position-small uk-position-top-right news-number uk-text-center">' . date("d.", $newsData['date']) . ' <div>' . date("M.", $newsData['date']) . '</div></div>
                            
                                <img style="width: 160px;" class="uk-margin-small-right" src="' . $newsData['image_url'] . '">
                                <div class="uk-width-1-1">
                                
                                    <h3 class="uk-text-truncate" title="' . $newsData['title'] . '">' . $newsData['title'] . '</h3>
                                    <small><span uk-icon="icon: user; ratio: 0.6"></span> ' . $stmtNewsAuthordata['username'] . ' | <span uk-icon="icon: calendar; ratio: 0.6"></span> ' . date("d. M. Y", $newsData['date']) . ' | <span uk-icon="icon: search; ratio: 0.6"></span> Prečítané ' . $newsData['read'] . 'x</small>
                                    
                                    <div class="news-driver uk-margin-small-top"></div>
                                    
                                    <p class="news-text">' . htmlcode($newsData['text']) . '</p>
                                    
                                    <div class="news-driver uk-margin-small-bottom"></div>
                                    
                                    <div class="fb-like uk-float-left" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>
                                    
                                    <a href="' . URL . '/news.php?id=' . $newsData['id'] . '"><button type="button" class="button uk-float-right">Čítaj celé</button></a>
                                    
                                </div>
                            </div>
                        </div>';

                    }

                        echo '
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