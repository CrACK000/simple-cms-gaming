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

        $title = 'Administrácia';
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
                            <p>' . $title . '</p>
                        </div>
                        <div class="panel-body">
                        
                            <div class="uk-padding-small">
                        
                                <ul class="uk-list uk-list-divider">
                                    <li><a href="'.URL.'/admin/award_rights.php">Právomoci</a></li>
                                    <li><a href="'.URL.'/admin/groups.php">Skupiny</a></li>
                                    <li><a href="'.URL.'/admin/forum_category.php">Fórum kategórie</a></li>
                                    <li><a href="'.URL.'/admin/forum_underCategory.php">Fórum pod-kategórie</a></li>
                                    <li><a href="'.URL.'/admin/servers.php">Servery</a></li>
                                    <li><a href="'.URL.'/admin/news.php">Novinky</a></li>
                                    <li><a href="'.URL.'/admin/settings.php">Nastavenia</a></li>
                                    <li><a href="'.URL.'/admin/users.php">Užívatelia</a></li>
                                </ul>
                            
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

