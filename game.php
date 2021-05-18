<html>
  <head>
    <title>Labyrinthe</title>
    <meta charset="utf-8">
  </head>
  <body>
    <div>
      <h3>A vous de jouer <?php echo($_COOKIE["pseudo"]); ?></h3>
    </div>
    <style type="text/css">
      table, th, td {
        border: 1px solid black;
      }
    </style>

<?php
  // on import et on définit un nouvelle objet pour notre class de base de donnée
  require_once('class/database.php');
  $myDB = new Database();

  // si on définit un niveau dans le GET
  if(isset($_GET["level"])){
    if(!$myDB->verifUid($_COOKIE["PHPSESSID"])){ // si les données du joueur ne sont pas présente en base de donnée
      setcookie("joueur_x", "", time() + 365*24*3600);
      setcookie("joueur_y", "", time() + 365*24*3600);
      $myDB->setData($_GET["level"], $_COOKIE["PHPSESSID"]);
    }
    header("Refresh:0; url=game.php");
  }

  // on import et on définit un nouvelle objet pour notre class de labytinthe
  require_once('class/Labyrinthe.php');
  $myLabyrinth = new Labyrinthe("" . $myDB->getLevel($_COOKIE["PHPSESSID"])["level"] . "");


  if(!isset($_COOKIE["bonus"]) && $myDB->getScore($_COOKIE["PHPSESSID"]) == 0){ // si les cookie n'existe pas et le score = 0
    $myLabyrinth->setCordsBonus(); // on définit de nouveaux bonus sur le jeu
  } elseif($myDB->getScore($_COOKIE["PHPSESSID"]) < 3){ // si le score est inférieur à 3
    $myLabyrinth->spanwBonus($_COOKIE["bonus"]); // on affiche les bonus
  }

  // variable qui contient les valeur du départ et de l'arrivé
  $startEnd = $myLabyrinth->foundStartEnd();

  // si la position du joueur n'est pas mise ou si il décide de reload
  if(!isset($_COOKIE["joueur_x"]) && !isset($_COOKIE["joueur_y"]) || isset($_POST["reload"])){
    $myLabyrinth->ResetPlayerData($startEnd); // on met à la position du start
  }

  // si le boutton quitter est cliqué
  if(isset($_POST["quit"])){
    $myDB->deleteLigne($_COOKIE["PHPSESSID"]); // on supprime la ligne en base
    header("Refresh:0; url=level.php");
  }

  // si un boutton de déplacement est cliqué
  if(isset($_GET["movement"])){
    $myLabyrinth->direction($_GET["movement"]);
  }

  if($myDB->getFinish($_COOKIE["PHPSESSID"]) == 1 && $myDB->getScore($_COOKIE["PHPSESSID"]) == 3){ // gagner si le joueur est à l'arrivé et avec les 3 bonus
    echo("<h2>Bravo vous avez gagné !</h2><h3>Vous pouvez recommencer le niveau ou quitter pour en choisir un autre");
  } elseif ($myDB->getFinish($_COOKIE["PHPSESSID"]) == 1 && $myDB->getScore($_COOKIE["PHPSESSID"]) < 3) { // perdu si le joueur est à l'arrive sans les 3 bonus
    echo("<h2>Vous avez terminez le labyrinthe mais vous n'avez pas récupéré tous les bonus, vous avez perdu !</h2><h3>Vous pouvez recommencer le niveau ou quitter pour en choisir un autre");
  }

  // si on change le pseudo
  if(isset($_POST["nickname"])){
    setcookie("pseudo", $_POST["nickname"], time() + 365*24*3600); // on le met dans les cookies
    header("Refresh:0; url=game.php");
  }

  // si les coordonnées du joueur sont dans les cookie
  if(isset($_COOKIE["joueur_x"]) && isset($_COOKIE["joueur_y"])){
    $myLabyrinth->spawnPlayer($_COOKIE["joueur_x"], $_COOKIE["joueur_y"]); // on fait apparaitre notre joueur
  }

?>

    <div>
      <?php if($myDB->getFinish($_COOKIE["PHPSESSID"]) == 0): ?> <!-- si c'est le jeu n'est pas terminé, on affiche les boutons de directions -->
        <form action="" method="GET">
          <input type="submit" value="Droite" name="movement" id="button_right">
          <input type="submit" value="Gauche" name="movement"  id="button_left">
          <input type="submit" value="Haut" name="movement"  id="button_up">
          <input type="submit" value="Bas" name="movement"  id="button_down">
        </form>
      <?php endif; ?>

      <form action="" method="POST">
        <input type="submit" value="Recommencer" name="reload">
        <?php if($myDB->getFinish($_COOKIE["PHPSESSID"]) == 1): ?> <!-- si je jeu est terminé on affiche ces bouttons -->
          <input type="submit" value="Quit" name="quit">
        <?php endif; ?>
      </form>

      <form action="" method="POST">
        <input type="text" placeholder="pseudo" name="nickname">
        <input type="submit" value="Renommer" name="rename">
      </form>
    </div>
  </body>
</html>
