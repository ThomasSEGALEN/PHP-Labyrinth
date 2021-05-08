<?php
   session_start();

   global $gchar_none;
   global $gchar_wall;
   global $gchar_play;
   global $gchar_goal;
   global $fchar_none;
   global $fchar_wall;
   global $fchar_play;
   global $fchar_goal;
   global $rowCount;
   global $colCount;
   global $rowPos;
   global $colPos;
   global $grid;
   global $gameFile;
   $fchar_none = 'n';
   $fchar_wall = 'w';
   $fchar_play = 'p';
   $fchar_goal = 'g';
   $gchar_none = '▢';
   $gchar_wall = '▩';
   $gchar_play = '◎';
   $gchar_goal = '◉';
   $rowCount = 0;
   $colCount = 0;
   $moveCount = 0;
   $ready = FALSE;
   $win = FALSE;
   $err = FALSE;

   if(isset($_SESSION['username'])) {
      $username = $_SESSION['username'];
      $_SESSION['load'] = 0;
      session_save_path();
   }

   if($_SESSION['load'] == 0) {
      if(isset($_SESSION['username'])) {
         // $gameFile = $_GET['file'];
         $gameFile = 'labyrinth_file.txt';
         load($gameFile);
      }
      $_SESSION['load'] = 1;
   }

   if($_SERVER['REQUEST_METHOD'] == "POST" AND $_SESSION['load'] == 1) {
      if(isset($_POST['up'])) {
         echo '<br>PosA:'.$rowPos.'/'.$colPos;
         moveUp();
         echo '<br>PosB:'.$rowPos.'/'.$colPos.'<br>MC:'.$moveCount;
      } elseif(isset($_POST['down'])) {
         echo '<br>PosA:'.$rowPos.'/'.$colPos;
         moveDown();
         echo '<br>PosB:'.$rowPos.'/'.$colPos.'<br>MC:'.$moveCount;
      } elseif(isset($_POST['left'])) {
         echo '<br>PosA:'.$rowPos.'/'.$colPos;
         moveLeft();
         echo '<br>PosB:'.$rowPos.'/'.$colPos.'<br>MC:'.$moveCount;
      } elseif(isset($_POST['right'])) {
         echo '<br>PosA:'.$rowPos.'/'.$colPos;
         moveRight();
         echo '<br>PosB:'.$rowPos.'/'.$colPos.'<br>MC:'.$moveCount;
      }
   }
   var_dump($_POST);
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

      <?php
         global $gameFile;
         $gameFile = 'C:\wamp64\www\PHP-Labyrinth\labyrinth_file.txt';
      ?>
		<div class="dashboard">
            <div class="memberDashboard">
           	   <a class="logoutButton" href="./labyrinth_game_menu.php">Logout</a>
            </div>
            <?php
               if(!isset($username)) {
        	         echo 'Si vous ne vous identifiez pas, votre session ne sera pas sauvegardée';
               } else {
        	         echo 'Username: ' . $username;
               }
               echo '<br>';
               if(isset($_GET['move'])) {
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
				echo "YOU WON !!!";
			} else {
				echo "Find the way out of the maze";
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
            if(isset($_SESSION['username'])) {
               display();
            }
         ?>
		</div>

		<div class="moveButton">
         <form method="POST" action="./labyrinth_game.php?move=up">
               <input type="submit" name="up" value="Up" />
            </form>
            <form method="POST" action="./labyrinth_game.php?move=left">
               <input type="submit" name="left" value="Left" />
            </form>
            <form method="POST" action="./labyrinth_game.php?move=right">
               <input type="submit" name="right" value="Right" />
            </form>
            <form method="POST" action="./labyrinth_game.php?move=down">
               <input type="submit" name="down" value="Down" />
            </form>
		</div>

	</body>
</html>

<script>
<?php
   function restart() {
         $gameFile = fopen('C:\wamp64\www\PHP-Labyrinth\labyrinth_file.txt', 'r+');
         $restartFile = fopen('C:\wamp64\www\PHP-Labyrinth\labyrinth_file_restart.txt', 'r+');
         fwrite($gameFile, fread($restartFile, 4096));
      }

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
	function load($file){
      global $rowCount;
      global $colCount;
      global $rowPos;
      global $colPos;
      global $grid;
      global $moveCount;
      $fchar_none = 'n';
      $fchar_wall = 'w';
      $fchar_play = 'p';
      $fchar_goal = 'g';
      $gchar_none = '▢';
      $gchar_wall = '▩';
      $gchar_play = '◎';
      $gchar_goal = '◉';
      $moveCount = 0;
      $ready = FALSE;
      $win = FALSE;
      $err = FALSE;
      $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $rowCount = count($lines);
      foreach($lines as $lineNum => $line) {
         $chars = str_split($line);
         $colCount = count($chars);
         foreach($chars as $charNum => $char) {
            if($char == $fchar_none){
               $grid[$lineNum][$charNum] = $gchar_none;
            } elseif($char == $fchar_wall) {
               $grid[$lineNum][$charNum] = $gchar_wall;
            } elseif($char == $fchar_play) {
               $grid[$lineNum][$charNum] = $gchar_play;
               $rowPos = $lineNum;
               $colPos = $charNum;
            } elseif($char == $fchar_goal) {
               $grid[$lineNum][$charNum] = $gchar_goal;
            } else {
               $err = TRUE;
            }
         }
      }
      if(!$err) {
         $ready = TRUE;
      }
   }

	function display(){
      global $rowCount;
      global $colCount;
      global $grid;
      	echo '<div>' . PHP_EOL;
      	for($row = 0; $row < $rowCount; $row++) {
         	for($col = 0; $col < $colCount; $col++) {
            	echo $grid[$row][$col] . PHP_EOL;
         	}
         	echo '<br>' . PHP_EOL;
      	}
      	echo '</div>' . PHP_EOL;
   }

	function moveUp() {
      global $rowCount;
      global $colCount;
      global $rowPos;
      global $colPos;
      global $grid;
      global $moveCount;
      $fchar_none = 'n';
      $fchar_wall = 'w';
      $fchar_play = 'p';
      $fchar_goal = 'g';
      $gchar_none = '▢';
      $gchar_wall = '▩';
      $gchar_play = '◎';
      $gchar_goal = '◉';
      if($rowPos > 0) {
         if($grid[$rowPos - 1][$colPos] == $gchar_none) {
            // move
            $grid[$rowPos][$colPos] = $gchar_none;
            $rowPos = $rowPos - 1;
            $grid[$rowPos][$colPos] = $gchar_play;
            $moveCount++;
         } elseif($grid[$rowPos - 1][$colPos] == $gchar_goal) {
            // move
            $grid[$rowPos][$colPos] = $gchar_none;
            $rowPos = $rowPos - 1;
            $grid[$rowPos][$colPos] = $gchar_play;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }

	function moveDown() {
      global $rowCount;
      global $colCount;
      global $rowPos;
      global $colPos;
      global $grid;
      global $moveCount;
      $fchar_none = 'n';
      $fchar_wall = 'w';
      $fchar_play = 'p';
      $fchar_goal = 'g';
      $gchar_none = '▢';
      $gchar_wall = '▩';
      $gchar_play = '◎';
      $gchar_goal = '◉';
      if($rowPos < $rowCount) {
         if($grid[$rowPos + 1][$colPos] == $gchar_none) {
            // move
            $grid[$rowPos][$colPos] = $gchar_none;
            $rowPos = $rowPos + 1;
            $grid[$rowPos][$colPos] = $gchar_play;
            $moveCount++;
         }elseif($grid[$rowPos + 1][$colPos] == $gchar_goal) {
            // move
            $grid[$rowPos][$colPos] = $gchar_none;
            $rowPos = $rowPos + 1;
            $grid[$rowPos][$colPos] = $gchar_play;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }

	function moveLeft() {
      global $rowCount;
      global $colCount;
      global $rowPos;
      global $colPos;
      global $grid;
      global $moveCount;
      $fchar_none = 'n';
      $fchar_wall = 'w';
      $fchar_play = 'p';
      $fchar_goal = 'g';
      $gchar_none = '▢';
      $gchar_wall = '▩';
      $gchar_play = '◎';
      $gchar_goal = '◉';
      if($colPos > 0) {
         if($grid[$rowPos][$colPos - 1] == $gchar_none) {
            // move
            $grid[$rowPos][$colPos] = $gchar_none;
            $colPos = $colPos - 1;
            $grid[$rowPos][$colPos] = $gchar_play;
            $moveCount++;
         } elseif($grid[$rowPos][$colPos - 1] == $gchar_goal) {
            // move
            $grid[$rowPos][$colPos] = $gchar_none;
            $colPos = $colPos - 1;
            $grid[$rowPos][$colPos] = $gchar_play;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }

	function moveRight() {
      global $rowCount;
      global $colCount;
      global $rowPos;
      global $colPos;
      global $grid;
      global $moveCount;
      $fchar_none = 'n';
      $fchar_wall = 'w';
      $fchar_play = 'p';
      $fchar_goal = 'g';
      $gchar_none = '▢';
      $gchar_wall = '▩';
      $gchar_play = '◎';
      $gchar_goal = '◉';
      if($colPos < $colCount){
         if($grid[$rowPos][$colPos + 1] == $gchar_none) {
            // move
            $grid[$rowPos][$colPos] = $gchar_none;
            $colPos = $colPos + 1;
            $grid[$rowPos][$colPos] = $gchar_play;
            $moveCount++;
         } elseif($grid[$rowPos][$colPos + 1] == $gchar_goal) {
            // move
            $grid[$rowPos][$colPos] = $gchar_none;
            $colPos = $colPos + 1;
            $grid[$rowPos][$colPos] = $gchar_play;
            $moveCount++;
            // win
            $win = TRUE;
         }
      }
   }
?>
</script>