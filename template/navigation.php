<?php
// registerフォルダ内
if(strpos($_SERVER['REQUEST_URI'], 'register')) {
  require_once '../functions.php';
  require_once '../env.php';
  require_once '../connect.php';
} else {
  require_once './functions.php';
  require_once './env.php';
  require_once './connect.php'; 
}

// ログインでも登録画面でもない
if($_SERVER['REQUEST_URI'] !== '/login.php' && !strpos($_SERVER['REQUEST_URI'], 'register')) {
  session_start();

  if(isset($_SESSION['id']) && $_SESSION['login_time'] + 3600 > time()) {
	  $_SESSION['login_time'] = time();

    $user = fetch_user($db, $_SESSION['id']);

    if(!empty($_POST['setting'])) {
      header('Location: setting.php');
      exit();
    }
    if(!empty($_POST['logout'])) {
      $_SESSION = array();
      session_destroy();
      reset_auto_login($db, $user['id']);

      session_start();
      $_SESSION['after_action_msg'] = "ログアウトが完了しました";
	    header('Location: login.php');
	    exit();
    }
  } else {
	  header('Location: login.php');
	  exit();
  }
}
?>

<form action="" method="post">
  <ul id="profile_dropdown" class="dropdown-content profile-dropdown">
    <li><a href="../profile.php?user_id=<?= h($user['id']) ?>"><i class="material-icons left">account_circle</i>プロフィール</a></li>
    <li class="divider"></li>
    <li><button class="btn-style-reset" type="submit" name="setting" value="1"><i class="material-icons left">settings</i>設定</button></li>
  </ul>
  <ul id="smp_profile_dropdown" class="dropdown-content profile-dropdown">
    <li><a href="../profile.php?user_id=<?= h($user['id']) ?>"><i class="material-icons left">account_circle</i>プロフィール</a></li>
    <li><button class="btn-style-reset" type="submit" name="setting" value="1"><i class="material-icons left">settings</i>設定</button></li>
  </ul>
  <nav>
    <div class="nav-wrapper blue">
      <a href="../index.php" id="app_name">Chat Space</a>
      <?php if(isset($user)): ?>
      <a href="#" data-target="smp-drawer" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
          <li><a class="dropdown-trigger" href="#!" data-target="profile_dropdown"><?= h($user['name']) ?><i class="material-icons right">arrow_drop_down</i></a></li>
          <li><a href="../index.php">ホーム</a></li>
          <li><button class="btn-logout btn-style-reset" type="submit" name="logout" value="1">ログアウト</button></li>
        </ul>
      <?php endif; ?>
    </div>
  </nav>

  <?php if(isset($user)): ?>
  <ul class="sidenav" id="smp-drawer">
    <li><a class="dropdown-trigger" href="#!" data-target="smp_profile_dropdown"><?= h($user['name']) ?><i class="material-icons right">arrow_drop_down</i></a></li>
    <li><a href="../index.php">ホーム</a></li>
    <li>
      <button class="btn-logout btn-style-reset" type="submit" name="logout" value="1">ログアウト</button>
    </li>
  </ul>
  <?php endif; ?>
</form>

<?php if(!isset($user)): ?>
<style>
#app_name {
  margin-right: 0;
}
</style>
<?php endif; ?>