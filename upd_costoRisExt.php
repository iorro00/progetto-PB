<?php
require_once("db.php");

$idPrj = $_POST["id"];
$idRis = $_POST["idRis"];
$newCosto = $_POST["newCosto"];


$stmtCosto = $conn->prepare("UPDATE risorseEsterne
                             SET costoPrevisto =".$newCosto."
                             WHERE id=".$idRis);
$stmtCosto->execute();

echo json_encode(array('success' => true));
?>