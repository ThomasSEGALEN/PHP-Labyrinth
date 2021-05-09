<?php
   session_start();

   define("FCHAR_NONE", "n");
   define("FCHAR_WALL", "w");
   define("FCHAR_PLAY", "p");
   define("FCHAR_GOAL", "g");
   define("GCHAR_NONE", "▢");
   define("GCHAR_WALL", "▩");
   define("GCHAR_PLAY", "◎");
   define("GCHAR_GOAL", "◉");

   if(isset($_SESSION['username'])) {
      $username = $_SESSION['username'];
      session_save_path();
   }


   if(isset($_GET['init'])) {
      if(isset($_SESSION['username'])) {
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
         session_save_path();
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


   // load a grid from a file
   function load(){
      $cfg = $_SESSION['cfg'];
      $cfg['moveCount'] = 0;
      $err = FALSE;
      $lines = file($cfg['gameFile'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $cfg['rowCount'] = count($lines);
      foreach($lines as $lineNum => $line) {
         $chars = str_split($line);
         $cfg['colCount'] = count($chars);
         foreach($chars as $charNum => $char) {
            if($char == constant("FCHAR_NONE")) {
               $cfg['grid'][$lineNum][$charNum] = constant("GCHAR_NONE");
            } elseif($char == constant("FCHAR_WALL")) {
               $cfg['grid'][$lineNum][$charNum] = constant("GCHAR_WALL");
            } elseif($char == constant("FCHAR_PLAY")) {
               $cfg['grid'][$lineNum][$charNum] = constant("GCHAR_PLAY");
               $cfg['rowPos'] = $lineNum;
               $cfg['colPos'] = $charNum;
            } elseif($char == constant("FCHAR_GOAL")) {
               $cfg['grid'][$lineNum][$charNum] = constant("GCHAR_GOAL");
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
         echo '<div>' . 'Labyrinth is not loaded !' . '</div>' . PHP_EOL;
      }
   }


   function moveUp() {
      $cfg = $_SESSION['cfg'];
      if($cfg['ready'] AND !$cfg['win']) {
         if($cfg['rowPos'] > 0) {
            if($cfg['grid'][$cfg['rowPos'] - 1][$cfg['colPos']] == constant("GCHAR_NONE")) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_NONE");
               $cfg['rowPos'] = $cfg['rowPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_PLAY");
            } elseif($cfg['grid'][$cfg['rowPos'] - 1][$cfg['colPos']] == constant("GCHAR_GOAL")) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_NONE");
               $cfg['rowPos'] = $cfg['rowPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_PLAY");
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
         if($cfg['colPos'] > 0) {
            if($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] - 1] == constant("GCHAR_NONE")) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_NONE");
               $cfg['colPos'] = $cfg['colPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_PLAY");
            } elseif($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] - 1] == constant("GCHAR_GOAL")) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_NONE");
               $cfg['colPos'] = $cfg['colPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_PLAY");
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
         if($cfg['colPos'] < $cfg['colCount'] - 1){
            if($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] + 1] == constant("GCHAR_NONE")) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_NONE");
               $cfg['colPos'] = $cfg['colPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_PLAY");
            } elseif($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] + 1] == constant("GCHAR_GOAL")) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_NONE");
               $cfg['colPos'] = $cfg['colPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_PLAY");
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
         if($cfg['rowPos'] < $cfg['rowCount'] - 1) {
            if($cfg['grid'][$cfg['rowPos'] + 1][$cfg['colPos']] == constant("GCHAR_NONE")) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_NONE");
               $cfg['rowPos'] = $cfg['rowPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_PLAY");
            } elseif($cfg['grid'][$cfg['rowPos'] + 1][$cfg['colPos']] == constant("GCHAR_GOAL")) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_NONE");
               $cfg['rowPos'] = $cfg['rowPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant("GCHAR_PLAY");
               // win
               $cfg['win'] = TRUE;
            }
            $cfg['moveCount'] = $cfg['moveCount'] + 1;
         }
         $_SESSION['cfg'] = $cfg;
      }
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


		<!-- <div class="restartButton">
			<form method="POST" action="./labyrinth_game.php?init="<?php echo $_GET['init']?>>
				<input type=submit name="restart" value="Recommencer" alt="Restart button">
			</form>
		</div> -->


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