<?php
   session_start();

   // global $gchar_none;
   // global $gchar_wall;
   // global $gchar_play;
   // global $gchar_goal;
   // global $fchar_none;
   // global $fchar_wall;
   // global $fchar_play;
   // global $fchar_goal;
   // $cfg['rowCount'];
   // $cfg['colCount'];
   // $cfg['rowPos'];
   // $cfg['colPos'];
   // global $cfg['grid'];
   // global $gameFile;
   // $fchar_none = 'n';
   // $fchar_wall = 'w';
   // $fchar_play = 'p';
   // $fchar_goal = 'g';
   // $gchar_none = '▢';
   // $gchar_wall = '▩';
   // $gchar_play = '◎';
   // $gchar_goal = '◉';
   // $cfg['grid'] = array();
   // $cfg['rowCount'] = 0;
   // $cfg['colCount'] = 0;
   // $cfg['moveCount'] = 0;
   // $ready = FALSE;
   // $win = FALSE;
   // $err = FALSE;

   if(isset($_SESSION['username'])) {
      $username = $_SESSION['username'];
      session_save_path();
   }

   if(isset($_GET['init'])) {
      if(isset($_SESSION['username'])) {
         // $gameFile = $_GET['file'];
         // echo 'zzz';
         $cfg['grid'] = array();
         $cfg['rowPos'] = 0;
         $cfg['colPos'] = 0;
         $cfg['rowCount'] = 0;
         $cfg['colCount'] = 0;
         $cfg['moveCount'] = 0;
         $cfg['gameFile'] = $_GET['init'];
         $cfg['ready'] = FALSE;
         $cfg['win'] = FALSE;
         $_SESSION['cfg'] = $cfg;
         load();
         // $cfg['grid'] = array();
         // $cfg['rowPos'];
         // $cfg['colPos'];
         // $cfg['rowCount'] = 0;
         // $cfg['colCount'] = 0;
         // $cfg['moveCount'] = 0;
         // $ready = FALSE;
         // $win = FALSE;
         // $err = FALSE;
      }
   }
   // print_r($_SESSION['cfg']);
   // print_r($GLOBALS);


   if($_SERVER['REQUEST_METHOD'] == "POST") {
      if(isset($_POST['up'])) {
         moveUp();
      } elseif(isset($_POST['down'])) {
         moveDown();
      } elseif(isset($_POST['left'])) {
         moveLeft();
      } elseif(isset($_POST['right'])) {
         moveRight();
      }
   }
   // var_dump($_POST);
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
         <h1 class="title">Sortez du labyrinthe</h1>  
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
            <div class="progressionText">
      			<?php
      			if($_SESSION['cfg']['win'] == TRUE) {
      				echo "YOU WON !!! <br>";
                  echo 'Move Count: ' . $_SESSION['cfg']['moveCount'];
      			} else {
      				echo "Find the way out of the maze";
      			}
      			?>      
            </div>
      </div>


		<div class="restartButton">
			<form method="POST" action="./labyrinth_game.php?init=init">
				<input type=submit name="restart" value="Recommencer" alt="Restart button">
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

      <form action="handler.php" method="get">
         <input type="hidden" name="lost" value="value" />
      </form>

	</body>
</html>

