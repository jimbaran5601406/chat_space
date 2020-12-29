<?php

$title = '設定/Chat Space';
require_once './template/header.php';

$id = h($_SESSION['id']);
$user_info = fetch_user($db, $id);

if(!empty($_SESSION['after_action_msg'])) {
	$after_action_msg = $_SESSION['after_action_msg'];
	unset($_SESSION['after_action_msg']);
}
if($_POST){
    if($_FILES['new_photo']['name'] || $_POST['new_name'] || $_POST['new_email'] || ($_POST['current_password'] && $_POST['new_password'])) {
        $new_photo_name = $_FILES['new_photo']['name'];
        $new_photo_name_for_upload = '';
        $new_photo_extension = strrchr($new_photo_name, '.');
        $new_photo_size = $_FILES['new_photo']['size'];
	    $new_name = h($_POST['new_name']);
	    $new_email = h($_POST['new_email']);
	    $current_password = h($_POST['current_password']);
        $new_password = h($_POST['new_password']);

        if ($new_photo_name) {
            $new_photo_name_for_upload = date('YmdHis').$new_photo_name;
            if ($new_photo_extension !== '.png' && $new_photo_extension !== '.jpeg' && $new_photo_extension !== '.jpg') { // 拡張子チェック
                $error_msg['new_photo_extension'] = '※新しい写真は[.png]、[.jpg]、[.jpeg]形式の写真を選択してください';
            }
            if ($new_photo_size > 10000000) { // 10MBサイズチェック
                $error_msg['new_photo_size'] = '※新しい写真は10メガバイト以下の写真を選択してください';
            }
        }
        if($new_name) {
            $stmt = $db->prepare("SELECT COUNT(*) as record_num FROM users WHERE email=:new_name");
		    $stmt->execute(array(':new_name' => $new_name));
		    $record_count = $stmt->fetch();
		    if($record_count['record_num'] > 0) {
			    $error_msg['new_name_duplicated'] = "※新しいユーザー名前は既に登録されています";
		    }
        }
        if($new_email) {
            $stmt = $db->prepare("SELECT COUNT(*) as record_num FROM users WHERE email=:new_email");
		    $stmt->execute(array(':new_email' => $new_email));
		    $record_count = $stmt->fetch();
		    if($record_count['record_num'] > 0) {
			    $error_msg['new_email_duplicated'] = "※新しいメールアドレスは既に登録されています";
		    }
        }
        if ($current_password && $new_password) {
            if(!password_verify($current_password, $user['password'])) {
                $error_msg['current_password_different'] = '※現在のパスワードが間違っています';
            }
            if(mb_strlen($new_password) < 8) {
                $error_msg['new_password_min'] = '※新しいパスワードは8文字以上で入力してください';
            }
        }
        if(empty($error_msg)) {
            if($new_photo_name) {
                // $_FILES['photo']['tmp_name']は一時的にイメージファイルが保存されている場所
                move_uploaded_file($_FILES['new_photo']['tmp_name'], './asset/images/user_images/'.$new_photo_name_for_upload);
            }
            update_setting($db, $id, $new_photo_name_for_upload, $new_name, $new_email, $new_password);
		    $_SESSION['after_action_msg'] = "アカウント情報の変更が完了しました！";

		    // 二重送信防止
		    header('Location: setting.php');
		    exit();
        }
    }
}

?>

<div class="container">
	<div class="row">
		<div class="col s12">
			<div class="row">
				<?php if(isset($after_action_msg)): ?>
					<p class="session-success-msg"><?= $after_action_msg ?></p>
                <?php endif;?>

                <form class="col s12" action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="file-field input-field">
                            <div class="btn">
                                <span>新しい写真を選択</span>
                                <input type="file" name="new_photo">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                            <?php if(isset($error_msg['new_photo_extension'])): ?>
                                <p class="red-text"><?= $error_msg['new_photo_extension'] ?></p>
                            <?php endif;?>
                            <?php if(isset($error_msg['new_name_required'])): ?>
                                <p class="red-text"><?= $error_msg['new_photo_size'] ?></p>
                            <?php endif;?>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <input class="validate" name="new_name" id="new_name" maxlength="255" type="text" value="<?= isset($new_name)? $new_name: ''; ?>">
                            <label for="new_name">新しいユーザー名</label>
                            <?php if(isset($error_msg['new_name_duplicated'])): ?>
                                <p class="red-text"><?= $error_msg['new_name_duplicated'] ?></p>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <input class="validate" name="new_email" id="new_email" maxlength="255" type="email" value="<?= isset($new_email)? $new_email: ''; ?>">
                            <label for="new_email">新しいメールアドレス</label>
                            <?php if(isset($error_msg['new_email_duplicated'])): ?>
                                <p class="red-text"><?= $error_msg['new_email_duplicated'] ?></p>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <input class="validate" name="current_password" id="current_password" maxlength="30" type="password" value="<?= isset($current_password)? $current_password: ''; ?>">
                            <label for="current_password">現在のパスワード</label>
                            <?php if(isset($error_msg['current_password_different'])): ?>
                                <p class="red-text"><?= $error_msg['current_password_different'] ?></p>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <input class="validate" name="new_password" id="new_password" maxlength="30" type="password" value="<?= isset($new_password)? $new_password: ''; ?>">
                            <label for="new_password">新しいパスワード</label>
                            <?php if(isset($error_msg['new_password_min'])): ?>
                                <p class="red-text"><?= $error_msg['new_password_min'] ?></p>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="row">
                        <button class="waves-effect waves-light btn" type="submit">変更</button>
                    </div>
		        </form>
            </div>
		</div>
	</div>
</div>
<!-- container -->
</body>
</html>
