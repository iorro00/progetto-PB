<?php
require_once("db.php");

$idPrj = $_POST["id"];
$riferimento = $_POST["riferimento"];
$nuovoRif = $_POST["newRif"];

if($riferimento=="strutt"){
  $stmtRif = $conn->prepare("UPDATE progetti
                             SET strutturale ='".$nuovoRif."'
                             WHERE id=".$idPrj);
  $stmtRif->execute();
}
if($riferimento=="orient"){
  $stmtRif = $conn->prepare("UPDATE progetti
                             SET orientamento ='".$nuovoRif."'
                             WHERE id=".$idPrj);
  $stmtRif->execute();
}
if($riferimento=="pcto"){
  $stmtRif = $conn->prepare("UPDATE progetti
                             SET PCTO ='".$nuovoRif."'
                             WHERE id=".$idPrj);
  $stmtRif->execute();
}

    
echo json_encode(array('success' => true));


?>