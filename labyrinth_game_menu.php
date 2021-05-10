<?php
session_start();

// save the posted username into a session variable
if (isset($_POST['username']) and (!empty($_POST['username']))) {
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

	<div class="logo">
		<img src="img/LabyrinthLogo.png">
		<a href="./labyrinth_game_menu.php">
			<h1 class="title">PHP Labyrinth Game</h1>
		</a>
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
							<input class="loginButton" type="submit" name="login" value="Confirm" alt="Login button">
						</div>
					</div>
					<!-- <button class="closeButton" onclick="closeForm()">Close</button> -->
				</form>
			</div>
		</form>
		<form>
			<div class="fieldColumn">
				<a id="link" href="<?php if (isset($_SESSION['username'])) {
									} ?>" onclick="getLevel()">
					<input class="playButton" type="button" name="play" value="Play" alt="Play button">
				</a>
				<div class="levelButton">
					<input type="radio" id="level1" name="level" value="Level1" checked>
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
			<a class="footerEffect" href="https://www.linkedin.com/in/thomas-s%C3%A9galen" target="_blank" alt="Linkedin button">
				<img src="img/LinkedinLogo.png">
			</a>
			<a class="footerEffect" href="https://github.com/ThomasSEGALEN" target="_blank" alt="Github button">
				<img src="img/GithubLogo.png">
			</a>
			<a class="footerEffect" href="mailto:segalen.thomas.pro@gmail.com" target="_blank" alt="Mail button">
				<img src="img/MailLogo.png">
			</a>
		</div>
		<span class="copyright">Developed & designed by Thomas SÉGALEN | © 2021</span>
	</footer>

	<script type="text/javascript">
		// open the form when clicking on Authentification button
		function openForm() {
			if (document.getElementById('open').value == 0) {
				document.getElementById("form").style.display = "block";
			}
		}

		// get the level selected by the player
		function getLevel() {
			if (document.getElementById('level1').checked) {
				document.getElementById('link').href = "./labyrinth_game.php?init=levels/labyrinth_level1.txt"
			} else if (document.getElementById('level2').checked) {
				document.getElementById('link').href = "./labyrinth_game.php?init=levels/labyrinth_level2.txt"
			} else if (document.getElementById('level3').checked) {
				document.getElementById('link').href = "./labyrinth_game.php?init=levels/labyrinth_level3.txt"
			}
		}

		// close the form when clicking on Close button - not used because not necessary
		// function closeForm() {
		// 	document.getElementById("form").style.display = "none";
		// }
	</script>

</body>

</html>