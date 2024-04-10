<?php

$selectSettings = $pdo->select()
    ->from('settings')
    ->where('id', '=', 1);

$querySettings = $selectSettings->execute();
$dataSettings = $querySettings->fetch();

define( 'URL',  $dataSettings['HTTP_Secure'].$dataSettings['url']);

echo '
    <title>'.$dataSettings['title'].' - '.$title.'</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="pallax.systems, Patrik CrACK Fejfár">
    <meta name="description" content="Váše neobmedzené herné hranice.">
    <meta name="keywords" content="secure, bezpečnosť, flat, jednoduchosť, crack, system, systems, cms, na mieru, systemy">
    <meta name="robots" content="index, nofollow">
    <meta name="revisit-after" content="1 day">
    <meta name="language" content="sk">
    <meta name="generator" content="N/A">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#140f17">
    <meta name="msapplication-navbutton-color" content="#140f17">
    <meta name="msapplication-TileColor" content="#ffffff">
    <link rel="stylesheet" href="'.URL.'/assets/css/normalize.css">
    <link rel="stylesheet" href="'.URL.'/assets/css/uikit.min.css">
    <link rel="stylesheet" href="'.URL.'/assets/css/styles.css">';