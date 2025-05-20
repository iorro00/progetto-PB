<?php
require_once("db.php");

// Leggo il parametro che indica se sto aggiornando risorsa interna ("int")
// o esterna ("ext")
$mess = $_POST["mess"] ?? '';

if ($mess === "int") {
    // --- Risorse interne ---
    // Recupero e valido gli input
    $id               = isset($_POST['id'])               ? (int) $_POST['id']               : 0;
    $oreCurrEff       = isset($_POST['oreCurrValue'])     ? (int) $_POST['oreCurrValue']     : 0;
    $oreExtraCurrEff  = isset($_POST['oreExtraCurrValue'])? (int) $_POST['oreExtraCurrValue']: 0;
    $oreSorvEff       = isset($_POST['oreSorvValue'])     ? (int) $_POST['oreSorvValue']     : 0;
    $oreProgEff       = isset($_POST['oreProgValue'])     ? (int) $_POST['oreProgValue']     : 0;

    // Preparo la UPDATE con placeholder
    $sql = "
      UPDATE risorseInterne
         SET OreCurricolariEffettive      = :oreCurr,
             OreExtraCurricolariEffettive = :oreExtra,
             OreSorveglianzaEffettive     = :oreSorv,
             OreProgettazioneEffettive    = :oreProg
       WHERE id = :id
    ";
    $stmt = $conn->prepare($sql);

    // Associo i parametri in modo sicuro
    $stmt->bindParam(':oreCurr',  $oreCurrEff,     PDO::PARAM_INT);
    $stmt->bindParam(':oreExtra', $oreExtraCurrEff, PDO::PARAM_INT);
    $stmt->bindParam(':oreSorv',  $oreSorvEff,      PDO::PARAM_INT);
    $stmt->bindParam(':oreProg',  $oreProgEff,      PDO::PARAM_INT);
    $stmt->bindParam(':id',       $id,              PDO::PARAM_INT);

    // Eseguo la query
    $stmt->execute();

    // (Opzionale) restituisco un JSON di conferma
    echo json_encode(['success' => true]);
    exit;
}

if ($mess === "ext") {
    // --- Risorse esterne ---
    // Recupero e valido gli input
    $id         = isset($_POST['id'])           ? (int) $_POST['id']           : 0;
    $oreDocEff  = isset($_POST['oreDocValue'])  ? (int) $_POST['oreDocValue']  : 0;
    $costoEff   = isset($_POST['costoDocValue'])? (int) $_POST['costoDocValue']: 0;

    // Preparo la UPDATE con placeholder
    $sql = "
      UPDATE risorseEsterne
         SET oreDocenzaEffettive = :oreDoc,
             costoEffettivo      = :costoEf
       WHERE id = :id
    ";
    $stmt = $conn->prepare($sql);

    // Associo i parametri
    $stmt->bindParam(':oreDoc',   $oreDocEff, PDO::PARAM_INT);
    $stmt->bindParam(':costoEf',  $costoEff,  PDO::PARAM_INT);
    $stmt->bindParam(':id',       $id,        PDO::PARAM_INT);

    // Eseguo la query
    $stmt->execute();

    // (Opzionale) restituisco un JSON di conferma
    echo json_encode(['success' => true]);
    exit;
}

// Se arriviamo qui, parametri non validi
echo json_encode([
    'success' => false,
    'message' => 'Parametri mancanti o non validi'
]);
