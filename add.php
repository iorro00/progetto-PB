<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Raccolta dei dati dal form
$titolo = $_POST["title"];
$dipart = $_POST["dip"];
$oreMat = $_POST["oreMat"];
$orePom = $_POST["orePom"];
$strutt = $_POST["strutt"];
$orient = $_POST["orient"];
$percorsi = $_POST["percorsi"];
$origine = $_POST["originePrj"];
$contesto = $_POST["contesto"];
$obiettivi = $_POST["obb"];
$attivita = $_POST["attiv"];
$metodi = $_POST["metodi"];
$tempi = $_POST["mese"];
$luoghi = $_POST["luog"];
$verifica = $_POST["verifica"];
$documentazione = $_POST["document"];
$progettazione_potenziamento = isset($_POST["progettazione_potenziamento"]) ? $_POST["progettazione_potenziamento"] : "";
$docenza_potenziamento = isset($_POST["docenza_potenziamento"]) ? $_POST["docenza_potenziamento"] : "";
$progettazione_pcto = isset($_POST["progettazione_pcto"]) ? $_POST["progettazione_pcto"] : "";
$docenza_pcto = isset($_POST["docenza_pcto"]) ? $_POST["docenza_pcto"] : "";
$progettazione_interno = isset($_POST["progettazione_interno"]) ? $_POST["progettazione_interno"] : "";
$docenza_interno = isset($_POST["docenza_interno"]) ? $_POST["docenza_interno"] : "";
$progettazione_esterno = isset($_POST["progettazione_esterno"]) ? $_POST["progettazione_esterno"] : "";
$docenza_esterno = isset($_POST["docenza_esterno"]) ? $_POST["docenza_esterno"] : "";

// Array per l'area competenza, basato sui ruoli
$data = array(
    "Docente potenziamentoP" => $progettazione_potenziamento,
    "Docente potenziamentoD" => $docenza_potenziamento,
    "Referente PCTOP" => $progettazione_pcto,
    "Referente PCTOD" => $docenza_pcto,
    "Docente internoP" => $progettazione_interno,
    "Docente internoD" => $docenza_interno
);

$numris = $_POST["numRisorse"];
$numrisext = $_POST["numRisorseExt"];

$comp = array();
for ($i = 1; $i <= 8; $i++) {
    $comp["comp$i"] = isset($_POST["comp$i"]) ? $_POST["comp$i"] : null;
}

$classiSelezionate = array();
foreach ($_POST as $nomeCampo => $valoreCampo) {
    // Verifica se il nome del campo corrisponde a una classe (checkbox con classe 'classe')
    if (isset($valoreCampo) && $valoreCampo === $nomeCampo && isset($_POST[$nomeCampo])) {
        $classiSelezionate[] = $valoreCampo;
    }
}

$tempiConcatenati = implode(", ", $tempi);
$mesiSelezionati = $tempiConcatenati;

require_once("db.php");

// Ottenimento dell'id del docente referente
$stm0 = $conn->prepare("SELECT id FROM docenteReferente WHERE nominativo = ?");
$stm0->execute([$_SESSION["nominativo"]]);
$idDoc = $stm0->fetch(PDO::FETCH_ASSOC);

if (!$idDoc) {
    die("Errore: Nessun docenteReferente trovato per questo nominativo.");
}

$res = $idDoc["id"];

