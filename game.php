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
  $myLabyrinth->getCoordsBonus();

  // si la position du joueur n'est pas mise ou si il décide de reload, on met à la position du start
  if(!isset($_COOKIE["joueur_x"]) && !isset($_COOKIE["joueur_y"]) || isset($_POST["reload"])){
    $myLabyrinth->ResetPlayerData($startEnd);
  }

  if(isset($_GET["movement"])){
    $myLabyrinth->direction($_GET["movement"]);
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

  $myLabyrinth->getZero();
  $myLabyrinth->showBonus();

  // au lieu de mettre url=game.php dans le refresh, mettre l'url courant pour rester dynamique

  // faire une base de donnée pour les coords des bonus et/ou des pièges
  // creer une class bdd, faires des fonctions pour les différentes requêtes
  // TODO:
  // 1 - récupérer un tableau avec les coords des 0 (laby.php)
  // 2 - générer 3 nombre aléatoire, et placer des bonus à la place des 0 (laby.php)
  // 3 - mettre les bonus en base (data.php)
  // 4 - récupérer les coords en base des bonus et les afficher à l'écran (laby.php)
  // 5 - si le joueur prend un bonus, incrémenter score de 1 et remplacer par 0, et supprimer de la base

  // TODO:
  // mettre les données comme pseudo, uid, finish en bdd
  // mettre position du joueur, bonus, piege en cookie

  // réorganiser l'arborescence des fichiers

  // mettre un tableau, (<td> etc etc), et en php mettre des class en fonction du caractère

?>

    <div>
      <?php if($_COOKIE["finish"] == 0): ?>
        <form action="" method="GET">
          <input type="submit" value="Right" name="movement" id="button_right">
          <input type="submit" value="Left" name="movement"  id="button_left">
          <input type="submit" value="Up" name="movement"  id="button_up">
          <input type="submit" value="Down" name="movement"  id="button_down">
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
