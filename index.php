<?php
  // ceci permet de creer mon objet et de lancer ma méthode
  require_once('class/Labyrinthe.php');
  $test = new Labyrinthe("labyrinthe.txt");

  if(isset($_COOKIE["joueur_x"]) && isset($_COOKIE["joueur_y"])){
    $test->spawnPlayer($_COOKIE["joueur_x"]*2, $_COOKIE["joueur_y"]);
  }

  $autretess = $test->foundStartEnd();

  // si la position du joueur n'est pas mise ou si il décide de reload, on met à la position du start
  if(!isset($_COOKIE["joueur_x"]) && !isset($_COOKIE["joueur_y"]) || isset($_POST["reload"])){
    setcookie("joueur_x", $autretess[0]["x"], time() + 365*24*3600);
    setcookie("joueur_y", $autretess[0]["y"], time() + 365*24*3600);
    header("Refresh:0; url=index.php");
  }

  if(isset($_GET["goRight"])){
    if($_COOKIE["joueur_x"]+1 <= 14){
      setcookie("joueur_x", $_COOKIE["joueur_x"]+1, time() + 365*24*3600);
      header("Refresh:0; url=index.php");
    }
  }

  if(isset($_GET["goLeft"])){
    if($_COOKIE["joueur_x"]-1 >= 0){
      setcookie("joueur_x", $_COOKIE["joueur_x"]-1, time() + 365*24*3600);
      header("Refresh:0; url=index.php");
    }
  }

  if(isset($_GET["goUp"])){
    if($_COOKIE["joueur_y"]-1 >= 0){
      setcookie("joueur_y", $_COOKIE["joueur_y"]-1, time() + 365*24*3600);
      header("Refresh:0; url=index.php");
    }
  }

  if(isset($_GET["goDown"])){
    if($_COOKIE["joueur_y"]+1 <= 14){
      setcookie("joueur_y", $_COOKIE["joueur_y"]+1, time() + 365*24*3600);
      header("Refresh:0; url=index.php");
    }
  }
?>

<html>
  <head>
    <title>Labyrinthe</title>
    <meta charset="utf-8">
  </head>
  <body>
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


<!-- TODO :
au clique sur les boutons (gauche droite haut bas) renvoie vers une url ex: ?x=1 OU ?y=-1
ensuite faire les tests pour savoir si il peut se déplacer
si oui le déplacer et mettre dans les nouveaux cookies
sinon ne rien faire -->


<!-- TODO :
revoir le reload (clear $_GET) -->
