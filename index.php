<?php
  // ceci permet de creer mon objet et de lancer ma méthode
  require_once('class/Labyrinthe.php');
  $test = new Labyrinthe("labyrinthe.txt");
  $test->showContent();
  $autretess = $test->foundStartEnd();
  //var_dump($autretess);
  setcookie("start_x", $autretess[0]["x"], time() + 365*24*3600);
  setcookie("start_y", $autretess[0]["y"], time() + 365*24*3600);
  setcookie("end_x", $autretess[1]["x"], time() + 365*24*3600);
  setcookie("end_y", $autretess[1]["y"], time() + 365*24*3600);
  var_dump($_COOKIE);
?>

<html>
  <head>
    <title>Labyrinthe</title>
    <meta charset="utf-8">
  </head>
  <body>
    <div>
      <form>
        <input type="button" value="Right" name="goRight">
        <input type="button" value="Left" name="goLeft">
        <input type="button" value="Up" name="goUp">
        <input type="button" value="Down" name="goDown">
      </form>

      <form>
        <input type="button" value="Reload" name="reload">
      </form>

      <form>
        <input type="text" placeholder="nickname" name="nickname">
        <input type="button" value="Rename" name="rename">
      </form>
    </div>
  </body>
</html>

<!-- ne pas mettre la position du start end dans les cookies, les garder dans la variable et mettre la position du joueur dans les cookies
a sovoir que la position du joueur au début dépendra du start -->



<!-- pour aller gauche droite etc etc utiliser la methode GET -->

<!-- pour le déplacement, enregistrer dans les cookies/session la positon du joueur -->
<!-- pour le faire aller à droite et à gauche, utiliser GET, incrémenter etc la valeur dans les cookies et regénérer la page pour placer le joueur aux nouvelles coordonnées -->


<!-- pour le jeu, caractères peu pratique, peut être générer un tableau en html et remplire les case d'une couleur pour les murs, le start/end et le perso -->



<!-- faire un tableau multi dimensionnel 15/15 et mettre les caractères dedans -->
<!-- afficher le labyrinthe depuis ce tableau -->
<!-- effectuer tous les changement de direction et de vérification à partir du tableau -->
<!-- pour afficher le tableau sur la page, faire une double boucle et mettre des <br> à la fin de chaque boucle -->
