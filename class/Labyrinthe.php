<?php
  class Labyrinthe {

    // attributs
    public $path_txt_file;
    public $test; // tableau avec les cases du jeu

    // contructeur
    function __construct($path_txt_file){
      $this->path_txt_file = $path_txt_file;
      $this->test = file("" . $this->path_txt_file . "");
      $this->parsingGame();
    }

    // fonction pour faire de notre jeu un tableau multidim
    public function parsingGame(){
      foreach ($this->test as $key => $value) {
        $this->test[$key] = explode(" ", $value);
      }

      return $this->test;
    }

    //methode permetant d'ouvrir, de lire, et d'afficher le contenu de mon fichier texte
    public function showContent(){
      $arrayGame = $this->test;
      foreach ($arrayGame as $key => $value) {
        foreach ($arrayGame[$key] as $keys => $values) {
          echo($values . " ");
        }
        echo("<br>");
      }
    }

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

    public function spawnPlayer($x, $y){
      $this->test[$y][$x] = "M";
      $this->showContent();
    }

    public function isWall($x, $y, $newX, $newY){
      if($this->test[$y+$newY][$x+$newX] == "*"){
        return True;
      } else {
        return False;
      }
    }

    public function isEnd($x, $y, $newX, $newY){
      $endCoords = $this->foundStartEnd();
      if($x+$newX == $endCoords[1]["x"] && $y+$newY == $endCoords[1]["y"]){
        return True;
      } else {
        return False;
      }
    }
  }
?>
