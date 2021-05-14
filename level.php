<!-- faire une requete bdd por supprimer colonne uid si existe -->
<?php
  require_once('class/database.php');
  $myDB = new Database();
  $myDB->deleteLigne($_COOKIE["PHPSESSID"]);
  setcookie("level", "", time() + 365*24*3600);
?>
<html>
  <head>
    <title>Level</title>
    <meta charset="utf-8">
  </head>
  <body>
    <h1>Choisissez votre level <?php echo($_COOKIE["pseudo"]); ?></h1>
    <a href="game.php?level=labyrinthe.txt">
      <span>Level 1</span>
    </a>
    <br>
    <a href="game.php?level=test.txt">
      <span>Level 2</span>
    </a>
  </body>
</html>
