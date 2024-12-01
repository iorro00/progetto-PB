<?php
require_once("db.php");

$idPrj = $_POST["id"];
$idRis = $_POST["idRis"];
$newRuolo = $_POST["newRuolo"];

  $stmtRuolo = $conn->prepare("UPDATE progetti_risorse
                             SET ruolo ='".$newRuolo."'
                             WHERE fk_risorsaInterna=".$idRis);
  $stmtRuolo->execute();

    
echo json_encode(array('success' => true));


?>