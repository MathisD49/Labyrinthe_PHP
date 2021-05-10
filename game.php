<html>
  <head>
    <title>Labyrinthe</title>
    <meta charset="utf-8">
  </head>
  <body>
    <div>
      <h3>A vous de jouer <?php echo($_COOKIE["pseudo"]); ?></h3>
    </div>

<?php
  if(!isset($_COOKIE["level"])){
    setcookie("level", $_GET["level"], time() + 365*24*3600);
    header("Refresh:0; url=game.php");
  }

  // ceci permet de creer mon objet et de lancer ma méthode
  require_once('class/Labyrinthe.php');
  $myLabyrinth = new Labyrinthe($_COOKIE["level"]);

  if(isset($_COOKIE["joueur_x"]) && isset($_COOKIE["joueur_y"])){
    $myLabyrinth->spawnPlayer($_COOKIE["joueur_x"], $_COOKIE["joueur_y"]);
  }

  $startEnd = $myLabyrinth->foundStartEnd();

  // si la position du joueur n'est pas mise ou si il décide de reload, on met à la position du start
  if(!isset($_COOKIE["joueur_x"]) && !isset($_COOKIE["joueur_y"]) || isset($_POST["reload"])){
    $myLabyrinth->ResetPlayerData($startEnd);
  }


  if(isset($_GET["goRight"])){
    $myLabyrinth->MoveHorizontal($_COOKIE["joueur_x"]+1, 1, 0);
  }


  if(isset($_GET["goLeft"])){
    $myLabyrinth->MoveHorizontal($_COOKIE["joueur_x"]-1, -1, 0);
  }


  if(isset($_GET["goUp"])){
    $myLabyrinth->MoveVertical($_COOKIE["joueur_y"]-1, 0, -1);
  }


  if(isset($_GET["goDown"])){
    $myLabyrinth->MoveVertical($_COOKIE["joueur_y"]+1, 0, 1);
  }


  if($_COOKIE["finish"] == 1){
    echo("<h2>Bravo vous avez gagné !</h2><h3>Vous pouvez recommencer le niveau ou quitter pour en choisir un autre");
  }


  if(isset($_POST["quit"])){
    $myLabyrinth->Quit();
  }

  if(isset($_POST["nickname"])){
    setcookie("pseudo", $_POST["nickname"], time() + 365*24*3600);
    header("Refresh:0; url=game.php");
  }
?>

    <div>
      <?php if($_COOKIE["finish"] == 0): ?>
        <form action="" method="GET">
          <input type="submit" value="Right" name="goRight">
          <input type="submit" value="Left" name="goLeft">
          <input type="submit" value="Up" name="goUp">
          <input type="submit" value="Down" name="goDown">
        </form>
      <?php endif; ?>

      <form action="" method="POST">
        <?php if($_COOKIE["finish"] == 1): ?>
          <input type="submit" value="Reload" name="reload">
        <?php endif; ?>
        <input type="submit" value="Quit" name="quit">
      </form>

      <form action="" method="POST">
        <input type="text" placeholder="nickname" name="nickname">
        <input type="submit" value="Rename" name="rename">
      </form>
    </div>
  </body>
</html>

<!-- pour le jeu, caractères peu pratique, peut être générer un tableau en html et remplire les case d'une couleur pour les murs, le start/end et le perso -->
