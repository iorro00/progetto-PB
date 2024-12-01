<?php
require_once("db.php");

$idPrj = $_POST["idPrj"];
$nomeRis = $_POST['nomRis'];
$oreDocRis = $_POST['oreDocRis'];
$costoRis = $_POST['costoRis'];
$costoEvenRis = $_POST['costoEvenRis'];



$stmtRis = $conn->prepare("INSERT INTO risorseEsterne (nominativo,oreDocenza,costoPrevisto,costiEventuali) VALUES ('".$nomeRis."',".$oreDocRis.",".$costoRis.",'".$costoEvenRis."')");
$stmtRis->execute();
$idRis = $conn->lastInsertId();

$stmtRis2 = $conn->prepare("INSERT INTO progetti_risorse (fk_risorsaEsterna,fk_progetti) VALUES (".$idRis.",".$idPrj.")");
$stmtRis2->execute();


echo json_encode(array('success' => true));
?>