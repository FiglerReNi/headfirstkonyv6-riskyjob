<?php
//Karakter
ini_set('default_charset', 'utf-8');

//Állandók
define('HOST', "localhost");
define('USER', "root");
define('PASS', "");
define('AB', "riskyjob");

//Kapcsolat
$kapcs = mysqli_connect(HOST, USER, PASS, AB);
if (!$kapcs) {
    die(mysqli_connect_error());
}

//Karakter
mysqli_query($kapcs, "SET NAMES utf8");