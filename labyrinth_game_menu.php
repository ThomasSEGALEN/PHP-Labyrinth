<?php
session_start();

$login = FALSE;
if (isset($_POST['username']) and (!empty($_POST['username']))) {
	$login = TRUE;
} else {
	$login = FALSE;
}

if ($login == TRUE) {
    $_SESSION['username'] = $_POST['username'];
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Labyrinthe PHP</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="labyrinth_menu_css.css">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
</head>

<body>

	<div class="title">
		<img src="img/LabyrinthLogo.png"/>
		<h1>PHP Labyrinth Game</h1>
	</div>

	<div class="form">
		<form method="POST" action="" onSubmit="return validate();">
			<button class="openButton" onclick="openForm()">Authentification</button>
			<div class="formPopup" id="myForm">
				<form class="formContainer">
					<div class="fieldColumn">
						<div class="usernameLabel">
							<label for="username">Username</label>
							<span class="errorInfo" id="user_info"></span>
						</div>
						<div>
							<input class="inputBox" id="user_name" type="text" name="username">
						</div>
					</div>
					<div class="fieldColumn">
						<div>
							<input class="loginButton" type="submit" name="login" value="Confirm" alt="Login button">
						</div>
					</div>
					<button class="closeButton" onclick="closeForm()">Close</button>
				</form>
			</div>
		</form>
		<form>
			<div class="fieldColumn">
				<a href="./labyrinth_game.php">
					<input class="playButton" type="button" name="play" value="Play" alt="Play button">
				</a>
			</div>
		</form>
	</div>

	<footer>
		<div class="link social">
			<a class="footerEffect" href="https://www.linkedin.com/in/thomas-s%C3%A9galen" target="_blank">
				<img src="img/LinkedinLogo.png"/>
			</a>
			<a class="footerEffect" href="https://github.com/ThomasSEGALEN" target="_blank">
				<img src="img/GithubLogo.png"/>
			</a>
			<a class="footerEffect" href="mailto:segalen.thomas.pro@gmail.com" target="_blank">
				<img src="img/MailLogo.png"/>
			</a>
        </div>
        <span class="copyright">Developed & designed by Thomas SÉGALEN | © 2021</span>
	</footer>

	<script>
		function openForm() {
			document.getElementById("myForm").style.display = "block";
		}

		function closeForm() {
			document.getElementById("myForm").style.display = "none";
		}

		function validate() {
			var $valid = true;
			document.getElementById("user_info").innerHTML = "";

			var userName = document.getElementById("user_name").value;
			if (userName == "") {
				document.getElementById("user_info").innerHTML = "*without a username, your session won't be saved";
				$valid = false;
			}
			return $valid;
		}
	</script>

</body>

</html>