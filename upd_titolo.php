<?php
require_once("db.php");

$idPrj = $_POST["id"];
$titolo = $_POST["titolo"];


$stmtTitolo = $conn->prepare("UPDATE progetti
                              SET titolo ='".$titolo."'
                              WHERE id = ".$idPrj);
$stmtTitolo->execute();
    
echo json_encode(array('success' => true, 'risposta' => $idPrj));


?>

          
       