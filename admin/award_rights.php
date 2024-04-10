<?php

require '../vendor/autoload.php';
require '../app/app_config.php';

use Delight\Auth\Auth;
use Delight\Auth\Role;
use Delight\Auth\UnknownUsernameException;
use Delight\Auth\AmbiguousUsernameException;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

if ($auth->hasAnyRole(Role::SUBSCRIBER, Role::SUPER_ADMIN, Role::SUPER_EDITOR, Role::SUPER_MODERATOR, Role::TRANSLATOR)) {

    if (isset($_POST['add'])) {

        $username = $_POST['username'];

        $selectStatementusr = $pdo->select()
            ->from('users')
            ->where('username', '=', $username);

        $stmtusr = $selectStatementusr->execute();
        $datausr = $stmtusr->fetch();

        if ($datausr['roles_mask'] == 0) {

            $checkboxes = isset($_POST['permissions']) ? $_POST['permissions'] : array();
            foreach ($checkboxes as $value) {

                $selectStatement = $pdo->select()
                    ->from('users_permissions')
                    ->where('id', '=', $value);

                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();

                if ($value == "1") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::ADMIN);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "2") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::AUTHOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "3") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::COLLABORATOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "4") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::CONSULTANT);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "5") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::CONSUMER);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "6") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::CONTRIBUTOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "7") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::COORDINATOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "8") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::CREATOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "9") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::DEVELOPER);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "10") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::DIRECTOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "11") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::EDITOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "12") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::EMPLOYEE);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "13") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::MAINTAINER);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "14") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::MANAGER);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "15") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::MODERATOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "16") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::PUBLISHER);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "17") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::REVIEWER);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "18") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::SUBSCRIBER);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "19") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::SUPER_ADMIN);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "20") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::SUPER_EDITOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "21") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::SUPER_MODERATOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                if ($value == "22") {
                    try {
                        $auth->admin()->addRoleForUserByUsername($username, Role::TRANSLATOR);
                    } catch (UnknownUsernameException $e) {
                        // unknown username
                    } catch (AmbiguousUsernameException $e) {
                        // ambiguous username
                    }
                }

                $productid_arr[] = $value;
                $productid[] = $data['name'];

            }
        } else {
            $userhaveroles = true;
        }

        $des_prod = implode(', ' , $productid);

        $des_prod_id = implode(',',$productid_arr);

        $updateStatement = $pdo->update(array('permissions' => $des_prod_id))
            ->table('users')
            ->where('username', '=', $username);

        $affectedRows = $updateStatement->execute();

        if ($affectedRows) {
            $updateUserPerm = true;
        }

    }

    echo '
    <!DOCTYPE html>
    <html lang="sk">
    <head>';

    $title = 'Právomoci';
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
                            <p>Administrácia - ' . $title . '</p>
                        </div>
                        <div class="panel-body">
                        
                            <div class="uk-padding-small">
        
                                <div class="uk-width-1-1 uk-text-center">
                                    <h2 class="uk-margin-medium uk-text-uppercase colorko-txt">Udelovanie právomocí</h2>
                                    <hr>
                                </div>';

                                if ($userhaveroles == true) {
                                    echo '
                                        <div class="uk-width-1-1" uk-alert>
                                            <a class="uk-alert-close" uk-close></a>
                                            <h4>Užívateľovi '.$username.' nemôžete udeliť práva:</h4>
                                            <p>Ak uživateľ už má nastavené nejaké práva nieje môžne mu pridať ďalšie, pre bezpečnosť. Pokračujte ďalej tak, že odstránite uživatelovi práva a potom môžte mu pridať iné.</p>
                                        </div>';
                                }

                                if ($des_prod_id && $updateUserPerm == true) {

                                    echo '
                                        <div class="uk-width-1-1" uk-alert>
                                            <a class="uk-alert-close" uk-close></a>
                                            <h4>Užívateľovi '.$username.' boli udelené práva:</h4>
                                            <p> '.$des_prod.'</p>
                                        </div>';
                                }

                                $selectNamePermission = $pdo->select()
                                    ->from('users_permissions');

                                $stmtNamePermission = $selectNamePermission->execute();

                                echo '
                                <div class="uk-width-1-1 uk-text-center">
                                    <form class="search-box" method="post">
                                        <div class="uk-margin">
                                            <div class="uk-inline">
                                                <span class="uk-form-icon" uk-icon="icon: user"></span>
                                                <input class="uk-input uk-form-width-large" type="text" name="username" id="search-box" placeholder="ID/Email/Username" autocomplete="off">
                                                <div class="result"></div>
                                            </div>
                                        </div>
                                        <div class="uk-child-width-1-1 uk-margin-auto">
                                            <div class="uk-margin uk-grid-small uk-child-width-1-2 uk-grid uk-margin-auto uk-text-left">';

                                            while ($dataNamePermission = $stmtNamePermission->fetch()) {

                                                echo '<label><input class="uk-checkbox" type="checkbox" value="'.$dataNamePermission['id'].'" name="permissions[]"> '.$dataNamePermission['name'].'</label>';

                                            }

                                            echo '
                                            </div>
                                        </div>
                                        <div class="uk-margin">
                                            <button type="submit" name="add" class="button"><span class="uk-margin-small-right" uk-icon="icon: plus"></span> Udeliť práva</button>
                                        </div>
                                    </form>
                                </div>';

                            echo '<table class="uk-table uk-table-middle uk-table-small uk-table-divider">
                                    <caption>Používatelia, ktorí majú práva</caption>
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">#ID</th>
                                            <th style="width: 30%;">Užívateľské meno</th>
                                            <th style="width: 50%;">Práva</th>
                                            <th style="width: 10%;">Akcia</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                                        $selectStatement = $pdo->select()
                                            ->from('users')
                                            ->where('roles_mask', '>', '0')
                                            ->orderBy('roles_mask', 'DESC');

                                        $stmt = $selectStatement->execute();

                                        echo '<form method="post" action="">';

                                        while ($data = $stmt->fetch()) {

                                            if ($data['permissions']) {

                                                $tmpArray[$data['id']] = explode(",", $data['permissions']);

                                                foreach ($tmpArray[$data['id']] as $rights[$data['id']]) {
                                                    $selectStatementp = $pdo->select()
                                                        ->from('users_permissions')
                                                        ->where('id', '=', $rights[$data['id']]);

                                                    $stmtp = $selectStatementp->execute();
                                                    $dataperm = $stmtp->fetch();

                                                    $perms[$data['id']][] = ucfirst(strtolower($dataperm['name'])) . ',';
                                                }

                                                $resultperm[$data['id']] = implode(' ', $perms[$data['id']]);

                                            } else {
                                                $resultperm[$data['id']] = 'Member';
                                            }

                                            if (isset($_POST['removePermID' . $data['id']])) {

                                                header("Refresh:3; url=".URL.$_SERVER['PHP_SELF']);

                                                if ($data['id'] != 1) {

                                                    $updateStatement = $pdo->update(array('permissions' => '0'))
                                                        ->set(array('roles_mask' => '0'))
                                                        ->table('users')
                                                        ->where('id', '=', $data['id']);

                                                    $affectedRows = $updateStatement->execute();
                                                    if ($affectedRows) {
                                                        echo '<div class="uk-alert-success" uk-alert>
                                                        <a class="uk-alert-close" uk-close></a>
                                                        <p>Užívateľovi s ID <strong>' . $data['id'] . '</strong>, boli odobraté práva.</p>
                                                      </div>';
                                                    } else {
                                                        echo '<div class="uk-alert-danger" uk-alert>
                                                        <a class="uk-alert-close" uk-close></a>
                                                        <p>Niekedy sa vyskytla chyba alebo používateľ nemá žiadne práva.</p>
                                                      </div>';
                                                    }
                                                } else {
                                                    echo '<div class="uk-alert-warning" uk-alert>
                                                            <a class="uk-alert-close" uk-close></a>
                                                            <p>Majiteľovi webovej stránky nemôže nikto odobrať práva.</p>
                                                          </div>';
                                                }
                                            }


                                            echo '
                                            <tr>
                                                <td># '.$data['id'].'</td>
                                                <td><img class="uk-preserve-width uk-border-circle uk-margin-right" src="'.URL.'/uploads/avatars/'.$data['avatar'].'" style="width:30px;height:30px;" alt="'.$data['username'].'"> <span class="uk-text-middle">'.$data['username'].'</span></td>
                                                <td class="uk-text-truncate" title="'.rtrim($resultperm[$data['id']],",").'">'.rtrim($resultperm[$data['id']],",").'</td>
                                                <td>
                                                    <button type="submit" name="removePermID'.$data['id'].'" class="uk-button uk-button-link colorko-txt" title="Odstrániť práva" uk-tooltip><span uk-icon="icon: trash"></span></button>
                                                </td>
                                            </tr>';

                                        }

                                    echo '
                    
                                        </form>
                
                                    </tbody>
                                </table>
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

    <script type="text/javascript">
    $(document).ready(function(){
        $(\'#search-box\').on("keyup input", function(){
            /* Get input value on change */
            var inputVal = $(this).val();
            var resultDropdown = $(this).siblings(".result");
            if(inputVal.length){
                $.get("app/search.php", {term: inputVal}).done(function(data){
                    // Display the returned data in browser
                    resultDropdown.html(data);
                });
            } else{
                resultDropdown.empty();
            }
        });
        
        // Set search input value on click of result item
        $(document).on("click", ".result p", function(){
            $(this).parents(".search-box").find(\'input[type="text"]\').val($(this).text());
            $(this).parent(".result").empty();
        });

    });
    </script>

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