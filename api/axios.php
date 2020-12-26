<?php
require_once '../env.php';

try {
    $dsn = 'mysql:dbname=social-networking-service;host=localhost;charset=utf8';
    $db_username = getenv('DB_USERNAME');
    $db_password = getenv('DB_PASSWORD');
    $db = new PDO($dsn, $db_username, $db_password);
} catch(PDOException $e) {
    echo('DB接続エラー：'.$e->getMessage());
}

$data = json_decode(file_get_contents("php://input"), TRUE);
$id = $data['id'];
$is_liked = $data['is_liked'];

$stmt = $db->prepare("UPDATE
                            posts
                        SET
                            is_liked=:is_liked,
                            updated_at=NOW()
                        WHERE
                            id=:id
                    ");
$stmt->bindValue(':is_liked', $is_liked, PDO::PARAM_INT);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
