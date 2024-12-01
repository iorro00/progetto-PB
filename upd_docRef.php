<?php

require_once("db.php");

$idPrj = $_POST["id"];
$docRef = $_POST["docRef"];


$stmRef = $conn->prepare("SELECT id FROM docenteReferente WHERE nominativo = :nominativo");
          $stmRef->bindParam(':nominativo', $docRef);
          $stmRef->execute();
          $idDocRef = $stmRef->fetchColumn(); // Ottieni direttamente l'ID

          $stmtDocRef = $conn->prepare("UPDATE progetti
                                            SET fk_docenteReferente = :idRef
                                            WHERE id = :idPrj");
          $stmtDocRef->bindParam(':idRef', $idDocRef);
          $stmtDocRef->bindParam(':idPrj', $idPrj);
          $stmtDocRef->execute();
echo json_encode(array('success' => true));
?>