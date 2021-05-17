<?php
// ici on ne fait que envoyer ou récupérer des infos venant de la base
  class Database {

    public $mysqli;

    function __construct(){
      $this->mysqli = new mysqli("localhost", "root", "", "labyrinthe_php");
    }

    public function deleteLigne($uid){
      $sqli = $this->mysqli;
      if(!$sqli->query("DELETE FROM information WHERE uid='$uid'")){
        echo("erreur lors de la suppression");
      }
    }

    public function setData($level, $uid){
      $sqli = $this->mysqli;
      if(!$sqli->query("INSERT INTO information (level, uid, finish, score) VALUES ('$level', '$uid', 0, 0)")){
        echo("echec lors de l'écriture");
      }
    }

    public function getLevel($uid){
      $sqli = $this->mysqli;
      $gameLevel = "";
      if($result = $sqli->query("SELECT level FROM information WHERE uid='$uid'")){
        while($row = $result->fetch_assoc()){
          $gameLevel = $row;
        }
      }
      return $gameLevel;
    }

    public function getFinish($uid){
      $sqli = $this->mysqli;
      $gameFinish = 0;
      if($result = $sqli->query("SELECT finish FROM information WHERE uid='$uid'")){
        while($row = $result->fetch_assoc()){
          $gameFinish = $row['finish'];
        }
      }
      return (int)$gameFinish;
    }

    public function getScore($uid){
      $sqli = $this->mysqli;
      $gameScore = 0;
      if($result = $sqli->query("SELECT score FROM information WHERE uid='$uid'")){
        while($row = $result->fetch_assoc()){
          $gameScore = $row['score'];
        }
      }
      return (int)$gameScore;
    }

    public function setFinish($uid, $state){
      $sqli = $this->mysqli;
      if(!$sqli->query("UPDATE information SET finish=$state WHERE uid='$uid'")){
        echo("echec lors de l'update de finish");
      }
    }

    public function setScoreZero($uid){
      $sqli = $this->mysqli;
      if(!$sqli->query("UPDATE information SET score=0 WHERE uid='$uid'")){
        echo("echec lors de l'update de score");
      }
    }

    public function setScore($uid){
      $sqli = $this->mysqli;
      $currentScore = $this->getScore($uid);
      if(!$sqli->query("UPDATE information SET score=$currentScore+1 WHERE uid='$uid'")){
        echo("echec lors de l'update de score");
      }
    }

    public function verifUid($uid){
      $sqli = $this->mysqli;
      if($result = $sqli->query("SELECT * FROM information WHERE uid='$uid'")){
        if($result->num_rows > 0){
          return True;
        } else {
          return False;
        }
      }
    }
  }
?>
