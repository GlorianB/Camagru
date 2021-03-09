<?php require_once "require/head.php"?>

<body class="modifybody">
    <?php require_once "require/header.php"?>
	<div class="modify">
	    <h1>Change your informations</h1>
		<form action="/camagru/profile/modified" method="post">
        <label for="username"><i class="fas fa-user"></i></label>
			<input type="text" name="username" placeholder="Username" id="username" value="<?=$_SESSION["name"]?>" required>
			<label for="email"><i class="fa fa-at"></i></label>
			<input type="text" name="email" placeholder="Email" id="email" value="<?=$data["email"]?>" required>
			<label for="password"><i class="fas fa-lock"></i></label>
			<input type="password" name="password" placeholder="New password" id="password" required>
			<label for="confirm-password"><i class="fas fa-lock"></i></label>
			<input type="password" name="confirm-password" placeholder="Confirm new password" id="confirm-password" required>
			<label class="switch" for="profile_preference">Notification preference</label>
			<input type="checkbox" name="preference" value="On" id="profile_preference">
			<input type="submit" value="Validate change">
        </form>
	</div>
</body>
<?php require_once "require/footer.php"?>