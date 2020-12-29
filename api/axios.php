<?php
require_once '../env.php';
require_once '../connect.php';

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
