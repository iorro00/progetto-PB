<?php
// Importa la connessione al database
require_once("db.php");

// Recupera i dati inviati tramite POST
$classi = $_POST["classi"];
$nominativo = $_POST["nominativo"];
$progetti = [];

// Se sono state selezionate delle classi
if (!empty($classi)) {
    foreach ($classi as $classe) {
        
        // Estrae l'anno e la sezione dalla stringa della classe
        $anno = (int) $classe[0];
        $sezione = substr($classe, 1);

        // Se l'utente è amministratore, non applico il filtro sul docente
        if ($nominativo == "Amministratore") {
            $stmt = $conn->prepare("SELECT p.id
                                    FROM progetti p
                                    JOIN progetti_classi pc ON p.id = pc.fk_progetto
                                    JOIN classi c ON pc.fk_classe = c.id
                                    WHERE c.anno_classe = :anno
                                    AND c.sezione = :sezione");
            $stmt->bindParam(':anno', $anno, PDO::PARAM_INT);
            $stmt->bindParam(':sezione', $sezione, PDO::PARAM_STR);
        } else {
            // Se non è amministratore, filtro i progetti per il docente referente
            $stmt = $conn->prepare("SELECT p.id
                                    FROM progetti p
                                    JOIN progetti_classi pc ON p.id = pc.fk_progetto
                                    JOIN classi c ON pc.fk_classe = c.id
                                    JOIN docenteReferente d ON p.fk_docenteReferente = d.id
                                    WHERE d.nominativo = :nominativo
                                    AND c.anno_classe = :anno
                                    AND c.sezione = :sezione");
            $stmt->bindParam(':nominativo', $nominativo, PDO::PARAM_STR);
            $stmt->bindParam(':anno', $anno, PDO::PARAM_INT);
            $stmt->bindParam(':sezione', $sezione, PDO::PARAM_STR);
        }
        $stmt->execute();
        $result = $stmt->fetchAll();

        // Salvo gli ID dei progetti trovati
        foreach ($result as $row) {
            $progetti[] = $row["id"];
        }
    }
} else {
    // Se non sono state selezionate classi, eseguo una query generale
    if ($nominativo == "Amministratore") {
        $stmt = $conn->prepare("SELECT p.id
                                FROM progetti p
                                JOIN progetti_classi pc ON p.id = pc.fk_progetto
                                JOIN classi c ON pc.fk_classe = c.id");
    } else {
        $stmt = $conn->prepare("SELECT p.id
                                FROM progetti p
                                JOIN progetti_classi pc ON p.id = pc.fk_progetto
                                JOIN classi c ON pc.fk_classe = c.id
                                JOIN docenteReferente d ON p.fk_docenteReferente = d.id
                                WHERE d.nominativo = :nominativo");
        $stmt->bindParam(':nominativo', $nominativo, PDO::PARAM_STR);
    }
    $stmt->execute();
    $result = $stmt->fetchAll();

    // Salvo gli ID dei progetti trovati
    foreach ($result as $row) {
        $progetti[] = $row["id"];
    }
}

// Rimuovo eventuali duplicati e reindicizzo l'array
$ArrPrj = array_values(array_unique($progetti));

// Creo la tabella HTML
$table = "<table id='tbRendi' class='tbFiltered'>
                    <tr>
                        <th style='display:none;'></th>
                        <th>Titolo Progetto</th>
                        <th>Dipartimento</th>
                        <th>Docente Referente</th>
                        <th></th>
                        <th></th>
                    </tr>";

// Per ogni progetto trovato, ricostruisco la riga della tabella
foreach ($ArrPrj as $progetto) {
    $stm = $conn->prepare("SELECT id, titolo, fk_dipartimento, fk_docenteReferente FROM progetti WHERE id = :progetto ORDER BY titolo asc");
    $stm->bindParam(':progetto', $progetto, PDO::PARAM_INT);
    $stm->execute();
    $pr = $stm->fetchAll();
    
    foreach ($pr as $row) {
        // Recupero il nome del dipartimento se presente
        $varDip = '';
        if ($row["fk_dipartimento"]) {
            $dip = $conn->prepare("SELECT nome FROM dipartimento WHERE id = :dipId");
            $dip->bindParam(':dipId', $row["fk_dipartimento"], PDO::PARAM_INT);
            $dip->execute();
            $resultDip = $dip->fetch();
            $varDip = $resultDip["nome"];
        }
        // Recupero il nominativo del docente referente se presente
        $varRef = '';
        if ($row["fk_docenteReferente"]) {
            $ref = $conn->prepare("SELECT nominativo FROM docenteReferente WHERE id = :refId");
            $ref->bindParam(':refId', $row["fk_docenteReferente"], PDO::PARAM_INT);
            $ref->execute();
            $resultRef = $ref->fetch();
            $varRef = $resultRef["nominativo"];
        }
        
        // Costruisco la riga della tabella con i dati del progetto
        $table .= "<tr>
                        <td style='display:none;'>" . $row["id"] . "</td>
                        <td onclick='eventi(" . $row["id"] . ")'>" . $row["titolo"] . "</td>
                        <td onclick='eventi(" . $row["id"] . ")'>" . $varDip . "</td>
                        <td onclick='eventi(" . $row["id"] . ")'>" . $varRef . "</td>
                        <td class='icon-mod'><div prendi-id2='" . $row["id"] . "' style='display:none;' class='extra-option-left' id='extraOptionLeft-" . $row["id"] . "' onclick='modifica(" . $row["id"] . ")'><img src='img/modifica.png' class='modIcon'></div></td>
                        <td class='icon-del'><div prendi-id='" . $row["id"] . "' style='display:none;' class='extra-option' id='extraOption-" . $row["id"] . "' onclick='elimina(" . $row["id"] . ")'><img src='img/delete.png' class='deleteIcon'></div></td>
                   </tr>";
    }
}
$table .= "</table>";

// Restituisco la tabella in formato JSON
echo json_encode(array('success' => true, 'risposta' => $table));
?>
