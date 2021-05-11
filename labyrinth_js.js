// menu page functions

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

// game page functions

// page is body's id
var page = document.getElementById('page');

// play with keyboard arrows
page.addEventListener('keydown', function(event) {
   if (event.keyCode == 38) {
      document.getElementById('up').click();
   } else if (event.keyCode == 37) {
      document.getElementById('left').click();
   } else if (event.keyCode == 39) {
      document.getElementById('right').click();
   } else if (event.keyCode == 40) {
      document.getElementById('down').click();
   }
});

// exit the game with escape key & restart the game with space key
page.addEventListener('keyup', function(event) {
   if (event.keyCode == 27) {
      document.getElementById('logout').click();
   } else if (event.keyCode == 32) {
      document.getElementById('restart').click();
   }
});