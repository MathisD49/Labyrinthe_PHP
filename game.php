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
  require_once('class/database.php');
  $myDB = new Database();

  if(isset($_GET["level"])){
    if(!$myDB->verifUid($_COOKIE["PHPSESSID"])){
      setcookie("joueur_x", "", time() + 365*24*3600);
      setcookie("joueur_y", "", time() + 365*24*3600);
      $myDB->setData($_GET["level"], $_COOKIE["PHPSESSID"]);
    }
    header("Refresh:0; url=game.php");
  }

  // ceci permet de creer mon objet et de lancer ma méthode
  require_once('class/Labyrinthe.php');
  $myLabyrinth = new Labyrinthe("" . $myDB->getLevel($_COOKIE["PHPSESSID"])["level"] . "");

  if(!isset($_COOKIE["bonus"]) && $myDB->getScore($_COOKIE["PHPSESSID"]) == 0){
    $myLabyrinth->setCordsBonus();
  } elseif($myDB->getScore($_COOKIE["PHPSESSID"]) < 3){
    $myLabyrinth->spanwBonus($_COOKIE["bonus"]);
  }

  $startEnd = $myLabyrinth->foundStartEnd();

  // si la position du joueur n'est pas mise ou si il décide de reload, on met à la position du start
  if(!isset($_COOKIE["joueur_x"]) && !isset($_COOKIE["joueur_y"]) || isset($_POST["reload"])){
    $myLabyrinth->ResetPlayerData($startEnd);
  }

  if(isset($_POST["quit"])){
    $myDB->deleteLigne($_COOKIE["PHPSESSID"]);
    header("Refresh:0; url=level.php");
  }


  if(isset($_GET["movement"])){
    $myLabyrinth->direction($_GET["movement"]);
  }

  if($myDB->getFinish($_COOKIE["PHPSESSID"]) == 1 && $myDB->getScore($_COOKIE["PHPSESSID"]) >= 3){
    echo("<h2>Bravo vous avez gagné !</h2><h3>Vous pouvez recommencer le niveau ou quitter pour en choisir un autre");
  } elseif ($myDB->getFinish($_COOKIE["PHPSESSID"]) == 1 && $myDB->getScore($_COOKIE["PHPSESSID"]) < 3) {
    echo("<h2>Vous avez terminez le labyrinthe mais vous n'avez pas récupéré tous les bonus, vous avez perdu !</h2><h3>Vous pouvez recommencer le niveau ou quitter pour en choisir un autre");
  }


  if(isset($_POST["nickname"])){
    setcookie("pseudo", $_POST["nickname"], time() + 365*24*3600); // METTRE EN BASE
    header("Refresh:0; url=game.php");
  }

  if(isset($_COOKIE["joueur_x"]) && isset($_COOKIE["joueur_y"])){
    $myLabyrinth->spawnPlayer($_COOKIE["joueur_x"], $_COOKIE["joueur_y"]);
  }

  // au lieu de mettre url=game.php dans le refresh, mettre l'url courant pour rester dynamique

  // réorganiser l'arborescence des fichiers

?>

    <div>
      <?php if($myDB->getFinish($_COOKIE["PHPSESSID"]) == 0): ?>
        <form action="" method="GET">
          <input type="submit" value="Right" name="movement" id="button_right">
          <input type="submit" value="Left" name="movement"  id="button_left">
          <input type="submit" value="Up" name="movement"  id="button_up">
          <input type="submit" value="Down" name="movement"  id="button_down">
        </form>
      <?php endif; ?>

      <form action="" method="POST">
        <?php if($myDB->getFinish($_COOKIE["PHPSESSID"]) == 1): ?>
          <input type="submit" value="Reload" name="reload">
          <input type="submit" value="Quit" name="quit">
        <?php endif; ?>
      </form>

      <form action="" method="POST">
        <input type="text" placeholder="nickname" name="nickname">
        <input type="submit" value="Rename" name="rename">
      </form>
    </div>
  </body>
</html>
