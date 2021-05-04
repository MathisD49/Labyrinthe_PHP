<?php
  class Labyrinthe {

    // attributs
    public $path_txt_file;
    public $test;

    // contructeur
    function __construct($path_txt_file){
      $this->path_txt_file = $path_txt_file;
    }

    // fonction pour faire de notre jeu un tableau multidim
    public function parsingGame(){
      $this->test = file("" . $this->path_txt_file . "");

      foreach ($this->test as $key => $value) {
        //echo($tests . "<br>"); // value string
        $this->test[$key] = explode(" ", $value);
      }

      return $this->test;
    }

    //methode permetant d'ouvrir, de lire, et d'afficher le contenu de mon fichier texte
    public function showContent(){
      $arrayGame = $this->parsingGame();
      foreach ($arrayGame as $key => $value) {
        foreach ($arrayGame[$key] as $keys => $values) {
          echo($values . " ");
        }
        echo("<br>");
      }

      //var_dump($this->test);

      //$myFileOpen = fopen("" . $this->path_txt_file . "", 'r+');

      // while(($myFile = fgets($myFileOpen)) !== false){
      //   //echo($myFile . "<br>");
      //   $test[$count] = $myFile;
      //   $count = $count + 1;
      // }

      //var_dump(file("" . $this->path_txt_file . ""));

    }
  }
?>
