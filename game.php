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
  // ceci permet de creer mon objet et de lancer ma méthode
  require_once('class/Labyrinthe.php');
  $myLabyrinth = new Labyrinthe("labyrinthe.txt");

  if(isset($_COOKIE["joueur_x"]) && isset($_COOKIE["joueur_y"])){
    $myLabyrinth->spawnPlayer($_COOKIE["joueur_x"], $_COOKIE["joueur_y"]);
  }

  $startEnd = $myLabyrinth->foundStartEnd();

  // si la position du joueur n'est pas mise ou si il décide de reload, on met à la position du start
  if(!isset($_COOKIE["joueur_x"]) && !isset($_COOKIE["joueur_y"]) || isset($_POST["reload"])){
    setcookie("joueur_x", $startEnd[0]["x"], time() + 365*24*3600);
    setcookie("joueur_y", $startEnd[0]["y"], time() + 365*24*3600);
    setcookie("finish", 0, time() + 365*24*3600);
    header("Refresh:0; url=game.php");
  }

  if(isset($_GET["goRight"])){
    if($_COOKIE["joueur_x"]+1 <= 14 && !$myLabyrinth->isWall($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], 1, 0)){
      setcookie("joueur_x", $_COOKIE["joueur_x"]+1, time() + 365*24*3600);
      if($myLabyrinth->isEnd($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], 1, 0)){
        setcookie("finish", 1, time() + 365*24*3600);
      }
      header("Refresh:0; url=game.php");
    }else {
      header("Refresh:0; url=game.php");
    }
  }

  if(isset($_GET["goLeft"])){
    if($_COOKIE["joueur_x"]-1 >= 0 && !$myLabyrinth->isWall($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], -1, 0)){
      setcookie("joueur_x", $_COOKIE["joueur_x"]-1, time() + 365*24*3600);
      if($myLabyrinth->isEnd($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], -1, 0)){
        setcookie("finish", 1, time() + 365*24*3600);
      }
      header("Refresh:0; url=game.php");
    }else {
      header("Refresh:0; url=game.php");
    }
  }

  if(isset($_GET["goUp"])){
    if($_COOKIE["joueur_y"]-1 >= 0 && !$myLabyrinth->isWall($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], 0, -1)){
      setcookie("joueur_y", $_COOKIE["joueur_y"]-1, time() + 365*24*3600);
      if($myLabyrinth->isEnd($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], 0, -1)){
        setcookie("finish", 1, time() + 365*24*3600);
      }
      header("Refresh:0; url=game.php");
    }else {
      header("Refresh:0; url=game.php");
    }
  }

  if(isset($_GET["goDown"])){
    if($_COOKIE["joueur_y"]+1 <= 14 && !$myLabyrinth->isWall($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], 0, 1)){
      setcookie("joueur_y", $_COOKIE["joueur_y"]+1, time() + 365*24*3600);
      if($myLabyrinth->isEnd($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], 0, 1)){
        setcookie("finish", 1, time() + 365*24*3600);
      }
      header("Refresh:0; url=game.php");
    }else {
      header("Refresh:0; url=game.php");
    }
  }

  if($_COOKIE["finish"] == 1){
    echo("<h2>Bravo vous avez gagné !</h2>");
  }
?>

    <div>
      <form action="" method="GET">
        <input type="submit" value="Right" name="goRight">
        <input type="submit" value="Left" name="goLeft">
        <input type="submit" value="Up" name="goUp">
        <input type="submit" value="Down" name="goDown">
      </form>

      <form action="" method="POST">
        <input type="submit" value="Reload" name="reload">
      </form>

      <form action="" method="POST">
        <input type="text" placeholder="nickname" name="nickname">
        <input type="submit" value="Rename" name="rename">
      </form>
    </div>
  </body>
</html>

<!-- pour le jeu, caractères peu pratique, peut être générer un tableau en html et remplire les case d'une couleur pour les murs, le start/end et le perso -->
