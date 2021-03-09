<?php require_once "require/head.php"?>

<body class="loginbody">
    <?php require_once "require/header.php"?>
	<div class="login">
	    <h1>Login</h1>
		<form action="/camagru/login/authenticate" method="post">
		    <label for="username"><i class="fas fa-user"></i></label>
		    <input type="text" name="username" placeholder="Username" id="username" required>
			<label for="password"><i class="fas fa-lock"></i></label>
			<input type="password" name="password" placeholder="Password" id="password" required>
			<input type="submit" value="Login">
        </form>
	</div>
            <form class="forgot-password" action="/camagru/forgot" method="post">
                <input type="submit" value="Forgot password ?">
            </form>
</body>
<?php require_once "require/footer.php"?>