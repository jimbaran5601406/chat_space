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

function fetch_all_posts($db, $page)
{
    $startPage = ($page - 1) * 10;

    $stmt = $db->prepare("SELECT
                            u.name,
                            u.photo,
                            p.*
                        FROM
                            users u
                        INNER JOIN
                            posts p
                        ON
                            u.id = p.user_id
                        WHERE
                            p.reply_message_id IS NULL
                        ORDER BY
                            p.created_at
                        DESC
                        LIMIT
                            :startPage, 10"
                        );
    $stmt->bindParam(':startPage', $startPage, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $posts;
}

function fetch_all_user_posts($db, $user_id)
{
    $stmt = $db->prepare("SELECT
                            u.name,
                            u.photo,
                            p.*
                        FROM
                            users u
                        INNER JOIN
                            posts p
                        ON
                            u.id = p.user_id
                        WHERE
                            p.user_id=$user_id
                        AND
                            p.reply_message_id IS NULL
                        AND
                            p.is_liked=0
                        ORDER BY
                            p.created_at
                        DESC"
                        );
    $stmt->execute();
    $user_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $user_posts;
}

function fetch_all_liked_posts($db, $user_id)
{
    $stmt = $db->prepare("SELECT
                            u.name,
                            u.photo,
                            p.*
                        FROM
                            users u
                        INNER JOIN
                            posts p
                        ON
                            u.id = p.user_id
                        WHERE
                            p.user_id=$user_id
                        AND
                            p.reply_message_id IS NULL
                        AND
                            p.is_liked=1
                        ORDER BY
                            p.created_at
                        DESC"
                        );
    $stmt->execute();
    $liked_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $liked_posts;
}

function fetch_post($db, $post_id)
{
    $stmt = $db->prepare("SELECT
                            u.name,
                            u.photo,
                            p.*
                        FROM
                            posts p
                        INNER JOIN
                            users u
                        ON p.user_id=u.id
                        WHERE
                            p.id=$post_id
                        ORDER BY
                            p.created_at
                        DESC"
                        );
    $stmt->execute();
    $post = $stmt->fetch();
    return $post;
}

function fetch_all_reply_posts($db, $post_id)
{
    $stmt = $db->prepare("SELECT
                            u.name,
                            u.photo,
                            p.*
                        FROM
                            users u,
                            posts p
                        WHERE
                            u.id=p.user_id
                        AND
                            p.reply_message_id=$post_id
                        ORDER BY
                            p.created_at
                        DESC"
                        );
    $stmt->execute();
    $reply_posts = $stmt->fetchAll();
    return $reply_posts;
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
    $stmt = $db->prepare("UPDATE
                            users
                        SET
                            auto_login_key=NULL,
                            expire_at=NULL
                        WHERE
                            id=:user_id
                        AND
                            auto_login_key=:auto_login_key
                        ");
    $stmt->execute(array(
        ':user_id' => $user_id,
        ':auto_login_key' => $_COOKIE['auto_login']
    ));
	setcookie('auto_login', "", time()-1);
}

function delete_post($db, $post_id)
{
    $stmt = $db->prepare("DELETE FROM posts WHERE id=:post_id");
	$stmt->execute(array(
		':post_id' => $post_id
	));
}

function update_setting($db, ...$setting_info)
{
    $id = $setting_info[0];
    $new_photo = $setting_info[1];
    $new_name = $setting_info[2];
    $new_email = $setting_info[3];
    $new_password = $setting_info[4] ? password_hash($setting_info[4], PASSWORD_DEFAULT) : '';

    if(!empty($new_photo)) {
        $stmt = $db->prepare("UPDATE
                                users
                            SET
                                photo=:new_photo,
                                updated_at=NOW()
                            WHERE
                                id=:id;
                            ");
        $stmt->execute(array(
            ':id' => $id,
            ':new_photo' => $new_photo
        ));
    }
    if(!empty($new_name)) {
        $stmt = $db->prepare("UPDATE
                                users
                            SET
                                name=:new_name,
                                updated_at=NOW()
                            WHERE
                                id=:id;
                            ");
        $stmt->execute(array(
            ':id' => $id,
            ':new_name' => $new_name
        ));
    }
    if(!empty($new_email)) {
        $stmt = $db->prepare("UPDATE
                                users
                            SET
                                email=:new_email,
                                updated_at=NOW()
                            WHERE
                                id=:id;
                            ");
        $stmt->execute(array(
            ':id' => $id,
            ':new_email' => $new_email
        ));
    }
    if(!empty($new_password)) {
        $stmt = $db->prepare("UPDATE
                                users
                            SET
                                password=:new_password,
                                updated_at=NOW()
                            WHERE
                                id=:id;
                            ");
        $stmt->execute(array(
            ':id' => $id,
            ':new_password' => $new_password
        ));
    }
}