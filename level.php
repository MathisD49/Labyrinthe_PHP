<html>
  <head>
    <title>Level</title>
    <meta charset="utf-8">
  </head>
  <body>
    <h1>Choisissez votre level <?php echo($_COOKIE["pseudo"]); ?></h1>
    <a href="game.php?level=gamefiles/labyrinthe.txt">
      <span>Level 1</span>
    </a>
    <br>
    <a href="game.php?level=gamefiles/test.txt">
      <span>Level 2</span>
    </a>
  </body>
</html>
