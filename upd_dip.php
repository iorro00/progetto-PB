<?php

require_once("db.php");

$idPrj = $_POST["id"];
$dip = $_POST["dip"];


$stmDip = $conn->prepare("SELECT id FROM dipartimento WHERE nome = :nome");
          $stmDip->bindParam(':nome', $dip);
          $stmDip->execute();
          $idDip = $stmDip->fetchColumn(); // Ottieni direttamente l'ID

          $stmtDip = $conn->prepare("UPDATE progetti
                                            SET fk_dipartimento = :idDip
                                            WHERE id = :idPrj");
          $stmtDip->bindParam(':idDip', $idDip);
          $stmtDip->bindParam(':idPrj', $idPrj);
          $stmtDip->execute();
echo json_encode(array('success' => true));
?>