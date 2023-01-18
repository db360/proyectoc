<?php
include __DIR__ . '/conf.php';

$opciones = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

$pdo = new PDO(DB_DSN, DB_USER, DB_PASSWD, $opciones);

?>