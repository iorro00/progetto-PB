<?php
require_once("db.php");

$idPrj = $_POST["id"];
$orario = $_POST["orario"];
$newOre = $_POST["ora"];

if($orario=="oreMatt"){
  $stmtOre = $conn->prepare("UPDATE progetti_classi
                             SET ore_mattina ='".$newOre."'
                             WHERE fk_progetto=".$idPrj);
  $stmtOre->execute();
}
if($orario=="orePom"){
  $stmtOre = $conn->prepare("UPDATE progetti_classi
                             SET ore_pomeriggio ='".$newOre."'
                             WHERE fk_progetto=".$idPrj);
  $stmtOre->execute();
}
    
echo json_encode(array('success' => true));


?>