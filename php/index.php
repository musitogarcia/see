<?php

if (!isset($_COOKIE['ciclo'])) {
    setcookie('ciclo', $_POST['ciclo'], time() + (86400));
    print("<!--COOKIE" . $_POST['ciclo'] . "-->");
} else {
    if (isset($_POST['ciclo'])) {
        if ($_POST['ciclo'] != $_COOKIE['ciclo'])
            setcookie('ciclo', $_POST['ciclo'], time() + (86400));
    }
}
