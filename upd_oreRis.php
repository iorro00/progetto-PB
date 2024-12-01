<?php
require_once("db.php");

$idPrj = $_POST["id"];
$idRis = $_POST["idRis"];
$newOra = $_POST["newOre"];
$type = $_POST["tipologia"];

if(strpos($type, "oreCurrRis") !== false){
  $stmtOre = $conn->prepare("UPDATE risorseInterne
                             SET oreCurricolari ='".$newOra."'
                             WHERE id=".$idRis);
  $stmtOre->execute();
}
if(strpos($type, "oreExtraCurrRis") !== false){
  $stmtOre = $conn->prepare("UPDATE risorseInterne
                             SET oreExtraCurricolari ='".$newOra."'
                             WHERE id=".$idRis);
  $stmtOre->execute();
}
if(strpos($type, "oreSorvRis") !== false){
  $stmtOre = $conn->prepare("UPDATE risorseInterne
                             SET oreSorveglianza ='".$newOra."'
                             WHERE id=".$idRis);
  $stmtOre->execute();
}
if(strpos($type, "oreProgRis") !== false){
  $stmtOre = $conn->prepare("UPDATE risorseInterne
                             SET oreProgettazione ='".$newOra."'
                             WHERE id=".$idRis);
  $stmtOre->execute();
}
    
echo json_encode(array('success' => true));
?>