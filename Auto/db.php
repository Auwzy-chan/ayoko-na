<?php
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'car_db';
$DB_PORT = 3306;

function getDbConnection()
{
    static $connection = null;

    if ($connection === null) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $connection = new mysqli($GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASS'], '', $GLOBALS['DB_PORT']);
        $connection->query("CREATE DATABASE IF NOT EXISTS `" . $connection->real_escape_string($GLOBALS['DB_NAME']) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $connection->select_db($GLOBALS['DB_NAME']);
        $connection->set_charset('utf8mb4');
    }

    return $connection;
}
