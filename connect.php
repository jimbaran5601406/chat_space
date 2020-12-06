<?php
require './env.php';

try {
    $dsn = 'mysql:dbname=social-networking-service;host=localhost;charset=utf8';
    $username = getenv('DB_USERNAME');
    $password = getenv('DB_PASSWORD');
    $db = new PDO($dsn, $username, $password);
    print 'PDOによる接続に成功しました。';
} catch(PDOException $e) {
    echo('DB接続エラー：'.$e->getMessage());
}