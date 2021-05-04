<?php
  class Labyrinthe {

    // attributs
    public $path_txt_file;
    public $array_game = [];

    // contructeur
    function __construct($path_txt_file){
      $this->path_txt_file = $path_txt_file;
    }

    //methode permetant d'ouvrir, de lire, et d'afficher le contenu de mon fichier texte
    public function showContent(){
      $test = file("" . $this->path_txt_file . "");

      foreach ($test as $key => $value) {
        //echo($tests . "<br>"); // value string
        $test[$key] = explode(" ", $value);
      }

      //var_dump($test);

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
