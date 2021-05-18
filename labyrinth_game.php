<?php
// start the session to link informations from menu page
session_start();

// constant variables
define('FCHAR_NONE', 'n');
define('FCHAR_WALL', 'w');
define('FCHAR_PLAY', 'p');
define('FCHAR_GOAL', 'g');
define('FCHAR_BONUS', 'b');
define('GCHAR_NONE', '🥄');
define('GCHAR_WALL', '🥦');
define('GCHAR_PLAY', '🦊');
define('GCHAR_GOAL', '🐔');
define('GCHAR_BONUS', '🐤');
define('DEV_LEVEL1', '38');
define('DEV_LEVEL2', '30');
define('DEV_LEVEL3', '128');

// save the username into a variable
if (isset($_SESSION['username'])) {
   $username = $_SESSION['username'];
} else {
   header('Location: ./labyrinth_game_menu.php');
}

// initialize the game
if (isset($_GET['init'])) {
   if (isset($_SESSION['username'])) {
      $cfg['grid'] = array();
      $cfg['rowPos'] = 0;
      $cfg['colPos'] = 0;
      $cfg['rowCount'] = 0;
      $cfg['colCount'] = 0;
      $cfg['moveCount'] = 0;
      $cfg['bonusCount'] = 0;
      $cfg['bonusTotal'] = 0;
      $cfg['gameFile'] = $_GET['init'];
      $cfg['ready'] = FALSE;
      $cfg['win'] = FALSE;
      $cfg['play'] = 0;
      $cfg['goal'] = 0;
      $cfg['bonus'] = 0;
      $cfg['bonusError'] = FALSE;
      $_SESSION['cfg'] = $cfg;
      load();
   }
}
// print_r($_SESSION['cfg']);
// print_r($GLOBALS);

// move the character
if ($_SERVER['REQUEST_METHOD'] == "POST") {
   if (isset($_POST['up'])) {
      moveUp();
   } elseif (isset($_POST['left'])) {
      moveLeft();
   } elseif (isset($_POST['right'])) {
      moveRight();
   } elseif (isset($_POST['down'])) {
      moveDown();
   }
}
// var_dump($_POST);

