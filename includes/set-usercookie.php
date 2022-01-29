<?php
    if(!isset($_COOKIE["usercookie"])) {
        session_start();
        $usercookie = session_id();
        setcookie("usercookie", $usercookie, time() + (86400 * 90), "/reseptisivusto", "", true);
    }
?>