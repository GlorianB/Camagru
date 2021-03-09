<?php require_once "require/head.php"?>
<body class="registerbody">
    <?php require_once "require/header.php"?>
	<div class="register">
	    <h1>Register</h1>
		<form action="/camagru/register/registration" method="post">
		    <label for="username"><i class="fas fa-user"></i></label>
		    <input type="text" name="username" placeholder="Username" id="username" required>
            <label for="email"><i class="fa fa-at"></i></label>
			<input type="text" name="email" placeholder="Email" id="email" required>
			<label for="password"><i class="fas fa-lock"></i></label>
			<input type="password" name="password" placeholder="Password" id="password" required>
			<input type="submit" value="Register">
        </form>
	</div>
</body>

<?php require_once "require/footer.php"?>