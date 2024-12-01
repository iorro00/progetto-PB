<?php
require_once("db.php");
$mess = $_POST["mess"];

  if($mess == "int"){
      $id = $_POST["id"];
      $oreCurrEff = $_POST["oreCurrValue"];
      $oreExtraCurrEff = $_POST["oreExtraCurrValue"];
      $oreSorvEff = $_POST["oreSorvValue"];
      $oreProgEff = $_POST["oreProgValue"];

      $stmRisInt= $conn->prepare("UPDATE risorseInterne SET OreCurricolariEffettive = ".$oreCurrEff.", OreExtraCurricolariEffettive = ".$oreExtraCurrEff.", OreSorveglianzaEffettive = ".$oreSorvEff.", OreProgettazioneEffettive = ".$oreProgEff." WHERE id = " . $id);
      $stmRisInt -> execute();
  }
  if($mess == "ext"){
	  $id = $_POST["id"];
      $oreDocEff = $_POST["oreDocValue"];
      $costoEff = $_POST["costoDocValue"];
      
      $stmRisExt= $conn->prepare("UPDATE risorseEsterne SET oreDocenzaEffettive = ".$oreDocEff.", costoEffettivo = ".$costoEff." WHERE id = " .$id);
      $stmRisExt -> execute();
  }
?>