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

    $title = 'Fórum pod-kategórie';
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
                        
                            <div class="uk-padding-small">';

                                if ($_GET['edit']) {

                                    $selectForumCategory = $pdo->select()
                                        ->from('forum_underCategory')
                                        ->where('id', '=', $_GET['edit']);

                                    $stmtForumCategory = $selectForumCategory->execute();
                                    $dataForumCategory = $stmtForumCategory->fetch();

                                    if (isset($_POST['editFormCategory'])) {
                                        $updateStatementForumCategorye = $pdo->update(array('title' => $_POST['formCategoryTitle']))
                                            ->set(array('description' => $_POST['formCategoryDescription']))
                                            ->set(array('category' => $_POST['formCategoryCategory']))
                                            ->table('forum_underCategory')
                                            ->where('id', '=', $_GET['edit']);

                                        $affectedRowsForumCategorye = $updateStatementForumCategorye->execute();

                                        if ($affectedRowsForumCategorye) {
                                            header('Location: ' . URL . '/admin/forum_underCategory.php');
                                        }
                                    }

                                    echo '                        
                                    <form method="post" class="uk-form-stacked uk-width-1-2 uk-text-center uk-margin-auto">
                                        <div class="uk-margin-small">
                                            <label class="uk-form-label" for="form-stacked-text">Názov kategórie</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" name="formCategoryTitle" id="form-stacked-text" value="'.$dataForumCategory['title'].'" type="text" placeholder="Example">
                                            </div>
                                        </div>
                                        <div class="uk-margin-small">
                                            <label class="uk-form-label" for="form-stacked-texts">Krátky popis</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" name="formCategoryDescription" id="form-stacked-texts" value="'.$dataForumCategory['description'].'" type="text" placeholder="Lorem Ipsum is simply dummy">
                                            </div>
                                        </div>
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-stacked-select">Kategória</label>
                                            <div class="uk-form-controls">
                                                <select class="uk-select" name="formCategoryCategory" id="form-stacked-select">';

                                                $selectForumcat = $pdo->select()
                                                    ->from('forum_category');

                                                $stmtForumcat = $selectForumcat->execute();

                                                while ($dataForumcat = $stmtForumcat->fetch()) {
                                                    echo '<option value="'.$dataForumcat['id'].'" '.($dataForumCategory['category'] == $dataForumcat['id'] ? 'selected' : '').'>'.$dataForumcat['title'].'</option>';
                                                }

                                                echo '
                                                </select>
                                            </div>
                                        </div>
                                        <div class="uk-margin-small">
                                            <button class="button" name="editFormCategory" type="submit">Uložiť</button>
                                        </div>
                                    </form>';

                                } else {

                                    if (isset($_POST['addFormCategory'])) {
                                        $insertStatement = $pdo->insert(array('title', 'description', 'category'))
                                            ->into('forum_underCategory')
                                            ->values(array($_POST['formCategoryTitle'], $_POST['formCategoryDescription'], $_POST['formCategoryCategory']));

                                        $insertId = $insertStatement->execute(false);

                                        if ($insertId) {
                                            header('Location: ' . URL . '/admin/forum_underCategory.php');
                                        }
                                    }

                                    echo '                        
                                    <form method="post" class="uk-form-stacked uk-width-1-2 uk-text-center uk-margin-auto">
                                        <div class="uk-margin-small">
                                            <label class="uk-form-label" for="form-stacked-text">Názov kategórie</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" name="formCategoryTitle" id="form-stacked-text" type="text" placeholder="Example">
                                            </div>
                                        </div>
                                        <div class="uk-margin-small">
                                            <label class="uk-form-label" for="form-stacked-texts">Krátky popis</label>
                                            <div class="uk-form-controls">
                                                <input class="uk-input" name="formCategoryDescription" id="form-stacked-texts" type="text" placeholder="Lorem Ipsum is simply dummy">
                                            </div>
                                        </div>
                                        <div class="uk-margin">
                                            <label class="uk-form-label" for="form-stacked-select">Kategória</label>
                                            <div class="uk-form-controls">
                                                <select class="uk-select" name="formCategoryCategory" id="form-stacked-select">';

                                                $selectForumcat = $pdo->select()
                                                    ->from('forum_category');

                                                $stmtForumcat = $selectForumcat->execute();

                                                while ($dataForumcat = $stmtForumcat->fetch()) {
                                                    echo '<option value="'.$dataForumcat['id'].'">'.$dataForumcat['title'].'</option>';
                                                }

                                                echo '
                                                </select>
                                            </div>
                                        </div>
                                        <div class="uk-margin-small">
                                            <button class="button" name="addFormCategory" type="submit">Pridať</button>
                                        </div>
                                    </form>
                                    
                                    <hr>
                            
                                    <table class="my-table uk-width-1-1 uk-table-middle uk-margin-auto" cellspacing="0" cellpadding="0">
                                        <thead>
                                            <tr>
                                                <td class="uk-text-center" width="6%">ID</td>
                                                <td width="26%">Kategória</td>
                                                <td width="38%">Názov</td>
                                                <td width="30%">Akcia</td>
                                            </tr>
                                        </thead>
                                        <tbody>';

                                            $selectPermissions = $pdo->select()
                                                ->from('forum_underCategory')
                                                ->orderBy('id', 'ASC');

                                            $stmtPermissions = $selectPermissions->execute();

                                            while ($dataPermissions = $stmtPermissions->fetch()) {

                                                if (isset($_POST['deleteFormCategory' . $dataPermissions['id']])) {
                                                    $deleteDeleteForumCategory = $pdo->delete()
                                                        ->from('forum_underCategory')
                                                        ->where('id', '=', $dataPermissions['id']);

                                                    $affectedRowsDeleteForumCategory = $deleteDeleteForumCategory->execute();

                                                    if ($affectedRowsDeleteForumCategory){
                                                        header('Location: ' . URL . '/admin/forum_underCategory.php');
                                                    }
                                                }

                                                $selectStatementy = $pdo->select()
                                                    ->from('forum_category')
                                                    ->where('id', '=', $dataPermissions['category']);

                                                $stmty = $selectStatementy->execute();
                                                $datay = $stmty->fetch();

                                                echo '
                                                <tr>
                                                    <td class="uk-text-center"><strong>' . $dataPermissions['id'] . '</strong>.</td>
                                                    <td>' . $datay['title'] . '</td>
                                                    <td>' . $dataPermissions['title'] . '</td>
                                                    <td>
                                                        <a href="'.URL.'/admin/forum_underCategory.php?edit=' . $dataPermissions['id'] . '"><button type="button" class="button uk-margin-remove">Upraviť</button></a>
                                                        <form method="post" class="uk-display-inline uk-margin-remove"><button type="submit" name="deleteFormCategory' . $dataPermissions['id'] . '" class="button uk-margin-remove">Zmazať</button></form>
                                                    </td>
                                                </tr>';

                                        }

                                        echo '
                                        </tbody>
                                    </table>';

                            }

                            echo '
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

