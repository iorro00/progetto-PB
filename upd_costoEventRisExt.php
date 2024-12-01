<?php
require_once("db.php");

$idPrj = $_POST["id"];
$idRis = $_POST["idRis"];
$newCostoEvent = $_POST["newCostoEvent"];


$stmtCostiEvent = $conn->prepare("UPDATE risorseEsterne
                             	  SET costiEventuali ='".$newCostoEvent."'
                             	  WHERE id=".$idRis);
$stmtCostiEvent->execute();

echo json_encode(array('success' => true));
?>