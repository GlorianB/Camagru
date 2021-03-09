<?php if (isset($_SESSION["loggedin"])):?>

<nav class="navtop">
	<div>
		<h1><a href="/camagru">Camagru</a></h1>
		<a href="/camagru/profile"><i class="fas fa-user-circle"></i><?php echo $_SESSION["name"];?></a>
		<a href="/camagru/login/logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
	</div>
</nav>

<?php else:?>

<nav class="navtop">
	<div>
		<h1><a href="/camagru/home">Camagru</a></h1>
		<a href="/camagru/register"><i class="fas fa-user-circle"></i>Register</a>
		<a href="/camagru/login"><i class="fas fa-sign-out-alt"></i>Login</a>
	</div>
</nav>

<?php endif;?>