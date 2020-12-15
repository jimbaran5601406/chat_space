<?php

function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

function fetch_user($db, $id)
{
    $stmt = $db->prepare("SELECT * FROM users WHERE id=:id");
    $stmt->execute(array(':id' => $id));
    $user = $stmt->fetch();
    return $user;
}

function fetch_all_posts($db, $user_id)
{
    $stmt = $db->prepare("SELECT
                                            u.name,
                                            u.photo,
                                            p.*
                                        FROM
                                            users u,
                                            posts p
                                        WHERE
                                            u.id=$user_id
                                        ORDER BY
                                            p.created_at
                                        DESC"
                                        );
    $stmt->execute();
    $posts = $stmt->fetchAll();
    return $posts;
}

function setup_auto_login($db, $user_id)
{
	$auto_login_key = hash('sha256', random_bytes(32));
    $expire_at = date('Y-m-d H:i:s', time()+60*60*24*7);
	$stmt = $db->prepare("UPDATE users SET auto_login_key=:auto_login_key, expire_at=:expire_at WHERE id=$user_id");
	$stmt->execute(array(
		':auto_login_key' => $auto_login_key,
		':expire_at' => $expire_at
    ));
    //有効期限7日の自動ログインクッキーをセット
    setcookie('auto_login', $auto_login_key, time()+60*60*24*7);
}

function reset_auto_login($db, $user_id)
{
    $stmt = $db->prepare("UPDATE users SET auto_login_key='', expire_at='' WHERE auto_login_key=:auto_login_key");
    $stmt->execute(array(
        ':auto_login_key' => $_COOKIE['auto_login']
    ));
	setcookie('auto_login', "", time()-1);
}