// load a grid from a file
function load()
{
   $cfg = $_SESSION['cfg'];
   $cfg['moveCount'] = 0;
   $err = FALSE;
   $lines = file($cfg['gameFile'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   $cfg['rowCount'] = count($lines);
   foreach ($lines as $lineNum => $line) {
      $chars = str_split($line);
      $cfg['colCount'] = count($chars);
      foreach ($chars as $charNum => $char) {
         if ($char == constant('FCHAR_NONE')) {
            $cfg['grid'][$lineNum][$charNum] = constant('GCHAR_NONE');
         } elseif ($char == constant('FCHAR_WALL')) {
            $cfg['grid'][$lineNum][$charNum] = constant('GCHAR_WALL');
         } elseif ($char == constant('FCHAR_PLAY')) {
            $cfg['grid'][$lineNum][$charNum] = constant('GCHAR_PLAY');
            $cfg['rowPos'] = $lineNum;
            $cfg['colPos'] = $charNum;
         } elseif ($char == constant('FCHAR_GOAL')) {
            $cfg['grid'][$lineNum][$charNum] = constant('GCHAR_GOAL');
         } elseif ($char == constant('FCHAR_BONUS')) {
            $cfg['grid'][$lineNum][$charNum] = constant('GCHAR_BONUS');
            $cfg['bonusTotal'] = $cfg['bonusTotal'] + 1;
         } else {
            $err = TRUE;
         }
      }
   }
   if (!$err) {
      $cfg['ready'] = TRUE;
   }
   $_SESSION['cfg'] = $cfg;
}

// display the maze
function display()
{
   $cfg = $_SESSION['cfg'];
   if ($cfg['ready']) {
      echo '<div>' . PHP_EOL;
      for ($row = 0; $row < $cfg['rowCount']; $row++) {
         for ($col = 0; $col < $cfg['colCount']; $col++) {
            echo '<span class="emoji">' . $cfg['grid'][$row][$col] . '</span>' . PHP_EOL;
         }
         echo '<br>' . PHP_EOL;
      }
      echo '</div>' . PHP_EOL;
   } else {
      echo '<div>' . 'Labyrinth is not loaded !' . '</div>' . PHP_EOL;
   }
}

// function to move up
function moveUp()
{
   $cfg = $_SESSION['cfg'];
   if ($cfg['ready'] and !$cfg['win']) {
      if ($cfg['rowPos'] > 0) {
         if ($cfg['grid'][$cfg['rowPos'] - 1][$cfg['colPos']] == constant('GCHAR_NONE')) {
            // move
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
            $cfg['rowPos'] = $cfg['rowPos'] - 1;
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
         } elseif ($cfg['grid'][$cfg['rowPos'] - 1][$cfg['colPos']] == constant('GCHAR_BONUS')) {
            // move
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
            $cfg['rowPos'] = $cfg['rowPos'] - 1;
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
            // collect
            $cfg['bonusCount'] = $cfg['bonusCount'] + 1;
         } elseif ($cfg['grid'][$cfg['rowPos'] - 1][$cfg['colPos']] == constant('GCHAR_GOAL')) {
            if (isset($cfg['bonusTotal'])) {
               if ($cfg['bonusTotal'] == $cfg['bonusCount']) {
                  // move
                  $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
                  $cfg['rowPos'] = $cfg['rowPos'] + 1;
                  $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
                  // win
                  $cfg['win'] = TRUE;
               } else {
                  $cfg['bonusError'] = TRUE;
               }
            } else {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['rowPos'] = $cfg['rowPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
               // win
               $cfg['win'] = TRUE;
            }
         }
         $cfg['moveCount'] = $cfg['moveCount'] + 1;
      }
      $_SESSION['cfg'] = $cfg;
   }
}

// function to move left
function moveLeft()
{
   $cfg = $_SESSION['cfg'];
   if ($cfg['ready'] and !$cfg['win']) {
      if ($cfg['colPos'] > 0) {
         if ($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] - 1] == constant('GCHAR_NONE')) {
            // move
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
            $cfg['colPos'] = $cfg['colPos'] - 1;
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
         } elseif ($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] - 1] == constant('GCHAR_BONUS')) {
            // move
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
            $cfg['colPos'] = $cfg['colPos'] - 1;
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
            // collect
            $cfg['bonusCount'] = $cfg['bonusCount'] + 1;
         } elseif ($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] - 1] == constant('GCHAR_GOAL')) {
            if (isset($cfg['bonusTotal'])) {
               if ($cfg['bonusTotal'] == $cfg['bonusCount']) {
                  // move
                  $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
                  $cfg['rowPos'] = $cfg['rowPos'] + 1;
                  $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
                  // win
                  $cfg['win'] = TRUE;
               } else {
                  $cfg['bonusError'] = TRUE;
               }
            } else {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['rowPos'] = $cfg['rowPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
               // win
               $cfg['win'] = TRUE;
            }
         }
         $cfg['moveCount'] = $cfg['moveCount'] + 1;
      }
      $_SESSION['cfg'] = $cfg;
   }
}

// function to move right
function moveRight()
{
   $cfg = $_SESSION['cfg'];
   if ($cfg['ready'] and !$cfg['win']) {
      if ($cfg['colPos'] < $cfg['colCount'] - 1) {
         if ($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] + 1] == constant('GCHAR_NONE')) {
            // move
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
            $cfg['colPos'] = $cfg['colPos'] + 1;
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
         } elseif ($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] + 1] == constant('GCHAR_BONUS')) {
            // move
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
            $cfg['colPos'] = $cfg['colPos'] + 1;
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
            // collect
            $cfg['bonusCount'] = $cfg['bonusCount'] + 1;
         } elseif ($cfg['grid'][$cfg['rowPos']][$cfg['colPos'] + 1] == constant('GCHAR_GOAL')) {
            if (isset($cfg['bonusTotal'])) {
               if ($cfg['bonusTotal'] == $cfg['bonusCount']) {
                  // move
                  $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
                  $cfg['rowPos'] = $cfg['rowPos'] + 1;
                  $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
                  // win
                  $cfg['win'] = TRUE;
               } else {
                  $cfg['bonusError'] = TRUE;
               }
            } else {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['rowPos'] = $cfg['rowPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
               // win
               $cfg['win'] = TRUE;
            }
         }
         $cfg['moveCount'] = $cfg['moveCount'] + 1;
      }
      $_SESSION['cfg'] = $cfg;
   }
}

