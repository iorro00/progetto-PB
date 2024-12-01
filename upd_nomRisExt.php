<?php
require_once("db.php");

$idPrj = $_POST["id"];
$idRis = $_POST["idRis"];
$nomin = $_POST["newNom"];

$stmtIdNom = $conn->prepare("UPDATE risorseEsterne SET nominativo ='".$nomin."' WHERE id=".$idRis);
$stmtIdNom->execute();

echo json_encode(array('success' => true));
?>