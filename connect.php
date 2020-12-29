<?php

try {
    if($_SERVER["HTTP_HOST"] == 'chat_space.localhost') {
        $db_name = getenv('DEV_DB_NAME');
        $db_username = getenv('DEV_DB_USERNAME');
        $db_password = getenv('DEV_DB_PASSWORD');
        $dsn = 'mysql:dbname='.$db_name.';host=localhost;charset=utf8';
        $db = new PDO($dsn, $db_username, $db_password);
    } else {
        $db_name = getenv('LIVE_DB_NAME');
        $db_username = getenv('LIVE_DB_USERNAME');
        $db_password = getenv('LIVE_DB_PASSWORD');
        $db_host = getenv('LIVE_DB_HOST');
        $dsn = 'mysql:dbname='.$db_name.';host='.$db_host.';charset=utf8';
        $db = new PDO($dsn, $db_username, $db_password);
    }
} catch(PDOException $e) {
    echo('DB接続エラー：'.$e->getMessage());
}