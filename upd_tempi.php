<?php
require_once("db.php");

$idPrj = $_POST["id"];
$tempi = $_POST["tempi"];

$stmtRif = $conn->prepare("UPDATE progetti
                           SET tempi_svolgimento ='".$tempi."'
                           WHERE id=".$idPrj);
$stmtRif->execute();

echo json_encode(array('success' => true));
?>