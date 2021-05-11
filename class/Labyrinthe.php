<?php
  class Labyrinthe {

    // attributs
    public $path_txt_file;
    public $test; // tableau avec les cases du jeu
    public $myDB;

    // contructeur
    function __construct($path_txt_file){
      $this->path_txt_file = $path_txt_file;
      $this->test = file("" . $this->path_txt_file . "");
      $this->parsingGame();
      require_once('Database.php');
      $this->myDB = new Database();
      $this->getCoordsBonus();
    }

    // fonction pour faire de notre jeu un tableau multidim
    public function parsingGame(){
      foreach ($this->test as $key => $value) {
        $this->test[$key] = explode(" ", $value);
      }

      return $this->test;
    }

    // methode permetant d'ouvrir, de lire, et d'afficher le contenu de mon fichier texte
    public function showContent(){
      $arrayGame = $this->test;
      foreach ($arrayGame as $key => $value) {
        foreach ($arrayGame[$key] as $keys => $values) {
          echo($values . " ");
        }
        echo("<br>");
      }
    }

    // methode qui retourne un tableau contenant les coordonnées X et Y du point de départ et d'arrivé
    public function foundStartEnd(){
      $arrayGame = $this->test;
      $start = ["x" => 0, "y" => 0];
      $end = ["x" => 0, "y" => 0];

      foreach ($arrayGame as $key => $value) {
        foreach ($arrayGame[$key] as $keys => $values) {
          if($values == "S"){
            $start["x"] = $keys;
            $start["y"] = $key;
          }
          elseif ($values == "E") {
            $end["x"] = $keys;
            $end["y"] = $key;
          }
        }
      }

      return [$start, $end];
    }

    // methode pour afficher le joueur à l'écran
    public function spawnPlayer($x, $y){
      $this->test[$y][$x] = "M";
      $this->showContent();
    }

    // methode pour savoir si il y a un mur ou non
    public function isWall($x, $y, $newX, $newY){
      if($this->test[$y+$newY][$x+$newX] == "*"){
        return True;
      } else {
        return False;
      }
    }

    // methode pour savoir si le joueur est au point d'arrivé
    public function isEnd($x, $y, $newX, $newY){
      $endCoords = $this->foundStartEnd();
      if($x+$newX == $endCoords[1]["x"] && $y+$newY == $endCoords[1]["y"]){
        return True;
      } else {
        return False;
      }
    }

    // methode utilisé pour recharger la partie
    public function ResetPlayerData($startEnd){
      setcookie("joueur_x", $startEnd[0]["x"], time() + 365*24*3600);
      setcookie("joueur_y", $startEnd[0]["y"], time() + 365*24*3600);
      setcookie("finish", 0, time() + 365*24*3600);
      header("Refresh:0; url=game.php");
    }

    // methoe utiliser pour quitter la partie
    public function Quit(){
      setcookie("level", "", time() + 365*24*3600);
      setcookie("joueur_x", "", time() + 365*24*3600);
      setcookie("joueur_y", "", time() + 365*24*3600);
      header("Refresh:0; url=level.php");
    }

    // methode pour déterminer vers quelle direction va le joueur
    public function direction($direction){
      if($direction == "Right"){
        $this->MoveHorizontal($_COOKIE["joueur_x"]+1, 1, 0);
      } elseif ($direction == "Left") {
        $this->MoveHorizontal($_COOKIE["joueur_x"]-1, -1, 0);
      } elseif ($direction == "Up") {
        $this->MoveVertical($_COOKIE["joueur_y"]-1, 0, -1);
      } elseif ($direction == "Down") {
        $this->MoveVertical($_COOKIE["joueur_y"]+1, 0, 1);
      } else {
        header("Refresh:0; url=game.php");
      }
    }

    // methode pour déplacer le joueur en bas ou en haut
    public function MoveVertical($cookie, $xAxe, $yAxe){
      if($cookie >= 0 && $cookie <= 14 && !$this->isWall($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], $xAxe, $yAxe)){
        setcookie("joueur_y", $cookie, time() + 365*24*3600);
        if($this->isEnd($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], $xAxe, $yAxe)){
          setcookie("finish", 1, time() + 365*24*3600);
        }
      }
      header("Refresh:0; url=game.php");
    }

    // methode pour déplacer le joueur à gauche ou à droite
    public function MoveHorizontal($cookie, $xAxe, $yAxe){
      if($cookie >= 0 && $cookie <= 14 && !$this->isWall($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], $xAxe, $yAxe)){
        setcookie("joueur_x", $cookie, time() + 365*24*3600);
        if($this->isEnd($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], $xAxe, $yAxe)){
          setcookie("finish", 1, time() + 365*24*3600);
        }
      }
      header("Refresh:0; url=game.php");
    }

    public function getZero(){
      $arrayZero = [];
      $arrayGame = $this->test;

      foreach ($arrayGame as $key => $value) {
        foreach($arrayGame[$key] as $keys => $values){
          if($values == "0"){
            array_push($arrayZero, [$keys, $key]);
          }
        }
      }
      return $arrayZero;
    }

    // /!\ revoir le système de gestion des doublons !!!
    public function getCoordsBonus(){
      $arrayZero = $this->getZero();
      $selectedNumber = [];
      for($i=0; $i < 3; $i++) {
        $number = rand(0,sizeof($arrayZero));
        if($i > 0){
          foreach ($selectedNumber as $value) {
            if($number == $value){
              $number = rand(0,sizeof($arrayZero));
            }
          }
        }
        $this->myDB->insertCoordsBonus($_COOKIE["PHPSESSID"], $arrayZero[$number][0], $arrayZero[$number][1]);
        array_push($selectedNumber, $number);
      }
    }

    public function showBonus(){
      $coords = $this->myDB->getCoordsBonus($_COOKIE["PHPSESSID"]);
      var_dump($coords);
      foreach ($coords as $key => $value) {
        foreach ($coords[$key] as $keys => $values) {
          //$this->test[$key][$keys] = "T";

        }
      }
    }



  }

?>
