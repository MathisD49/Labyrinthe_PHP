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
  require_once('class/database.php');
  $myDB = new Database();

  if(isset($_GET["level"])){
    // insérer ici bdd
    // setcookie("level", $_GET["level"], time() + 365*24*3600); // METTRE EN BASE
    // setcookie("score", 0, time() + 365*24*3600); // mettre en base
    setcookie("joueur_x", "", time() + 365*24*3600);
    setcookie("joueur_y", "", time() + 365*24*3600);
    $myDB->setData($_GET["level"], $_COOKIE["PHPSESSID"]);
    // setcookie("finish", 0, time() + 365*24*3600); // mettre en base
    header("Refresh:0; url=game.php");
  }

  // ceci permet de creer mon objet et de lancer ma méthode
  require_once('class/Labyrinthe.php');
  // METTRE FONCTION GET
  $myLabyrinth = new Labyrinthe("" . $myDB->getLevel($_COOKIE["PHPSESSID"])["level"] . "");

  // METTRE FONCTION GET
  if(!isset($_COOKIE["bonus"]) && $myDB->getScore($_COOKIE["PHPSESSID"]) == 0){
    $myLabyrinth->setCordsBonus();
  } elseif($myDB->getScore($_COOKIE["PHPSESSID"]) < 3){
    $myLabyrinth->spanwBonus($_COOKIE["bonus"]);
  }

  if(isset($_COOKIE["joueur_x"]) && isset($_COOKIE["joueur_y"])){
    $myLabyrinth->spawnPlayer($_COOKIE["joueur_x"], $_COOKIE["joueur_y"]);
  }

  $startEnd = $myLabyrinth->foundStartEnd();

  // si la position du joueur n'est pas mise ou si il décide de reload, on met à la position du start
  if(!isset($_COOKIE["joueur_x"]) && !isset($_COOKIE["joueur_y"]) || isset($_POST["reload"])){
    $myLabyrinth->ResetPlayerData($startEnd);
    // METTRE UNE FONCTION DE SET
  }

  if(isset($_POST["quit"])){
    $myDB->deleteLigne($_COOKIE["PHPSESSID"]);
    header("Refresh:0; url=level.php");
  }


  if(isset($_GET["movement"])){
    $myLabyrinth->direction($_GET["movement"]);
  }

  // METTRE FONCTION GET
  if($myDB->getFinish($_COOKIE["PHPSESSID"]) == 1 && $myDB->getScore($_COOKIE["PHPSESSID"]) >= 3){
    echo("<h2>Bravo vous avez gagné !</h2><h3>Vous pouvez recommencer le niveau ou quitter pour en choisir un autre");
  } elseif ($myDB->getFinish($_COOKIE["PHPSESSID"]) == 1 && $myDB->getScore($_COOKIE["PHPSESSID"]) < 3) {
    echo("<h2>Vous avez terminez le labyrinthe mais vous n'avez pas récupéré tous les bonus, vous avez perdu !</h2><h3>Vous pouvez recommencer le niveau ou quitter pour en choisir un autre");
  }


  if(isset($_POST["nickname"])){
    setcookie("pseudo", $_POST["nickname"], time() + 365*24*3600); // METTRE EN BASE
    header("Refresh:0; url=game.php");
  }

  var_dump($_COOKIE);

  var_dump($myDB->getFinish($_COOKIE["PHPSESSID"]));
  var_dump($myDB->getScore($_COOKIE["PHPSESSID"]));




  // $myLabyrinth->getZero();
  // $myLabyrinth->showBonus();

  // au lieu de mettre url=game.php dans le refresh, mettre l'url courant pour rester dynamique

  // faire une base de donnée pour les coords des bonus et/ou des pièges
  // creer une class bdd, faires des fonctions pour les différentes requêtes

  // TODO:
  // mettre les données comme level, uid, finish, score en bdd
  // mettre position du joueur, bonus, piege en cookie

  // réorganiser l'arborescence des fichiers

  // mettre un tableau, (<td> etc etc), et en php mettre des class en fonction du caractère
  //faire double boucle dans le tableau de caractère, et en fonction du caractère mettre une certaine balise avec une certaine class

  // TODO:
  // pour la base de donnée, se baser sur l'uid
  // 1 - soit on faire une requete insert into avec une condition sur l'uid
  // 2 - soit on fait deux requête
  //  2.1 - une première requete qui récupère les uid dans la table
  //      - on fait une condition pour voir si l'uid user correspond
  // 2.2  - si oui, on fait un simple update sur la ligne de l'uid
  //      - si non, on fait un insert into avec toutes les données



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

<!-- pour le jeu, caractères peu pratique, peut être générer un tableau en html et remplire les case d'une couleur pour les murs, le start/end et le perso -->
