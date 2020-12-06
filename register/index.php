<?php
$title = 'Register';
require_once '../template/header.php';
?>

<div class="container">
	<div class="row">
		<h1>Sign UP</h1>
	</div>

	<div class="row">
		<form class="col s12" action="" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="input-field">
					<input class="validate" name="name" id="name" maxlength="255" type="text" >
					<label for="name">Name</label>
					<span class="helper-text" data-error="Wrong" data-success="Right"></span>
				</div>
			</div>
			<div class="row">
				<div class="input-field">
					<input class="validate" name="email" id="email" maxlength="255" type="email" >
					<label for="Email">Email</label>
					<span class="helper-text" data-error="Wrong" data-success="Right"></span>
				</div>
			</div>
			<div class="row">
				<div class="input-field">
					<input class="validate" name="password" id="password" maxlength="255" type="password" >
					<label for="password">Password</label>
					<span class="helper-text" data-error="Wrong" data-success="Right"></span>
				</div>
			</div>
			<div class="row">
				<button class="waves-effect waves-light btn" type="submit">Confirm</button>
			</div>
		</form>
	</div>
	<!-- row -->
</div>
<!-- container -->
</body>
</html>
