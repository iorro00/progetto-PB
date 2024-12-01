<?php

require_once("db.php");
$idRis = $_POST["idRis"];

$stmDel1= $conn->prepare("DELETE FROM risorseEsterne WHERE id = ".$idRis);
$stmDel1->execute();

$stmDel2= $conn->prepare("DELETE FROM progetti_risorse WHERE fk_risorsaInterna = ".$idRis);
$stmDel2->execute();

echo json_encode(array('success' => true));
?>