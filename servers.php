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

    $title = 'Servery';
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