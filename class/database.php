<?php
// ici on ne fait que envoyer ou récupérer des infos venant de la base
  class Database {

    public $mysqli;

    function __construct(){
      $this->mysqli = new mysqli("localhost", "root", "", "labyrinthe_php");
    }

    // public function insertCoordsBonus($uid, $x, $y){
    //   $sqli = $this->mysqli;
    //   if(!$sqli->query("INSERT INTO bonus (uid, x, y) VALUES ('$uid', $x, $y)")){
    //     echo("Erreur lors de l'insertion en bdd");
    //   }
    // }

    // public function getCoordsBonus($uid){
    //   $sqli = $this->mysqli;
    //   $coords = [];
    //   if($result = $sqli->query("SELECT * FROM bonus WHERE uid='$uid'")){
    //     while($row = $result->fetch_assoc()){
    //       $coords[] = [$row['x'], $row['y']];
    //     }
    //     $result->close();
    //   }
    //   return $coords;
    // }


  }
?>
