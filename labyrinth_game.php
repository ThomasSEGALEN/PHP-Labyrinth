<?php
session_start();

if(isset($_SESSION['username'])) {
   $username = $_SESSION['username'];
   session_save_path();
}
// session_destroy();

$win = FALSE;

// require('CLabyrinthe.php');

// include 'labyrinth_vars.php';
// load('C:\wamp64\www\PHP-Labyrinth\labyrinth_file.txt');
// moveDown();
// display();

// if(!isset($loaded)) {
// 	$loaded = false;
// 	$array = array();
// 	$startRow = 0;
// 	$startCol = 0;
// 	if($loaded == false) {
// 					echo "loaded";
// 					$file = file('C:\wamp64\www\PHP-Labyrinth\labyrinth_file.txt', FILE_IGNORE_NEW_LINES);
// 				$nbCol = count($file);
// 				foreach ($file as $row => $line) {
// 					$chars = str_split($line);
// 					$nbRow = count($chars);
// 					foreach ($chars as $col => $char) {
// 						if($char == 'o') {
// 							$array[$row][$col] = '▩ ';
// 							// echo $array[$row][$col];
// 						} else if($char == 'c') {
// 							$array[$row][$col] = '▢ ';
// 							// echo $array[$row][$col];
// 						} else if($char == 's') {
// 							$array[$row][$col] = '◎ ';
// 							$startCol = $col;
// 							$startRow = $row;
// 							// echo $array[$row][$col];
// 						} else if($char == 'e'){
// 							$array[$row][$col] = '◉ ';
// 							// echo $array[$row][$col];
// 						}
// 					}
// 					// echo '<br>';
// 				}
// 				$loaded = true;
// 				}
// }



