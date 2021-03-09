<?php require_once "require/head.php"?>

<body class="loginbody">
    <?php require_once "require/header.php"?>
	<div class="login">
	    <h1>Enter informations below</h1>
		<form action="/camagru/forgot/reset" method="post">
		    <label for="username"><i class="fas fa-user"></i></label>
		    <input type="text" name="username" placeholder="Username" id="username" required>
			<label for="email"><i class="fa fa-at"></i></label>
			<input type="text" name="email" placeholder="Email" id="email" required>
			<input type="submit" value="Get my password">
        </form>
	</div>
</body>
<?php require_once "require/footer.php"?>