<?php
  // ceci permet de creer mon objet et de lancer ma méthode
  require_once('class/Labyrinthe.php');
  $test = new Labyrinthe("labyrinthe.txt");
  $test->showContent();
?>

<!-- pour aller gauche droite etc etc utiliser la methode GET -->

<!-- pour le déplacement, enregistrer dans les cookies/session la positon du joueur -->
<!-- pour le faire aller à droite et à gauche, utiliser GET, incrémenter etc la valeur dans les cookies et regénérer la page pour placer le joueur aux nouvelles coordonnées -->


<!-- pour le jeu, caractères peu pratique, peut être générer un tableau en html et remplire les case d'une couleur pour les murs, le start/end et le perso -->



<!-- faire un tableau multi dimensionnel 15/15 et mettre les caractères dedans -->
<!-- afficher le labyrinthe depuis ce tableau -->
<!-- effectuer tous les changement de direction et de vérification à partir du tableau -->
<!-- pour afficher le tableau sur la page, faire une double boucle et mettre des <br> à la fin de chaque boucle -->
