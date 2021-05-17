<?php
// start the session to link informations to the game page
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
	<link rel="stylesheet" type="text/css" href="labyrinth_game_menu.css">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
</head>

<body>

	<div class="logo">
		<img src="img/LabyrinthLogo.png" alt="Logo" />
		<a href="./labyrinth_game_menu.php">
			<h1 class="title">PHP Labyrinth Game</h1>
		</a>
	</div>

	<div class="form">
		<!-- popup menu to register the player with post method -->
		<form method="POST">
			<button class="authButton" id="open" onclick="openForm()">Authentification</button>
			<div class="formPopup" id="form">
				<div class="fieldColumn">
					<div class="usernameLabel">
						<label>Username</label>
					</div>
					<div>
						<input class="inputBox" type="text" name="username" required>
					</div>
				</div>
				<div class="fieldColumn">
					<div>
						<input class="loginButton" type="submit" name="login" value="Confirm">
					</div>
				</div>
				<!-- <button class="closeButton" onclick="closeForm()">Close</button> -->
			</div>
		</form>
		<!-- redirect to the game depending of the level you selected -->
		<form>
			<div class="fieldColumn">
				<a id="link" href="<?php if (isset($_SESSION['username'])) {
									} ?>" onclick="getLevel()">
					<input class="playButton" type="button" name="play" value="Play">
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

	<!-- footer used for menu and game page -->
	<footer>
		<div class="link social">
			<a class="footerEffect" href="https://www.linkedin.com/in/thomas-s%C3%A9galen" target="_blank">
				<img src="img/LinkedinLogo.png" alt="Linkedin" />
			</a>
			<a class="footerEffect" href="https://github.com/ThomasSEGALEN" target="_blank">
				<img src="img/GithubLogo.png" alt="Github" />
			</a>
			<a class="footerEffect" href="mailto:segalen.thomas.pro@gmail.com" target="_blank">
				<img src="img/MailLogo.png" alt="Mail" />
			</a>
		</div>
		<span class="copyright">Developed & designed by Thomas SÉGALEN | © 2021</span>
	</footer>

	<script type="text/javascript" src="labyrinth.js"></script>

</body>

</html>