<?php

require '../vendor/autoload.php';
require 'app_config.php';

use Delight\Auth\Auth;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

$selectSettings = $pdo->select()
    ->from('settings')
    ->where('id', '=', 1);

$querySettings = $selectSettings->execute();
$dataSettings = $querySettings->fetch();

define( 'URL',  $dataSettings['HTTP_Secure'].$dataSettings['url']);

$selectStatement = $pdo->select()
    ->from('users')
    ->where('id', '=', $auth->getUserId());

$stmt = $selectStatement->execute();
$data = $stmt->fetch();

if(is_array($_FILES)) {

    if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {

        $file 			= $_FILES['userImage']['tmp_name'];

        $chars 			= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $rename 		= substr( str_shuffle( $chars ), 0, 30 );

        $temp 			= explode(".", $_FILES["userImage"]["name"]);
        $filename		= $rename . '.' . end($temp);

        $file_type 		= $_FILES['userImage']['type'];
        $allowed 		= array("image/jpeg", "image/gif", "image/png");

        if(in_array($file_type, $allowed)) {

            if(move_uploaded_file($file, "../uploads/avatars/" . $filename)) {

                $updateStatementAvatar = $pdo->update(array('avatar' => $filename))
                    ->table('users')
                    ->where('id', '=', $auth->getUserId());

                $affectedRowsAvatar = $updateStatementAvatar->execute();

                if ($affectedRowsAvatar) {

                    echo '<img class="uk-border-circle uk-margin-bottom" style="width: 150px;height: 150px;" src="'.URL.'/uploads/avatars/' . $filename . '">';
                    echo '
                    
                    <script type="text/javascript">
                                    
                        $(document).ready(function() {
                            setTimeout(function(){
                            $(\'#loading\').fadeOut();
                            UIkit.notification({
                                message: \'<span uk-icon="icon: check"></span> Avatar bol úspešne zmenený\',
                                status: \'primary\',
                                pos: \'top-right\',
                                timeout: 4000
                            });
                            }, 3000);
                        });
    
                    </script>
                    
                    <div id="loading" style="z-index: 1;" class="uk-position-cover uk-overlay uk-flex uk-flex-center uk-flex-middle">
                        <div class="labelmy">
                            <div class="uk-margin-right" uk-spinner></div>
                            Načítavam
                        </div>
                    </div>';

                } else {

                    echo '<img class="uk-border-circle uk-margin-bottom" style="width: 150px;height: 150px;" src="'.URL.'/uploads/avatars/'.$data['avatar'].'">';
                    echo '
                    
                    <script type="text/javascript">
                                    
                        $(document).ready(function() {
                            setTimeout(function(){
                                $(\'#loading\').fadeOut();
                                UIkit.notification({
                                    message: \'<span uk-icon="icon: close"></span> Nastala chyba\',
                                    status: \'danger\',
                                    pos: \'top-right\',
                                    timeout: 4000
                                });
                                }, 3000);
                            });
                        });
    
                    </script>
                    
                    <div id="loading" style="z-index: 1;" class="uk-position-cover uk-overlay uk-flex uk-flex-center uk-flex-middle">
                        <div class="labelmy">
                            <div class="uk-margin-right" uk-spinner></div>
                            Načítavam
                        </div>
                    </div>';

                }
            }

        } else {

            echo '<img class="uk-border-circle uk-margin-bottom" style="width: 150px;height: 150px;" src="'.URL.'/uploads/avatars/'.$data['avatar'].'">';
            echo '
                
                <script type="text/javascript">
								
                    $(document).ready(function() {
                        setTimeout(function(){
                        $(\'#loading\').fadeOut();
                        UIkit.notification({
                            message: \'<span uk-icon="icon: close"></span> Nastala chyba\',
                            status: \'danger\',
                            pos: \'top-right\',
                            timeout: 4000
                        });
                        }, 3000);
                    });

                </script>
                
                <div id="loading" style="z-index: 1;" class="uk-position-cover uk-overlay uk-flex uk-flex-center uk-flex-middle">
                    <div class="labelmy">
                        <div class="uk-margin-right" uk-spinner></div>
                        Načítavam
                    </div>
                </div>';
        }

    }

}