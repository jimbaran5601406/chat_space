<?php

try {
    $dsn = 'mysql:dbname=social-networking-service;host=localhost;charset=utf8';
    $db_username = getenv('DB_USERNAME');
    $db_password = getenv('DB_PASSWORD');
    $db = new PDO($dsn, $db_username, $db_password);
} catch(PDOException $e) {
    echo('DB接続エラー：'.$e->getMessage());
}