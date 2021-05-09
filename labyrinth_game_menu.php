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
		<form method="POST" action="">
			<button class="authButton" id="open" onclick="openForm()">Authentification</button>
			<div class="formPopup" id="form">
				<form class="formContainer">
					<div class="fieldColumn">
						<div class="usernameLabel">
							<label for="username">Username</label>
						</div>
						<div>
							<input class="inputBox" type="text" name="username" required>
						</div>
					</div>
					<div class="fieldColumn">
						<div>
							<input class="loginButton" type="submit" name="login" value="Confirm" alt="Login button" onclick="test()">
						</div>
					</div>
					<!-- <button class="closeButton" onclick="closeForm()">Close</button> -->
				</form>
			</div>
		</form>
		<form>
			<div class="fieldColumn">
				<a href="" id="link" onclick="getLevel()">
					<input class="playButton" id="play" type="button" name="play" value="Play" alt="Play button">
				</a>
				<div class="levelButton">
					<input type="radio" id="level1" name="level" value="Level1">
					<label for="level1">Level 1</label>
					<input type="radio" id="level2" name="level" value="Level2">
					<label for="level2">Level 2</label>
					<input type="radio" id="level3" name="level" value="Level3">
					<label for="level3">Level 3</label>
				</div>
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

	<script language="JavaScript" type="text/javascript">
		/* JavaScript function which open the form when clicking on Authentification button */
		function openForm() {
			if(document.getElementById('open').value == 0) {
				document.getElementById("form").style.display = "block";
			}
		}

		function getLevel() {
			if(document.getElementById('level1').checked) {
				document.getElementById('link').href = "./labyrinth_game.php?init=./labyrinth_level1.txt"
			} else if(document.getElementById('level2').checked) {
				document.getElementById('link').href = "./labyrinth_game.php?init=./labyrinth_level2.txt"
			} else if(document.getElementById('level3').checked) {
				document.getElementById('link').href = "./labyrinth_game.php?init=./labyrinth_level3.txt"
			} else {
				document.getElementById('link').href = "./labyrinth_game.php?init=./labyrinth_level1.txt"
			}
		}

		/* JavaScript function which close the form when clicking on Close button - not used because not necessary */
		// function closeForm() {
		// 	document.getElementById("form").style.display = "none";
		// }
	</script>

</body>

</html>