<script>
<?php
   // load a grid from a file
	function load(){
      $cfg = $_SESSION['cfg'];
      $fchar_none = 'n';
      $fchar_wall = 'w';
      $fchar_play = 'p';
      $fchar_goal = 'g';
      $gchar_none = '▢';
      $gchar_wall = '▩';
      $gchar_play = '◎';
      $gchar_goal = '◉';
      $cfg['moveCount'] = 0;
      $err = FALSE;
      $lines = file($cfg['gameFile'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $cfg['rowCount'] = count($lines);
      foreach($lines as $lineNum => $line) {
         $chars = str_split($line);
         $cfg['colCount'] = count($chars);
         foreach($chars as $charNum => $char) {
            if($char == $fchar_none){
               $cfg['grid'][$lineNum][$charNum] = $gchar_none;
            } elseif($char == $fchar_wall) {
               $cfg['grid'][$lineNum][$charNum] = $gchar_wall;
            } elseif($char == $fchar_play) {
               $cfg['grid'][$lineNum][$charNum] = $gchar_play;
               $cfg['rowPos'] = $lineNum;
               $cfg['colPos'] = $charNum;
            } elseif($char == $fchar_goal) {
               $cfg['grid'][$lineNum][$charNum] = $gchar_goal;
            } else {
               $err = TRUE;
            }
         }
      }
      if(!$err) {
         $cfg['ready'] = TRUE;
      }
      $_SESSION['cfg'] = $cfg;
   }

	function display(){
      $cfg = $_SESSION['cfg'];
      if($cfg['ready']) {
      	echo '<div>' . PHP_EOL;
      	for($row = 0; $row < $cfg['rowCount']; $row++) {
         	for($col = 0; $col < $cfg['colCount']; $col++) {
            	echo $cfg['grid'][$row][$col] . PHP_EOL;
         	}
         	echo '<br>' . PHP_EOL;
      	}
      	echo '</div>' . PHP_EOL;
      } else {
         echo '<div>' . 'Labyrinth not loaded !' . '</div>' . PHP_EOL;
      }
   }

	function moveUp() {
      $cfg = $_SESSION['cfg'];
      if($cfg['ready'] AND !$cfg['win']) {
         $fchar_none = 'n';
         $fchar_wall = 'w';
         $fchar_play = 'p';
         $fchar_goal = 'g';
         $gchar_none = '▢';
         $gchar_wall = '▩';
         $gchar_play = '◎';
         $gchar_goal = '◉';
         if($cfg['rowPos'] > 0) {
            if($cfg['grid'][$cfg['rowPos'] - 1][$cfg['colPos']] == $gchar_none) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_none;
               $cfg['rowPos'] = $cfg['rowPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_play;
            } elseif($cfg['grid'][$cfg['rowPos'] - 1][$cfg['colPos']] == $gchar_goal) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_none;
               $cfg['rowPos'] = $cfg['rowPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_play;
               // win
               $cfg['win'] = TRUE;
            }
            $cfg['moveCount'] = $cfg['moveCount'] + 1;
         }
         $_SESSION['cfg'] = $cfg;
      }
   }

	function moveDown() {
      $cfg = $_SESSION['cfg'];
      if($cfg['ready'] AND !$cfg['win']) {
         $fchar_none = 'n';
         $fchar_wall = 'w';
         $fchar_play = 'p';
         $fchar_goal = 'g';
         $gchar_none = '▢';
         $gchar_wall = '▩';
         $gchar_play = '◎';
         $gchar_goal = '◉';
         if($cfg['rowPos'] < $cfg['rowCount'] - 1) {
            if($cfg['grid'][$cfg['rowPos'] + 1][$cfg['colPos']] == $gchar_none) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_none;
               $cfg['rowPos'] = $cfg['rowPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_play;
            } elseif($cfg['grid'][$cfg['rowPos'] + 1][$cfg['colPos']] == $gchar_goal) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_none;
               $cfg['rowPos'] = $cfg['rowPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_play;
               // win
               $cfg['win'] = TRUE;
            }
            $cfg['moveCount'] = $cfg['moveCount'] + 1;
         }
         $_SESSION['cfg'] = $cfg;
      }
   }

	function moveLeft() {
      $cfg = $_SESSION['cfg'];
      if($cfg['ready'] AND !$cfg['win']) {
         $fchar_none = 'n';
         $fchar_wall = 'w';
         $fchar_play = 'p';
         $fchar_goal = 'g';
         $gchar_none = '▢';
         $gchar_wall = '▩';
         $gchar_play = '◎';
         $gchar_goal = '◉';
         if($cfg['colPos'] > 0) {
            if($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] - 1] == $gchar_none) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_none;
               $cfg['colPos'] = $cfg['colPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_play;
            } elseif($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] - 1] == $gchar_goal) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_none;
               $cfg['colPos'] = $cfg['colPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_play;
               // win
               $cfg['win'] = TRUE;
            }
            $cfg['moveCount'] = $cfg['moveCount'] + 1;
         }
         $_SESSION['cfg'] = $cfg;
      }
   }

	function moveRight() {
      $cfg = $_SESSION['cfg'];
      if($cfg['ready'] AND !$cfg['win']) {
         $fchar_none = 'n';
         $fchar_wall = 'w';
         $fchar_play = 'p';
         $fchar_goal = 'g';
         $gchar_none = '▢';
         $gchar_wall = '▩';
         $gchar_play = '◎';
         $gchar_goal = '◉';
         if($cfg['colPos'] < $cfg['colCount'] - 1){
            if($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] + 1] == $gchar_none) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_none;
               $cfg['colPos'] = $cfg['colPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_play;
            } elseif($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] + 1] == $gchar_goal) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_none;
               $cfg['colPos'] = $cfg['colPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = $gchar_play;
               // win
               $cfg['win'] = TRUE;
            }
            $cfg['moveCount'] = $cfg['moveCount'] + 1;
         }
         $_SESSION['cfg'] = $cfg;
      }
   }
?>
</script>