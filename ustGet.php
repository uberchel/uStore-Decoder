<?php

/***
 * Redirect module
 */

 if (empty($_GET['id']) || empty($_GET['token'])) {
    die('Hacking Attempt');
 }

readfile('https://red.uboost.one' . $_SERVER['REQUEST_URI']);
exit;

