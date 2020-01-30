<?php

include_once 'auth.php';

session_start();

$descr = $_POST['descr'];

$auth = new Auth();
$auth->setDescr($_SESSION['user']['name'], $descr);

$_SESSION['user']['description'] = $descr;