if($_SERVER['REQUEST_METHOD'] == "POST" AND (isset($_POST['down']))) {
	// CLabyrinthe::moveDown();
	// CLabyrinthe::display();
}
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
            </div>
        </div>

        <?php
        if(!isset($username)) {
        	echo 'Si vous ne vous identifiez pas, votre session ne sera pas sauvegardée';
        } else {
        	echo 'Username: ' . $username;
        }
        ?>

		<div class="tipText">
			<?php
			if(isset($_GET['move'])){
				echo 'Move: '. $_GET['move'];
			} else {
				echo "Veuillez effectuer un déplacement";
			}
			?>
		</div>

		<h1 class="title">Sortez du labyrinthe</h1>	
		
		<div class="progressionText">
			<?php
			if($win == TRUE) {
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
				if($_SERVER['REQUEST_METHOD'] == "POST" AND (isset($_POST['restart']))) {
					restart();
				}
				?>
			</form>
		</div>


		<div class="labyrinthGame">
			<?php
				for($r = 0; $r < $nbRow; $r++) {
					for($c = 0; $c < $nbCol; $c++) {
						echo $array[$r][$c];
					}
					echo '<br>';
				}
			?>
		</div>

		<div class="moveButton">
			<div class="upButton">
				<a href="./labyrinth_game.php?move=up" onclick="reloadPage()">
					<button><img src="img/upArrow.png"/></button>
					<!-- <input type="image" src="img/upArrow.png" value="Up" alt="Up move"> -->
				</a>
			</div>
			<div class="leftnrightButton">
				<div class="leftButton">
					<a href="./labyrinth_game.php?move=left" onclick="reloadPage()">
						<button onclick="moveLeft()"><img src="img/leftArrow.png"/></button>
						<!-- <input type="image" src="img/leftArrow.png" value="Left" alt="Left move"> -->
					</a>
				</div>
				<div class="rightButton">
					<a href="./labyrinth_game.php?move=right" onclick="reloadPage()">
						<button onclick="moveRight()"><img src="img/rightArrow.png"/></button>
						<!-- <input type="image" src="img/rightArrow.png" value="Right" alt="Right move"> -->
					</a>
				</div>
			</div>
			<div class="bottomButton">
				<a href="./labyrinth_game.php?move=down">
					<button onclick="moveDown()"><img src="img/downArrow.png"/></button>
					<!-- <input type="image" src="img/downArrow.png" value="Down" alt="Down move"> -->
				</a>
				<form action="" method="post">
    				<input type="submit" name="down" value="Down" />
				</form>
			</div>
		</div>

	</body>
</html>

<script>
<?php
// file codage
const FCHAR_NONE = '.';
const FCHAR_WALL = '*';
const FCHAR_PLAY = 'S';
const FCHAR_GOAL = 'E';

// grid codage
const GCHAR_NONE = '.';
const GCHAR_WALL = '*';
const GCHAR_PLAY = 'S';
const GCHAR_GOAL = 'E';

	function getMoveCount(){
      return $moveCount;
   }

	function getReady(){
      return $ready;
   }

	function getWin(){
      return $win;
   }

// load a grid from a file
	function load($fileName){
      $moveCount = 0;
      $ready = FALSE;
      $win = FALSE;
      $err = FALSE;
      $lines = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $rowCount = count($lines);
      foreach($lines as $lineNum => $line){
         $chars = str_split($line);
         $colCount = count($chars);
         foreach($chars as $charNum => $char){
            if($char == FCHAR_NONE){
               $grid[$lineNum][$charNum] = GCHAR_NONE;
            }elseif($char == FCHAR_WALL){
               $grid[$lineNum][$charNum] = GCHAR_WALL;
            }elseif($char == FCHAR_PLAY){
               $grid[$lineNum][$charNum] = GCHAR_PLAY;
               $rowPos = $lineNum;
               $colPos = $charNum;
            }elseif($char == FCHAR_GOAL){
               $grid[$lineNum][$charNum] = GCHAR_GOAL;
            }else{
               $err = TRUE;
            }
         }
      }
      if(!$err){
         $ready = TRUE;
      }
   }

	function display(){
		global $rowCount;
      	echo '<div>' . PHP_EOL;
      	for($row = 0; $row < $rowCount; $row++){
         	for($col = 0; $col < $colCount; $col++){
            	echo $grid[$row][$col] . PHP_EOL;
         	}
         	echo '<br>' . PHP_EOL;
      	}
      	echo '</div>' . PHP_EOL;
   }

	function moveUp(){
      if($rowPos > 0){
         if($grid[$rowPos - 1][$colPos] == GCHAR_NONE){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $rowPos = $rowPos - 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
         }elseif($grid[$rowPos - 1][$colPos] == GCHAR_GOAL){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $rowPos = $rowPos - 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
      if(isset($_SESSION['username'])) {
         $username = $_SESSION['username'];
      } else {
         echo $username;
      }
   }

	function moveDown(){
	global $rowPos;
	global $rowCount;
      if($rowPos < $rowCount){
         if($grid[$rowPos + 1][$colPos] == GCHAR_NONE){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $rowPos = $rowPos + 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
         }elseif($grid[$rowPos + 1][$colPos] == GCHAR_GOAL){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $rowPos = $rowPos + 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }

	function moveLeft(){
      if($colPos > 0){
         if($grid[$rowPos][$colPos - 1] == GCHAR_NONE){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $colPos = $colPos - 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
         }elseif($grid[$rowPos][$colPos - 1] == GCHAR_GOAL){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $colPos = $colPos - 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }

	function moveRight(){
      if($colPos < $colCount){
         if($grid[$rowPos][$colPos + 1] == GCHAR_NONE){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $colPos = $colPos + 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
         }elseif($grid[$rowPos][$colPos + 1] == GCHAR_GOAL){
            // move
            $grid[$rowPos][$colPos] = GCHAR_NONE;
            $colPos = $colPos + 1;
            $grid[$rowPos][$colPos] = GCHAR_PLAY;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }

	function restart() {
		$gameFile = fopen('C:\wamp64\www\PHP-Labyrinth\labyrinth_file.txt', 'r+');
		$restartFile = fopen('C:\wamp64\www\PHP-Labyrinth\labyrinth_file_restart.txt', 'r+');
		fwrite($gameFile, fread($restartFile, filesize($gameFile)));
	}

	function reloadPage() {
		location.reload();
	}
?>
</script>