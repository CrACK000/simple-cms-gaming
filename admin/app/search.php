<?php

require '../../vendor/autoload.php';
require '../../app/app_config.php';

use Delight\Auth\Auth;
use Delight\Auth\Role;
use Slim\PDO\Database;

$pdo    = new Database($dsn, $usr, $pwd);
$auth   = new Auth($pdo);

if ($auth->hasAnyRole( Role::AUTHOR, Role::CREATOR, Role::DIRECTOR, Role::SUPER_ADMIN)) {

    try {
        if (isset($_REQUEST['term'])) {

            $selectStatement = $pdo->select()
                ->from('users')
                ->WhereLike('id', '%' . $_REQUEST['term'] . '%')
                ->orWhereLike('email', '%' . $_REQUEST['term'] . '%')
                ->orWhereLike('username', '%' . $_REQUEST['term'] . '%')
                ->limit(7);


            $stmt = $selectStatement->execute();

            if ($stmt->rowCount() > 0) {
                while ($data = $stmt->fetch()) {
                    echo "<p class='uk-text-left'>" . $data['username'] . "</p>";
                }
            } else {
                echo "<p>Žiadne zhody nenájdené</p>";
            }
        }
    } catch (PDOException $e) {
        die("CHYBA: Nepodarilo sa vykonať $sql. " . $e->getMessage());
    }

}