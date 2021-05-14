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
    }

    // fonction pour faire de notre jeu un tableau multidim
    public function parsingGame(){
      foreach ($this->test as $key => $value) {
        $this->test[$key] = explode(" ", $value);
      }
      return $this->test;
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
      $this->myDB->setFinish($_COOKIE["PHPSESSID"], 0);
      $this->myDB->setScoreZero($_COOKIE["PHPSESSID"]);
      header("Refresh:0; url=game.php");
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
          $this->myDB->setFinish($_COOKIE["PHPSESSID"], 1);
        }
        if($this->myDB->getScore($_COOKIE["PHPSESSID"]) < 3){
          if ($this->isBonus($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], $xAxe, $yAxe)) {
            $this->myDB->setScore($_COOKIE["PHPSESSID"]);
          }
        }
      }
      header("Refresh:0; url=game.php");
    }

    // methode pour déplacer le joueur à gauche ou à droite
    public function MoveHorizontal($cookie, $xAxe, $yAxe){
      if($cookie >= 0 && $cookie <= 14 && !$this->isWall($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], $xAxe, $yAxe)){
        setcookie("joueur_x", $cookie, time() + 365*24*3600);
        if($this->isEnd($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], $xAxe, $yAxe)){
          $this->myDB->setFinish($_COOKIE["PHPSESSID"], 1);
        }
        if($this->myDB->getScore($_COOKIE["PHPSESSID"]) < 3){
          if ($this->isBonus($_COOKIE["joueur_x"], $_COOKIE["joueur_y"], $xAxe, $yAxe)) {
            $this->myDB->setScore($_COOKIE["PHPSESSID"]);
          }
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

    public function setCordsBonus(){
      $arrayZero = $this->getZero();
      $selectedNumber = [];
      for ($i=0; $i < 3 ; $i++) {
        $number = rand(0,sizeof($arrayZero));
        if($i > 0){
          foreach ($selectedNumber as $value) {
            while ($number == $value) {
              $number = rand(0,sizeof($arrayZero));
            }
          }
        }
        setcookie("bonus[$i][x]", $arrayZero[$number][0], time() + 365*24*3600);
        setcookie("bonus[$i][y]", $arrayZero[$number][1], time() + 365*24*3600);
        array_push($selectedNumber, $number);
      }
      header("Refresh:0; url=game.php");
    }

    public function spanwBonus($cookie){
      foreach ($cookie as $key => $value) {
        $this->test[$cookie[$key]['y']][$cookie[$key]['x']] = "T";
      }
    }

    public function isBonus($x, $y, $newX, $newY){
      $test = 0;
      foreach ($_COOKIE["bonus"] as $key => $value) {
        if($x+$newX == $_COOKIE["bonus"][$key]['x'] && $y+$newY == $_COOKIE["bonus"][$key]['y']){
          setcookie("bonus[$key][x]", '', time() - 365*24*3600, '/TP_PHP');
          setcookie("bonus[$key][y]", '', time() - 365*24*3600, '/TP_PHP');
          return True;
        }
      }
    }

    // methode permetant d'ouvrir, de lire, et d'afficher le contenu de mon fichier texte
    public function showContent(){
      $arrayGame = $this->test;
      echo("<table>");
      foreach ($arrayGame as $key => $value) {
        echo("<tr>");
        foreach ($arrayGame[$key] as $keys => $values) {
          if($values == "M"){
            echo("<th style='background-color: blue; width: 20px; height: 20px'></th>");
          }elseif ($values == "*") {
            echo("<th style='background-color: black; width: 20px; height: 20px'></th>");
          }elseif ($values == "0") {
            echo("<th style='background-color: white; width: 20px; height: 20px'></th>");
          }elseif ($values == "S") {
            echo("<th style='background-color: green; width: 20px; height: 20px'></th>");
          }elseif ($values == "E") {
            echo("<th style='background-color: red; width: 20px; height: 20px'></th>");
          }elseif ($values == "T") {
            echo("<th style='background-color: yellow; width: 20px; height: 20px'></th>");
          }
        }
        echo("</tr>");
      }
      echo("</table>");
    }
  }

?>
