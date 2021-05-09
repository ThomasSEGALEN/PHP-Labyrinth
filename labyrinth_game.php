<?php
   session_start();

   // constant variables
   define('FCHAR_NONE', 'n');
   define('FCHAR_WALL', 'w');
   define('FCHAR_PLAY', 'p');
   define('FCHAR_GOAL', 'g');
   define('GCHAR_NONE', '▢');
   define('GCHAR_WALL', '▩');
   define('GCHAR_PLAY', '◎');
   define('GCHAR_GOAL', '◉');
   define('DEV_LEVEL1', '36');
   define('DEV_LEVEL2', '?');
   define('DEV_LEVEL3', '?');

   // save the username into a variable
   if(isset($_SESSION['username'])) {
      $username = $_SESSION['username'];
   }

   // initialize the game
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
      }
   }
   // print_r($_SESSION['cfg']);
   // print_r($GLOBALS);

   // move the character
   if($_SERVER['REQUEST_METHOD'] == "POST") {
      if(isset($_POST['up'])) {
         moveUp();
      } elseif(isset($_POST['left'])) {
         moveLeft();
      } elseif(isset($_POST['right'])) {
         moveRight();
      } elseif(isset($_POST['down'])) {
         moveDown();
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
            if($char == constant('FCHAR_NONE')) {
               $cfg['grid'][$lineNum][$charNum] = constant('GCHAR_NONE');
            } elseif($char == constant('FCHAR_WALL')) {
               $cfg['grid'][$lineNum][$charNum] = constant('GCHAR_WALL');
            } elseif($char == constant('FCHAR_PLAY')) {
               $cfg['grid'][$lineNum][$charNum] = constant('GCHAR_PLAY');
               $cfg['rowPos'] = $lineNum;
               $cfg['colPos'] = $charNum;
            } elseif($char == constant('FCHAR_GOAL')) {
               $cfg['grid'][$lineNum][$charNum] = constant('GCHAR_GOAL');
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

   // display the maze
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

   // function to move up
   function moveUp() {
      $cfg = $_SESSION['cfg'];
      if($cfg['ready'] AND !$cfg['win']) {
         if($cfg['rowPos'] > 0) {
            if($cfg['grid'][$cfg['rowPos'] - 1][$cfg['colPos']] == constant('GCHAR_NONE')) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['rowPos'] = $cfg['rowPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
            } elseif($cfg['grid'][$cfg['rowPos'] - 1][$cfg['colPos']] == constant('GCHAR_GOAL')) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['rowPos'] = $cfg['rowPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
               // win
               $cfg['win'] = TRUE;
            }
            $cfg['moveCount'] = $cfg['moveCount'] + 1;
         }
         $_SESSION['cfg'] = $cfg;
      }
   }

   // function to move left
   function moveLeft() {
      $cfg = $_SESSION['cfg'];
      if($cfg['ready'] AND !$cfg['win']) {
         if($cfg['colPos'] > 0) {
            if($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] - 1] == constant('GCHAR_NONE')) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['colPos'] = $cfg['colPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
            } elseif($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] - 1] == constant('GCHAR_GOAL')) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['colPos'] = $cfg['colPos'] - 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
               // win
               $cfg['win'] = TRUE;
            }
            $cfg['moveCount'] = $cfg['moveCount'] + 1;
         }
         $_SESSION['cfg'] = $cfg;
      }
   }

   // function to move right
   function moveRight() {
      $cfg = $_SESSION['cfg'];
      if($cfg['ready'] AND !$cfg['win']) {
         if($cfg['colPos'] < $cfg['colCount'] - 1){
            if($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] + 1] == constant('GCHAR_NONE')) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['colPos'] = $cfg['colPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
            } elseif($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] + 1] == constant('GCHAR_GOAL')) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['colPos'] = $cfg['colPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
               // win
               $cfg['win'] = TRUE;
            }
            $cfg['moveCount'] = $cfg['moveCount'] + 1;
         }
         $_SESSION['cfg'] = $cfg;
      }
   }

   // function to move down
   function moveDown() {
      $cfg = $_SESSION['cfg'];
      if($cfg['ready'] AND !$cfg['win']) {
         if($cfg['rowPos'] < $cfg['rowCount'] - 1) {
            if($cfg['grid'][$cfg['rowPos'] + 1][$cfg['colPos']] == constant('GCHAR_NONE')) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['rowPos'] = $cfg['rowPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
            } elseif($cfg['grid'][$cfg['rowPos'] + 1][$cfg['colPos']] == constant('GCHAR_GOAL')) {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['rowPos'] = $cfg['rowPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
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

      <div class="header">
         <div class="dashboard">
               <div class="dashButton">
                  <a class="logoutButton" href="./labyrinth_game_menu.php">Logout</a>
                  <a class="logoutButton" href="<?php echo './labyrinth_game.php?init=' . $_SESSION['cfg']['gameFile'] ?>">Restart</a>
               </div>
               <?php
                  if(!isset($username)) {
                     echo 'Username is not set!';
                  } else {
                     echo 'Username: ' . $username;
                  }
               ?>
         </div>
         <div class="logo">
            <img src="img/LabyrinthLogo.png">
            <a href="./labyrinth_game_menu.php">
               <h1 class="title">PHP Labyrinth Game</h1>
            </a>
         </div>
      </div>

		<div class="labyrinthGame">
         <div class="progressionText">
            <?php
               if($_SESSION['cfg']['win'] == TRUE) {
                  if($_SESSION['cfg']['gameFile'] == 'levels/labyrinth_level1.txt') {
                     echo '&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Dev: ' . constant('DEV_LEVEL1') . ' - ' . $username . ': ' . $_SESSION['cfg']['moveCount'];
                     if(constant('DEV_LEVEL1') > $_SESSION['cfg']['moveCount']) {
                        echo '<br>You finally managed to get out <br>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp...and you beat me...';
                     } else {
                        echo '<br>You finally managed to get out';
                     }
                  } elseif($_SESSION['cfg']['gameFile'] == 'levels/labyrinth_level2.txt') {
                     echo '&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Dev: ' . constant('DEV_LEVEL2') . ' - ' . $username . ': ' . $_SESSION['cfg']['moveCount'];
                     if(constant('DEV_LEVEL1') > $_SESSION['cfg']['moveCount']) {
                        echo '<br>You finally managed to get out <br>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp...and you beat me...';
                     } else {
                        echo '<br>You finally managed to get out';
                     }
                  } elseif($_SESSION['cfg']['gameFile'] == 'levels/labyrinth_level3.txt') {
                     echo '&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Dev: ' . constant('DEV_LEVEL3') . ' - ' . $username . ': ' . $_SESSION['cfg']['moveCount'];
                     if(constant('DEV_LEVEL1') > $_SESSION['cfg']['moveCount']) {
                        echo '<br>You finally managed to get out <br>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp...and you beat me...';
                     } else {
                        echo '<br>You finally managed to get out';
                     }
                  }
               }
               if(!isset($_GET['move']) AND $_SESSION['cfg']['win'] == FALSE) {
                  echo 'Find a way out of the maze';
               } elseif(isset($_GET['move']) AND $_SESSION['cfg']['win'] == FALSE) {
                  if($_GET['move'] == 'up') {
                     $_GET['move'] = '⮝';
                  } elseif($_GET['move'] == 'left') {
                     $_GET['move'] = '⮜';
                  } elseif($_GET['move'] == 'right') {
                     $_GET['move'] = '⮞';
                  } elseif($_GET['move'] == 'down') {
                     $_GET['move'] = '⮟';
                  }
                  echo 'Move: ' . $_GET['move'];
               }
            ?>
         </div>
            <?php
               if(isset($_SESSION['username'])) {
                  display();
               }
            ?>
		</div>

		<div class="moveButton">
         <form class="upButton" method="POST" action="./labyrinth_game.php?move=up">
            <input type="submit" name="up" value=""/>
         </form>
         <div class="lrButton">
            <form class="leftButton" method="POST" action="./labyrinth_game.php?move=left">
               <input type="submit" name="left" value=""/>
            </form>
            <form class="rightButton" method="POST" action="./labyrinth_game.php?move=right">
               <input type="submit" name="right" value=""/>
            </form>
         </div>
         <form class="downButton" method="POST" action="./labyrinth_game.php?move=down">
            <input type="submit" name="down" value=""/>
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

      <script type="text/javascript">
         // create a function which moves your character when using keyboard arrows

         // document.addEventListener('keydown', function(event) {
         //    if(event.keyCode == 38) {
         //       alert('Up was pressed');
         //    } else if(event.keyCode == 37) {
         //       alert('Left was pressed');
         //    } else if(event.keyCode == 39) {
         //       alert('Right was pressed');
         //    } else if(event.keyCode == 40) {
         //       alert('Down was pressed');
         //    }
         // }, true);
      </script>

	</body>
</html>