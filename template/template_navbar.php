<?php

use Delight\Auth\Role;

echo '

<div class="menu-bg">
    <div class="uk-container menu uk-visible@s">
        <ul>
            <li><a href="' . URL . '">Domov</a></li>
            <li><a href="' . URL . '/forum/index.php">Fórum</a></li>
            <li><a href="' . URL . '/servers.php">Servery</a></li>
            <li><a href="index.php">Banlist</a></li>
            <li><a href="index.php">Tým</a></li>
            <li><a href="index.php">Štatistiky</a></li>
            <li><a style="border-right: 1px solid #211b25;" href="' . URL . '/vip.php">VIP</a></li>
        </ul>
    </div>
    <div class="uk-container menu uk-hidden@s">
        <ul class="uk-float-right" style="border: none;">
            <li><a class="media-small" href="#menu" uk-toggle="target: #menu"><span uk-navbar-toggle-icon></span></a></li>
        </ul>
    </div>
</div>

<div id="menu" uk-offcanvas="mode: push; overlay: true; flip: true">
    <div class="uk-offcanvas-bar">

        <ul class="uk-nav uk-nav-default">
            <li><a href="' . URL . '">Domov</a></li>
            <li><a href="' . URL . '/forum/index.php">Fórum</a></li>
            <li><a href="' . URL . '/servers.php">Servery</a></li>
            <li><a href="index.php">Banlist</a></li>
            <li><a href="index.php">Tým</a></li>
            <li><a href="index.php">Štatistiky</a></li>
            <li><a href="' . URL . '/vip.php">VIP</a></li>';
            if ($auth->isLoggedIn()) {
                echo '
                <li class="uk-nav-header">'.$auth->getUsername().'</li>
                <li><a href="' . URL . '/profile.php?user=' . $auth->getUserId() . '">Profil</a></li>
                <li><a href="' . URL . '/edit_profile.php">Upraviť profil</a></li>
                <li><a href="' . URL . '/users.php">Užívatelia</a></li>';
                if ($auth->hasAnyRole(Role::SUBSCRIBER, Role::SUPER_ADMIN, Role::SUPER_EDITOR, Role::SUPER_MODERATOR, Role::TRANSLATOR)) {
                    echo '<li><a href="' . URL . '/admin/index.php">Administrácia</a></li>';
                }
                echo '<li><a href="' . URL . '/logout.php">Odhlásiť sa</a></li>';
            }
            echo '
        </ul>

    </div>
</div>

<div class="header '.($_SERVER['PHP_SELF'] != "/domains/gamestroke.eu/index.php" ? "header-small" : "").'">
    <div class="uk-container">
        <h1>ricsi.system</h1>
        <h2>Váše neobmedzené herné hranice</h2>
        <h3 '.($_SERVER['PHP_SELF'] != "/domains/gamestroke.eu/index.php" ? "class='uk-visible@s'" : "").'>Prajeme veľa zábavy </h3>
    </div>
</div>';

if ($auth->isLoggedIn()) {

    $selectStatement = $pdo->select()
        ->from('users')
        ->where('id', '=', $auth->getUserId());

    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();

    echo '
    <div class="login-bg uk-visible@s">
        <div class="uk-container">
        
            <div uk-grid>
                
                <div style="padding-top: 7px;">
                    <div class="login-avatar"><img src="'.URL.'/uploads/avatars/'.$data['avatar'].'" alt="'.$data['username'].'"></div>
                    <p class="uk-margin-small-top uk-margin-large-left">Vitajte <span style="color: #df3a3a;">'.$data['username'].'</span></p>
                </div>
                
                <div class="uk-width-expand">
                    <div style="margin-right: -41px; float: right">
                        <ul>
                            <li><a href="'.URL.'/profile.php?user='.$data['id'].'">Profil</a></li>
                            <li><a href="'.URL.'/edit_profile.php">Upraviť profil</a></li>
                            <li><a href="' . URL . '/users.php">Užívatelia</a></li>';
                            if ($auth->hasAnyRole(Role::SUBSCRIBER, Role::SUPER_ADMIN, Role::SUPER_EDITOR, Role::SUPER_MODERATOR, Role::TRANSLATOR)) {
                                echo '<li><a href="'.URL.'/admin/index.php" style="color: #df3a3a;">Administrácia</a></li>';
                            }
                            echo '
                            <li><a href="'.URL.'/logout.php">Odhlásiť sa</a></li>
                        </ul>
                    </div>
                </div>
            
            </div>
            
        </div>
    </div>
    <div class="login-bg uk-hidden@s">
        <div class="uk-container">
        
            <div uk-grid>
                
                <div style="padding-top: 7px;">
                    <div class="login-avatar"><img src="'.URL.'/uploads/avatars/'.$data['avatar'].'" alt="'.$data['username'].'"></div>
                    <p class="uk-margin-small-top uk-margin-xlarge-left">Vitajte <span style="color: #df3a3a;">'.$data['username'].'</span></p>
                </div>
            
            </div>
            
        </div>
    </div>';
} else {
    echo '
    <div class="login-bg uk-visible@s">
        <div class="uk-container">
            <form action="' . URL . '/login.php" method="post" accept-charset="utf-8">
                <div uk-grid>
                    <div><p class="uk-margin-top">Prihlásiť sa</p></div>
                    <div>
                        <div style="margin-top:18px;">
                            <div class="uk-inline">
                                <span class="uk-form-icon" uk-icon="icon: users"></span>
                                <input class="uk-input" type="email" name="email" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div style="margin-top:18px;">
                            <div class="uk-inline">
                                <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                <input class="uk-input" type="password" name="password" placeholder="********">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" name="login" class="login-button">Prihlásiť</button>
                    </div>
                    <div class="uk-width-expand">
                        <div style="margin-right: -41px; float: right">
                            <ul>
                                <li><a href="'.URL.'/register.php">Zaregistrovať sa</a></li>
                                <li><a href="'.URL.'/forgot_password.php">Zabudol si heslo ?</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="login-bg uk-hidden@s">
        <div class="uk-container">
            <div uk-grid>
                <div class="uk-width-1-1@s">
                    <p class="uk-margin-top">
                        <a class="uk-margin-right" href="'.URL.'/login.php" style="color: #e4cff0;">Prihlásiť sa</a>
                        <a href="'.URL.'/register.php" style="color: #e4cff0;">Zaregistrovať sa</a>
                    </p>
                </div>
            </div>
        </div>
    </div>';
}

echo '<div class="login-bg-bottom-border"></div>';