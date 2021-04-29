<?php
session_start();
// session_destroy();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Labyrinthe PHP</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="labyrinth_css.css">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
	</head>
	<body>

		<div class="dashboard">
            <div class="memberDashboard">
           		<a class="logoutButton" href="./labyrinth_game_menu.php">Déconnexion</a>
           		<?php
				session_destroy();
				?>
            </div>
        </div>

        <?php
        if(!isset($_SESSION['username'])) {
        	echo 'Si vous ne vous identifiez pas, votre session ne sera pas sauvegardée';
        } else {
        	echo 'Username: ' . $_SESSION['username'];
        }
        ?>

		<div class="tipText">
			<?php
			if(isset($_GET['move'])){
				echo 'Move: '.$_GET['move'];
			} else {
				echo "Veuillez effectuer un déplacement";
			}
			?>
		</div>

		<h1 class="title">Sortez du labyrinthe</h1>	
		
		<div class="progressionText">
			<?php
			if(isset($_GET['win']) && (isset($_GET['move']))) {
				echo "VOUS AVEZ GAGNÉ!";
			} else {
				echo "Vous y êtes presque";
			}
			?>
		</div>


		<div class="restartButton">
			<form method="POST" action="">
				<input type=submit name="restart" value="Recommencer" alt="Restart button">
				<?php
				if($_SERVER['REQUEST_METHOD']=="POST" AND (isset($_POST['restart']))) {
					restart();
				}
				?>
			</form>
		</div>


		<div class="labyrinthGame">
			<?php
			$file = fopen('C:\wamp64\www\labyrinth\labyrinth_file.txt', 'r+');

			for($nb = 1; $nb <= 17; $nb++) {
				$lign = fgets($file).'<br>';
				echo $lign;
			}
			?>
		</div>

		<div class="moveButton">
			<div class="upButton">
				<a href="/labyrinth/labyrinth_game.php?move=up">
					<input type="image" src="img/upArrow.png" value="Up" alt="Up move">
				</a>
			</div>
			<div class="leftnrightButton">
				<div class="leftButton">
					<a href="/labyrinth/labyrinth_game.php?move=left">
						<input type="image" src="img/leftArrow.png" value="Left" alt="Left move">
					</a>
				</div>
				<div class="rightButton">
					<a href="/labyrinth/labyrinth_game.php?move=right">
						<input type="image" src="img/rightArrow.png" value="Right" alt="Right move">
					</a>
				</div>
			</div>
			<div class="bottomButton">
				<a href="/labyrinth/labyrinth_game.php?move=down">
					<input type="image" src="img/downArrow.png" value="Down" alt="Down move">
				</a>
			</div>
		</div>

	</body>
</html>

<?php
function moveTop() {
	echo 'character is moving up';
}
function moveLeft() {
	echo 'character is moving left';
}
function moveRight() {
	echo 'character is moving right';
}
function moveDown() {
	echo 'character is moving down';
}

function restart() {
	$gameFile = fopen('C:\wamp64\www\labyrinth\labyrinth_file.txt', 'c+b');
	$restartFile = fopen('C:\wamp64\www\labyrinth\labyrinth_file_restart.txt', 'c+b');
	fwrite($gameFile, fread($restartFile, 1200));
}

/* Faire une boucle pour lire un tableau et vérifier si la valeur GET y est inscrite
Dans notre cas : up/left/right/down */
	// if(!isset($_GET['move'])) {
	// 	echo 'character is not moving';
	// } else if(isset($_GET['move']) && $_GET['move'] == up) {
	// 	moveTop();
	// } else if(isset($_GET['move']) && $_GET['move'] == left) {
	// 	moveLeft();
	// } else if(isset($_GET['move']) && $_GET['move'] == right) {
	// 	moveRight();
	// } else if(isset($_GET['move']) && $_GET['move'] == down) {
	// 	moveDown();
	// }

	// switch (isset($_GET['move'])) {
	// 	case (!isset($_GET['move'])):
	// 		echo 'character is not moving';
	// 		break;
	// 	case ($_GET['move']==top):
	// 		moveTop();
	// 		break;
	// 	case ($_GET['move']==left):
	// 		moveLeft();
	// 		break;
	// 	case ($_GET['move']==right):
	// 		moveRight();
	// 		break;
	// 	case ($_GET['move']==down):
	// 		moveDown();
	// 		break;
	// }
?>