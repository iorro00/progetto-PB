<?php
require_once("db.php");

$idPrj = $_POST["id"];
$aspDid = $_POST["aspDid"];
$nuovoAspDid = $_POST["newAsp"];

if($aspDid=="contesto"){
  $stmtAsp = $conn->prepare("UPDATE progetti
                             SET analisi_contesto ='".$nuovoAspDid."'
                             WHERE id=".$idPrj);
  $stmtAsp->execute();
}
if($aspDid=="obb"){
  $stmtAsp = $conn->prepare("UPDATE progetti
                             SET obbiettivi_attesi ='".$nuovoAspDid."'
                             WHERE id=".$idPrj);
  $stmtAsp->execute();
}
if($aspDid=="attiv"){
  $stmtAsp = $conn->prepare("UPDATE progetti
                             SET attivita_previste ='".$nuovoAspDid."'
                             WHERE id=".$idPrj);
  $stmtAsp->execute();
}
if($aspDid=="strum"){
  $stmtAsp = $conn->prepare("UPDATE progetti
                             SET metodologia_e_strumenti ='".$nuovoAspDid."'
                             WHERE id=".$idPrj);
  $stmtAsp->execute();
}
if($aspDid=="luoghi"){
  $stmtAsp = $conn->prepare("UPDATE progetti
                             SET luoghi_svolgimento ='".$nuovoAspDid."'
                             WHERE id=".$idPrj);
  $stmtAsp->execute();
}
if($aspDid=="finale"){
  $stmtAsp = $conn->prepare("UPDATE progetti
                             SET verifica_itinere_e_finale ='".$nuovoAspDid."'
                             WHERE id=".$idPrj);
  $stmtAsp->execute();
}
if($aspDid=="docum"){
  $stmtAsp = $conn->prepare("UPDATE progetti
                             SET documentazione ='".$nuovoAspDid."'
                             WHERE id=".$idPrj);
  $stmtAsp->execute();
}


    
echo json_encode(array('success' => true));


?>