// Inserimento del progetto
$stm = $conn->prepare("INSERT INTO progetti(titolo, fk_dipartimento, strutturale, orientamento, PCTO, origineProgetto, analisi_contesto, obbiettivi_attesi, attivita_previste, metodologia_e_strumenti, tempi_svolgimento, luoghi_svolgimento, verifica_itinere_e_finale, documentazione, fk_docenteReferente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stm->execute([$titolo, $dipart, $strutt, $orient, $percorsi, $origine, $contesto, $obiettivi, $attivita, $metodi, $mesiSelezionati, $luoghi, $verifica, $documentazione, $res]);

if($stm->rowCount()){
    $id = $conn->lastInsertId();

    // Inserimento delle competenze
    foreach($comp as $campo => $valore){
        if(!empty($valore)){
            $stm3 = $conn->prepare("INSERT INTO progetti_competenze(fk_progetti, fk_competenze) VALUES (?, ?)");
            $stm3->execute([$id, $valore]);
        }
    }

    // Inserimento delle classi associate al progetto
    foreach ($classiSelezionate as $classe) {
        $anno = substr($classe, 0, 1); 
        $sezione = substr($classe, 1);
        $stm8 = $conn->prepare("SELECT id FROM classi WHERE anno_classe = ? AND sezione = ?");
        $stm8->execute([$anno, $sezione]);
        $result = $stm8->fetchAll();
        foreach($result as $row) {
            $stm9 = $conn->prepare("INSERT INTO progetti_classi(ore_mattina, ore_pomeriggio, fk_classe, fk_progetto) VALUES (?, ?, ?, ?)");
            $stm9->execute([$oreMat, $orePom, $row['id'], $id]);
        }
    }

    $risorse = array();
    // Loop per inserire le risorse interne
    for ($i = 0; $i < $numris; $i++) {
        $risorse[$i] = array(
            'nome' => $_POST['risorse'.$i.'nome'],
            'ruolo' => $_POST['risorse'.$i.'ruolo'],
            'progett' => $_POST['risorse'.$i.'progett'],
            'oreCurr' => $_POST['risorse'.$i.'oreCurr'],
            'oreExtraCurr' => $_POST['risorse'.$i.'oreExtraCurr'],
            'sorveglianza' => $_POST['risorse'.$i.'sorveglianza']
        );
    }
    
    $risorseext = array();
    // Loop per inserire le risorse esterne
    for ($j = 0; $j < $numrisext; $j++) {
        $risorseext[$j] = array(
            'nome' => $_POST['risorseExt'.$j.'nome'],
            'ore' => $_POST['risorseExt'.$j.'ore'],
            'costo' => $_POST['risorseExt'.$j.'costo'],
            'costiEventuali' => $_POST['risorseExt'.$j.'eventualicosti']
        );      
    }

    // Inserimento delle risorse interne e collegamento con il progetto
    foreach ($risorse as $risorsa) {
        // Inserimento nella tabella risorseInterne
        $stm4 = $conn->prepare("INSERT INTO risorseInterne (nominativo, oreCurricolari, oreExtraCurricolari, oreSorveglianza, oreProgettazione) VALUES (?, ?, ?, ?, ?)");
        $stm4->execute([
            $risorsa['nome'],
            $risorsa['oreCurr'],
            $risorsa['oreExtraCurr'],
            $risorsa['sorveglianza'],
            $risorsa['progett']
        ]);
        $id2 = $conn->lastInsertId();
        
        $flag = false;
        // Verifica del ruolo per assegnare l'area competenza
        foreach ($data as $key => $value) {
            $key_without_last_letter = substr($key, 0, -1);
            if ($key_without_last_letter == $risorsa['ruolo'] && !empty($value)) {
                $flag = true;
                $stm5 = $conn->prepare("INSERT INTO progetti_risorse(areaCompetenza, ruolo, fk_risorsaInterna, fk_progetti) VALUES (?, ?, ?, ?)");
                $stm5->execute([$value, $risorsa['ruolo'], $id2, $id]);
                break; // interrompe il ciclo dopo il primo inserimento valido
            }
        }
        if (!$flag) {
            $stm5 = $conn->prepare("INSERT INTO progetti_risorse(ruolo, fk_risorsaInterna, fk_progetti) VALUES (?, ?, ?)");
            $stm5->execute([$risorsa['ruolo'], $id2, $id]);
        }
    }

    // Inserimento delle risorse esterne e collegamento con il progetto
    foreach ($risorseext as $index => $risorsaext) {
        $stm6 = $conn->prepare("INSERT INTO risorseEsterne (nominativo, oreDocenza, costoPrevisto, costiEventuali) VALUES (?, ?, ?, ?)");
        $stm6->execute([
            $risorsaext['nome'],
            $risorsaext['ore'],
            $risorsaext['costo'],
            $risorsaext['costiEventuali']
        ]);
        $id3 = $conn->lastInsertId();
        
        $stm7 = $conn->prepare("INSERT INTO progetti_risorse(fk_risorsaEsterna, fk_progetti) VALUES (?, ?)");
        $stm7->execute([$id3, $id]);
    }
    header("Location: ins_visua_project.php");
}
?>
