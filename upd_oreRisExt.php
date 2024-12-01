<?php
require_once("db.php");

$idPrj = $_POST["id"];
$idRis = $_POST["idRis"];
$newOra = $_POST["newOreDoc"];


$stmtOre = $conn->prepare("UPDATE risorseEsterne
                             SET oreDocenza ='".$newOra."'
                             WHERE id=".$idRis);
$stmtOre->execute();

echo json_encode(array('success' => true));
?>