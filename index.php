<?php
  if(isset($_POST["pseudo"])){
    setcookie("pseudo", $_POST["pseudo"], time() + 365*24*3600);
    header("Refresh:0; url=level.php");
  }

  if(isset($_COOKIE["pseudo"])){
    header("Refresh:0; url=Level.php");
  }


?>

<html>
  <head>
    <title>Bienvenue</title>
    <meta charset="utf-8">
  </head>
  <body>
    <h1>Bienvenue nouveau joueur</h1>
    <h2>Veuillez entrer votre pseudo</h2>
    <form action="" method="POST">
      <input type="text" name="pseudo" placeholder="Pseudo">
      <input type="submit" name="pseudoBtn" value="valider">
    </form>
  </body>
</html>
