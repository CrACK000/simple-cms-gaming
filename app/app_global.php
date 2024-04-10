<?php

function parsebb($body) {
    $find = array(
        "@\n@",
        "@[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]@is",
        "/\[url\=(.+?)\](.+?)\[\/url\]/is",
        "/\[b\](.+?)\[\/b\]/is",
        "/\[i\](.+?)\[\/i\]/is",
        "/\[u\](.+?)\[\/u\]/is",
        "/\[color\=(.+?)\](.+?)\[\/color\]/is",
        "/\[size\=(.+?)\](.+?)\[\/size\]/is",
        "/\[font\=(.+?)\](.+?)\[\/font\]/is",
        "/\[center\](.+?)\[\/center\]/is",
        "/\[right\](.+?)\[\/right\]/is",
        "/\[left\](.+?)\[\/left\]/is",
        "/\[img\](.+?)\[\/img\]/is",
        "/\[email\](.+?)\[\/email\]/is"
    );
    $replace = array(
        "<br />",
        "<a href=\"\\0\">\\0</a>",
        "<a href=\"$1\" target=\"_blank\">$2</a>",
        "<strong>$1</strong>",
        "<em>$1</em>",
        "<span style=\"text-decoration:underline;\">$1</span>",
        "<font color=\"$1\">$2</font>",
        "<font size=\"$1\">$2</font>",
        "<span style=\"font-family: $1\">$2</span>",
        "<div style=\"text-align:center;\">$1</div>",
        "<div style=\"text-align:right;\">$1</div>",
        "<div style=\"text-align:left;\">$1</div>",
        "<img src=\"$1\" alt=\"Image\" />",
        "<a href=\"mailto:$1\" target=\"_blank\">$1</a>"
    );
    $body = htmlspecialchars($body);
    $body = preg_replace($find, $replace, $body);
    return $body;
}

function htmlcode($text) {

    $search = array(
      '<',
      '>',
      '=',
      "'"
    );

    $replace = array(
      '&#60;',
      '&#62;',
      '&#61;',
      '&#39;'
    );

    $htmlcode = str_replace($search, $replace, $text);
    return $htmlcode;
}

function time_since($since) {
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
        array(1 , 'second')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name.' ago' : "$count {$name}s ago";
    return $print;
}