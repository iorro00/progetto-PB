<?php
require_once("db.php");

if (isset($_POST['id']) && isset($_POST['comp']) && isset($_POST['flag'])) {
    $projectId = $_POST['id'];
    $newComp = $_POST['comp'];
    $flag = $_POST['flag'];
    $compExist = isset($_POST['compExist']) ? $_POST['compExist'] : null;

    try {
        if ($compExist) {
            // Se esiste una competenza, aggiorna quella esistente
            $stmt = $conn->prepare("UPDATE competenze SET descrizione = :newComp WHERE descrizione = :compExist AND id IN (SELECT fk_competenze FROM progetti_competenze WHERE fk_progetti = :projectId)");
            $stmt->bindParam(':newComp', $newComp);
            $stmt->bindParam(':compExist', $compExist);
            $stmt->bindParam(':projectId', $projectId);
        } else {
            // Controlla se la competenza esiste già
            $stmt = $conn->prepare("SELECT id FROM competenze WHERE descrizione = :newComp");
            $stmt->bindParam(':newComp', $newComp);
            $stmt->execute();
            $existingComp = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingComp) {
                $newCompId = $existingComp['id'];
            } else {
                // Se non esiste una competenza, aggiungi una nuova
                $stmt = $conn->prepare("INSERT INTO competenze (descrizione) VALUES (:newComp)");
                $stmt->bindParam(':newComp', $newComp);
                $stmt->execute();
                $newCompId = $conn->lastInsertId();
            }

            $stmt = $conn->prepare("INSERT INTO progetti_competenze (fk_progetti, fk_competenze) VALUES (:projectId, :newCompId)");
            $stmt->bindParam(':projectId', $projectId);
            $stmt->bindParam(':newCompId', $newCompId);
        }

        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Competenza aggiornata con successo']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento della competenza: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dati mancanti']);
}
?>