// function to move down
function moveDown()
{
   $cfg = $_SESSION['cfg'];
   if ($cfg['ready'] and !$cfg['win']) {
      if ($cfg['rowPos'] < $cfg['rowCount'] - 1) {
         if ($cfg['grid'][$cfg['rowPos'] + 1][$cfg['colPos']] == constant('GCHAR_NONE')) {
            // move
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
            $cfg['rowPos'] = $cfg['rowPos'] + 1;
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
         } elseif ($cfg['grid'][$cfg['rowPos'] + 1][$cfg['colPos']] == constant('GCHAR_BONUS')) {
            // move
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
            $cfg['rowPos'] = $cfg['rowPos'] + 1;
            $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
            // collect
            $cfg['bonusCount'] = $cfg['bonusCount'] + 1;
         } elseif ($cfg['grid'][$cfg['rowPos'] + 1][$cfg['colPos']] == constant('GCHAR_GOAL')) {
            if (isset($cfg['bonusTotal'])) {
               if ($cfg['bonusTotal'] == $cfg['bonusCount']) {
                  // move
                  $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
                  $cfg['rowPos'] = $cfg['rowPos'] + 1;
                  $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
                  // win
                  $cfg['win'] = TRUE;
               } else {
                  $cfg['bonusError'] = TRUE;
               }
            } else {
               // move
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_NONE');
               $cfg['rowPos'] = $cfg['rowPos'] + 1;
               $cfg['grid'][$cfg['rowPos']][$cfg['colPos']] = constant('GCHAR_PLAY');
               // win
               $cfg['win'] = TRUE;
            }
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
   <link rel="stylesheet" type="text/css" href="labyrinth_game.css">
   <meta content="width=device-width, initial-scale=1" name="viewport" />
</head>

<body id="page">

   <div class="header">
      <!-- display logout/restart button and the username registered before -->
      <div class="dashboard">
         <div class="dashButton">
            <a class="logoutButton" id="logout" href="./labyrinth_game_menu.php">Logout</a>
            <!-- restart the file loaded during init process -->
            <a class="restartButton" id="restart" href="<?php if (isset($_SESSION['username'])) {
                                                            echo './labyrinth_game.php?init=' . $_SESSION['cfg']['gameFile'];
                                                         } else {
                                                            echo 'username_not_set';
                                                         } ?>">Restart</a>
         </div>
         <div class="usernameText">
            <?php
            if (!isset($username)) {
               echo 'Username is not set!';
            } else {
               echo 'Username: ' . $username;
            }
            ?>
         </div>
      </div>
      <div class="logo">
         <img src="img/LabyrinthLogo.png" alt="Logo" />
         <a href="./labyrinth_game_menu.php">
            <h1 class="title">PHP Labyrinth Game</h1>
         </a>
      </div>
   </div>

   <div class="labyrinthGame">
      <div class="progressionText">
         <?php
         // display player moves with $_GET
         if (isset($_SESSION['username'])) {
            // start
            if (!isset($_GET['move']) and $_SESSION['cfg']['win'] == FALSE) {
               echo 'Find a way out of the maze';
               // move display (no bonus)
            } elseif (isset($_GET['move']) and $_SESSION['cfg']['win'] == FALSE and empty($_SESSION['cfg']['bonusTotal'])) {
               if ($_GET['move'] == 'up') {
                  $_GET['move'] = '⮝';
               } elseif ($_GET['move'] == 'left') {
                  $_GET['move'] = '⮜';
               } elseif ($_GET['move'] == 'right') {
                  $_GET['move'] = '⮞';
               } elseif ($_GET['move'] == 'down') {
                  $_GET['move'] = '⮟';
               }
               echo 'Move: ' . $_GET['move'];
               // move display with bonus
            } else if (isset($_GET['move']) and $_SESSION['cfg']['win'] == FALSE and isset($_SESSION['cfg']['bonusTotal'])) {
               if ($_GET['move'] == 'up') {
                  $_GET['move'] = '⮝';
               } elseif ($_GET['move'] == 'left') {
                  $_GET['move'] = '⮜';
               } elseif ($_GET['move'] == 'right') {
                  $_GET['move'] = '⮞';
               } elseif ($_GET['move'] == 'down') {
                  $_GET['move'] = '⮟';
               }
               echo 'Move: ' . $_GET['move'] . ' - Bonus collected: ' . $_SESSION['cfg']['bonusCount'] . '/' . $_SESSION['cfg']['bonusTotal'];
               if ($_SESSION['cfg']['bonusError'] == TRUE) {
                  echo '<br>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbspBonus missed!';
                  $_SESSION['cfg']['bonusError'] = FALSE;
               }
            }
            // standard win display (no bonus)
            if ($_SESSION['cfg']['win'] == TRUE and !empty($_SESSION['cfg']['bonusTotal'])) {
               if ($_SESSION['cfg']['gameFile'] == 'levels/labyrinth_level1.txt') {
                  echo '&nbsp &nbsp &nbsp &nbsp &nbsp Dev: ' . constant('DEV_LEVEL1') . ' - ' . $username . ': ' . $_SESSION['cfg']['moveCount'];
                  if (constant('DEV_LEVEL1') > $_SESSION['cfg']['moveCount']) {
                     echo '<br>You finally managed to get out <br>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp...and you beat me...';
                  } else {
                     echo '<br>You finally managed to get out';
                  }
               } elseif ($_SESSION['cfg']['gameFile'] == 'levels/labyrinth_level2.txt') {
                  echo '&nbsp &nbsp &nbsp &nbsp &nbsp Dev: ' . constant('DEV_LEVEL2') . ' - ' . $username . ': ' . $_SESSION['cfg']['moveCount'];
                  if (constant('DEV_LEVEL2') > $_SESSION['cfg']['moveCount']) {
                     echo '<br>You finally managed to get out <br>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp...and you beat me...';
                  } else {
                     echo '<br>You finally managed to get out';
                  }
               } elseif ($_SESSION['cfg']['gameFile'] == 'levels/labyrinth_level3.txt') {
                  echo '&nbsp &nbsp &nbsp &nbsp &nbsp Dev: ' . constant('DEV_LEVEL3') . ' - ' . $username . ': ' . $_SESSION['cfg']['moveCount'];
                  if (constant('DEV_LEVEL3') > $_SESSION['cfg']['moveCount']) {
                     echo '<br>You finally managed to get out <br>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp...and you beat me...';
                  } else {
                     echo '<br>You finally managed to get out';
                  }
               }
               // win display with bonus
            } elseif ($_SESSION['cfg']['win'] == TRUE and $_SESSION['cfg']['bonusCount'] == $_SESSION['cfg']['bonusTotal']) {
               if ($_SESSION['cfg']['gameFile'] == 'levels/labyrinth_level1.txt') {
                  echo '&nbsp &nbsp &nbsp &nbsp &nbsp Dev: ' . constant('DEV_LEVEL1') . ' - ' . $username . ': ' . $_SESSION['cfg']['moveCount'];
                  if (constant('DEV_LEVEL1') > $_SESSION['cfg']['moveCount']) {
                     echo '<br>You finally managed to get out <br>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp...and you beat me...';
                  } else {
                     echo '<br>You finally managed to get out';
                  }
               } elseif ($_SESSION['cfg']['gameFile'] == 'levels/labyrinth_level2.txt') {
                  echo '&nbsp &nbsp &nbsp &nbsp &nbsp Dev: ' . constant('DEV_LEVEL2') . ' - ' . $username . ': ' . $_SESSION['cfg']['moveCount'];
                  if (constant('DEV_LEVEL2') > $_SESSION['cfg']['moveCount']) {
                     echo '<br>You finally managed to get out <br>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp...and you beat me...';
                  } else {
                     echo '<br>You finally managed to get out';
                  }
               } elseif ($_SESSION['cfg']['gameFile'] == 'levels/labyrinth_level3.txt') {
                  echo '&nbsp &nbsp &nbsp &nbsp &nbsp Dev: ' . constant('DEV_LEVEL3') . ' - ' . $username . ': ' . $_SESSION['cfg']['moveCount'];
                  if (constant('DEV_LEVEL3') > $_SESSION['cfg']['moveCount']) {
                     echo '<br>You finally managed to get out <br>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp...and you beat me...';
                  } else {
                     echo '<br>You finally managed to get out';
                  }
               }
            }
         }
         ?>
      </div>
      <?php
      // display the maze
      if (isset($_SESSION['username'])) {
         display();
      }
      ?>
   </div>

   <!-- move buttons with post method -->
   <div class="moveButton">
      <form class="upButton" method="POST" action="./labyrinth_game.php?move=up">
         <input id="up" type="submit" name="up" value="" />
      </form>
      <div class="lrButton">
         <form class="leftButton" method="POST" action="./labyrinth_game.php?move=left">
            <input id="left" type="submit" name="left" value="" />
         </form>
         <form class="rightButton" method="POST" action="./labyrinth_game.php?move=right">
            <input id="right" type="submit" name="right" value="" />
         </form>
      </div>
      <form class="downButton" method="POST" action="./labyrinth_game.php?move=down">
         <input id="down" type="submit" name="down" value="" />
      </form>
   </div>

   <!-- footer used for menu and game page -->
   <footer class="footer">
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