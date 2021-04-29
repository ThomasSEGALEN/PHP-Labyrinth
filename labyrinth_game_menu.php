<?php
session_start();
// session_destroy();
if (isset($_POST['pseudo']) and (!empty($_POST['pseudo']))) {
	$_SESSION['pseudo'] = $_POST['pseudo'];
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

	<div class="table">
		<form method="POST" action="" onSubmit="return validate();">
			<button class="openButton" onclick="openForm()">Identification</button>
			<div class="formPopup" id="myForm">
				<form class="formContainer">
					<!-- <div class="formHead">Identification</div> -->
					<?php
					if (isset($_SESSION["errorMessage"])) {
					?>
						<div class="errorMessage"><?php echo $_SESSION["errorMessage"]; ?></div>
					<?php
						unset($_SESSION["errorMessage"]);
					}
					?>
					<div class="fieldColumn">
						<div>
							<label for="pseudo">Pseudonyme</label><span class="errorInfo" id="user_info"></span>
						</div>
						<div>
							<input class="inputBox" id="user_name" type="text" name="pseudo">
						</div>
					</div>
					<div class="fieldColumn">
						<div>
							<input class="loginButton" type="submit" name="login" value="Confirmer" alt="Login button">
						</div>
					</div>
					<button class="closeButton" onclick="closeForm()">Fermer</button>
				</form>
			</div>
		</form>
		<form>
			<div class="fieldColumn">
				<a href="/labyrinth/labyrinth_game.php">
					<input class="playButton" type="button" name="play" value="Jouer" alt="Play button" action="/labyrinth/labyrinth_game.php">
				</a>
			</div>
		</form>
	</div>

	<footer>
		<div class="footer">
			FOOTER
		</div>
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
				document.getElementById("user_info").innerHTML = "si vous ne vous identifiez pas, votre session ne sera pas sauvegardée";
				$valid = false;
			}
			return $valid;
		}
	</script>

</body>

</html>