<?php require_once "require/head.php";?>
<body class="profilebody">
    <?php require_once "require/header.php";?>
    <div class="content">
		<h2>Profile Page</h2>
		<div>
			<p>Your account details are below:</p>
			<table>
				<tr>
					<td>Username:</td>
					<td><?=$_SESSION['name']?></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><?="**********"?></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><?=$data["email"]?></td>
				</tr>
				<tr>
					<td>Mail notification:</td>
					<td><?php if ($data["preference"]) {echo "activated";} else {echo "inactivated";}?></td>
				</tr>
			</table>
			<a href="/camagru/profile/modify" class="green-button">Change details</a>
		</div>
	</div>
</body>
<?php require_once "require/footer.php"?>