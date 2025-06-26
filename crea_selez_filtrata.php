<?php
require_once("db.php");
$classi = $_POST["classi"];
// Array associativo per evitare duplicati
$progetti_associativi = [];
$oreCurr = 0;
$oreExtraCurr = 0;
$oreSorv = 0;
$oreProg = 0;
$oreMat = 0;
$orePom = 0;

$TotCurrEff = 0;
$TotExtraCurrEff = 0;
$TotSorvEff = 0;
$TotProgEff = 0;

// --- RACCOLTA ID PROGETTI FILTRATI ---
if (!empty($classi)) {
    foreach ($classi as $classe) {
        preg_match('/^(\d+)/', $classe, $matches);
        $anno = isset($matches[1]) ? (int)$matches[1] : 0;
        $sezione = substr($classe, strlen($matches[1])); // il resto della stringa

        $stmt = $conn->prepare("SELECT DISTINCT p.id
                                FROM progetti p
                                JOIN progetti_classi pc ON p.id = pc.fk_progetto
                                JOIN classi c ON pc.fk_classe = c.id
                                WHERE c.anno_classe = :anno AND c.sezione = :sezione");
        $stmt->bindParam(':anno', $anno, PDO::PARAM_INT);
        $stmt->bindParam(':sezione', $sezione, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($result as $idProgetto) {
            $progetti_associativi[$idProgetto] = $idProgetto;
        }
    }
} else {
    $stmt = $conn->prepare("SELECT id FROM progetti");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($result as $idProgetto) {
        $progetti_associativi[$idProgetto] = $idProgetto;
    }
}

$ArrPrj = array_values($progetti_associativi);

// Tabella Progetti
$table = "<table class='tbVisua' id='firstTb'>
            <tr>
                <th style='display:none;'></th>
                <th>Titolo Progetto</th>
                <th>Dipartimento</th>
                <th>Docente Referente</th>
            </tr>";

// Tabella Progetti Ore
$tableProgettiOre = "<table class='tbVisua' id='tbProgettiOre'>
                    <tr>
                        <th>Titolo Progetto</th>
                        <th>Ore Progettazione</th>
                        <th>Ore Curricolari</th>
                        <th>Ore Extracurricolari</th>
                        <th>Ore Sorveglianza</th>
                    </tr>";

// Tabella Ore Effettive interne
$tableOreEffettive = "<table class='tbVisua' id='tbOreEffettive'>
<tr>
<th>Titolo Progetto</th>
<th>Ore Curricolari Effettive</th>
<th>Ore Extra-Curricolari Effettive</th>
<th>Ore Sorveglianza Effettive</th>
<th>Ore Progettazione Effettive</th>
</tr>";

// Tabella Risorse Esterne PREVISTE
$tableOreEst = "<table class='tbVisua' id='tbOreEst'>
<tr>
    <th>Titolo Progetto</th>
    <th>Totale Ore Docenza Esterna</th>
    <th>Totale Costo Previsto (€)</th>
</tr>";

// Tabella Risorse Esterne EFFETTIVE
$tableOreEstEff = "<table class='tbVisua' id='tbOreEstEffettive'>
<tr>
    <th>Titolo Progetto</th>
    <th>Ore Docenza Effettive</th>
    <th>Costo Effettivo (€)</th>
</tr>";

foreach ($ArrPrj as $progetto) {
    // Dettagli Progetto
    $stm = $conn->prepare("SELECT id, titolo, fk_dipartimento, fk_docenteReferente FROM progetti WHERE id = :id");
    $stm->bindParam(':id', $progetto, PDO::PARAM_INT);
    $stm->execute();
    $pr = $stm->fetchAll();

    foreach($pr as $row) {
        $varDip = '';
        if($row["fk_dipartimento"]){
            $dip = $conn->prepare("SELECT nome FROM dipartimento WHERE id = :id");
            $dip->bindParam(':id', $row["fk_dipartimento"], PDO::PARAM_INT);
            $dip->execute();
            $resultDip = $dip->fetch();
            $varDip = $resultDip["nome"];
        }
        $varRef = '';
        if($row["fk_docenteReferente"]){
            $ref = $conn->prepare("SELECT nominativo FROM docenteReferente WHERE id = :id");
            $ref->bindParam(':id', $row["fk_docenteReferente"], PDO::PARAM_INT);
            $ref->execute();
            $resultRef = $ref->fetch();
            $varRef = $resultRef["nominativo"];
        }
        $table .= "<tr>
                    <td style='display:none;'>" . $row["id"] . "</td>
                    <td>" . $row["titolo"] . "</td>
                    <td>" . $varDip . "</td>
                    <td>" . $varRef . "</td>
                   </tr>";
    }

    // Ore Progetto (INTERNE)
    $stmProgetti = $conn->prepare("
    SELECT 
      p.id AS ID_Progetto, 
      p.titolo AS Titolo_Progetto,
      COALESCE(SUM(ri.oreProgettazione), 0) AS TotaleOreProgettazione,
      COALESCE(SUM(ri.oreCurricolari), 0) AS TotaleOreCurricolari,
      COALESCE(SUM(ri.oreExtraCurricolari), 0) AS TotaleOreExtraCurricolari,
      COALESCE(SUM(ri.oreSorveglianza), 0) AS TotaleOreSorveglianza
    FROM 
      progetti p
    LEFT JOIN progetti_risorse pr ON p.id = pr.fk_progetti
    LEFT JOIN risorseInterne ri ON pr.fk_risorsaInterna = ri.id
    WHERE p.id = :id
    GROUP BY p.id, p.titolo
    ");
    $stmProgetti->bindParam(':id', $progetto, PDO::PARAM_INT);
    $stmProgetti->execute();
    $progetti_ore = $stmProgetti->fetchAll();

    foreach($progetti_ore as $progetto_ore) {
        $tableProgettiOre .= "<tr>
                    <td>" . $progetto_ore["Titolo_Progetto"] . "</td>
                    <td>" . $progetto_ore["TotaleOreProgettazione"] . "</td>
                    <td>" . $progetto_ore["TotaleOreCurricolari"] . "</td>
                    <td>" . $progetto_ore["TotaleOreExtraCurricolari"] . "</td>
                    <td>" . $progetto_ore["TotaleOreSorveglianza"] . "</td>
                </tr>";
    }

    // Calcolo ore totali interne
    $stm2 = $conn->prepare("SELECT DISTINCT SUM(r.oreCurricolari) AS oreCurricolari, 
                            SUM(r.oreExtraCurricolari) AS oreExtraCurricolari, 
                            SUM(r.oreSorveglianza) AS oreSorveglianza, 
                            SUM(r.oreProgettazione) AS oreProgettazione
                            FROM risorseInterne r, progetti_risorse pr
                            WHERE pr.fk_progetti = :project
                            AND pr.fk_risorsaInterna = r.id");
    $stm2->bindParam(':project', $progetto, PDO::PARAM_INT);
    $stm2->execute();
    $oreTot = $stm2->fetchAll();    
    
    foreach($oreTot as $ore) {
        $oreCurr += $ore['oreCurricolari'];
        $oreExtraCurr += $ore['oreExtraCurricolari'];
        $oreSorv += $ore['oreSorveglianza'];
        $oreProg += $ore['oreProgettazione'];
    }    

    // Ore mattino/pomeriggio
    $stm3 = $conn->prepare("SELECT *
                            FROM progetti_classi
                            WHERE fk_progetto = :project
                            LIMIT 1");
    $stm3->bindParam(':project', $progetto, PDO::PARAM_INT);
    $stm3->execute();
    $oreMP = $stm3->fetchAll();
    
    foreach($oreMP as $omp) {
        $oreMat += $omp["ore_mattina"];
        $orePom += $omp["ore_pomeriggio"];
    }

    // Ore Effettive (INTERNE)
    $stmEff = $conn->prepare("SELECT 
        COALESCE(SUM(ri.OreCurricolariEffettive),0) AS TotCurrEff,
        COALESCE(SUM(ri.OreExtraCurricolariEffettive),0) AS TotExtraCurrEff,
        COALESCE(SUM(ri.OreSorveglianzaEffettive),0) AS TotSorvEff,
        COALESCE(SUM(ri.OreProgettazioneEffettive),0) AS TotProgEff
        FROM risorseInterne ri
        JOIN progetti_risorse pr ON ri.id = pr.fk_risorsaInterna
        WHERE pr.fk_progetti = :id");
    $stmEff->bindParam(':id', $progetto, PDO::PARAM_INT);
    $stmEff->execute();
    $eff = $stmEff->fetch();

    $tableOreEffettive .= "<tr>
    <td>".$row["titolo"]."</td>
    <td>".$eff["TotCurrEff"]."</td>
    <td>".$eff["TotExtraCurrEff"]."</td>
    <td>".$eff["TotSorvEff"]."</td>
    <td>".$eff["TotProgEff"]."</td>
    </tr>";

    $TotCurrEff += $eff["TotCurrEff"];
    $TotExtraCurrEff += $eff["TotExtraCurrEff"];
    $TotSorvEff += $eff["TotSorvEff"];
    $TotProgEff += $eff["TotProgEff"];

    // --- RISORSE ESTERNE: PREVISTE
    $stmEst = $conn->prepare("
        SELECT 
            p.id AS ID_Progetto, 
            p.titolo AS Titolo_Progetto,
            COALESCE(SUM(re.oreDocenza), 0) AS TotOreDocenza,
            COALESCE(SUM(re.costoPrevisto), 0) AS TotCostoPrevisto
        FROM 
            progetti p
        LEFT JOIN progetti_risorse pr ON p.id = pr.fk_progetti
        LEFT JOIN risorseEsterne re ON pr.fk_risorsaEsterna = re.id
        WHERE p.id = :id
        GROUP BY p.id, p.titolo
    ");
    $stmEst->bindParam(':id', $progetto, PDO::PARAM_INT);
    $stmEst->execute();
    $esterni = $stmEst->fetchAll();

    foreach($esterni as $est) {
        $tableOreEst .= "<tr>
            <td>" . htmlspecialchars($est["Titolo_Progetto"], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . $est["TotOreDocenza"] . "</td>
            <td>" . number_format($est["TotCostoPrevisto"], 2, ',', '.') . "</td>
        </tr>";
    }

    // --- RISORSE ESTERNE: EFFETTIVE
    $stmEstEff = $conn->prepare("
        SELECT 
            p.id AS ID_Progetto,
            p.titolo AS Titolo_Progetto,
            COALESCE(SUM(re.oreDocenzaEffettive), 0) AS TotOreDocEff,
            COALESCE(SUM(re.costoEffettivo), 0) AS TotCostoEff
        FROM 
            progetti p
        LEFT JOIN progetti_risorse pr ON p.id = pr.fk_progetti
        LEFT JOIN risorseEsterne re ON pr.fk_risorsaEsterna = re.id
        WHERE p.id = :id
        GROUP BY p.id, p.titolo
    ");
    $stmEstEff->bindParam(':id', $progetto, PDO::PARAM_INT);
    $stmEstEff->execute();
    $oreEstEffettive = $stmEstEff->fetchAll();

    foreach($oreEstEffettive as $estEff) {
        $tableOreEstEff .= "<tr>
            <td>" . htmlspecialchars($estEff["Titolo_Progetto"], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . $estEff["TotOreDocEff"] . "</td>
            <td>" . number_format($estEff["TotCostoEff"], 2, ',', '.') . "</td>
        </tr>";
    }
}

// Chiudi le tabelle
$table .= "</table>";
$tableProgettiOre .= "</table>";
$tableOreEffettive .= "</table>";
$tableOreEst .= "</table>";
$tableOreEstEff .= "</table>";

// Tabella Ore Totali interne
$tableOre = "<table class='tbVisua' id='tbOre'>
                <tr>
                    <th>Ore totali Progettazione</th>
                    <th>Ore totali Curricolari</th>
                    <th>Ore totali Extracurricolari</th>
                    <th>Ore totali Sorveglianza</th>
                </tr>
                <tr>
                    <td>".$oreProg."</td>
                    <td>".$oreCurr."</td>
                    <td>".$oreExtraCurr."</td>
                    <td>".$oreSorv."</td>
                </tr>
            </table>";

// Tabella Mattino/Pomeriggio
$tableMatPom = "<table class='tbVisua' id='tbMatPom'>
                <tr>
                    <th>Ore totali Mattino</th>
                    <th>Ore totali Pomeriggio</th>
                </tr>
                <tr>
                    <td>".$oreMat."</td>
                    <td>".$orePom."</td>
                </tr>
            </table>";

// Tabella Ore Effettive Totali interne
$tableOreEffTotali = "<table class='tbVisua' id='tbOreEffTotali'>
<tr>
<th>Totale Ore Curricolari Effettive</th>
<th>Totale Ore Extra-Curricolari Effettive</th>
<th>Totale Ore Sorveglianza Effettive</th>
<th>Totale Ore Progettazione Effettive</th>
</tr>
<tr>
<td>$TotCurrEff</td>
<td>$TotExtraCurrEff</td>
<td>$TotSorvEff</td>
<td>$TotProgEff</td>
</tr></table>";

// --- TOTALE GENERALE RISORSE ESTERNE (previsto) ---
$tableOreEstTot = "<table class='tbVisua' id='tbOreEstTotali'>
    <tr>
        <th>Totale Ore Docenza Esterna</th>
        <th>Totale Costo Previsto (€)</th>
    </tr>
    <tr>";

if(count($ArrPrj)>0){
    $placeholders = implode(',', array_fill(0, count($ArrPrj), '?'));
    $stmEstTot = $conn->prepare("
        SELECT 
            COALESCE(SUM(re.oreDocenza), 0) AS TotOreDocenza,
            COALESCE(SUM(re.costoPrevisto), 0) AS TotCostoPrevisto
        FROM risorseEsterne re
        JOIN progetti_risorse pr ON re.id = pr.fk_risorsaEsterna
        WHERE pr.fk_progetti IN ($placeholders)
    ");
    $stmEstTot->execute($ArrPrj);
    $estTot = $stmEstTot->fetch();
} else {
    $estTot = ["TotOreDocenza"=>0,"TotCostoPrevisto"=>0];
}
$tableOreEstTot .= "<td>".$estTot["TotOreDocenza"]."</td>";
$tableOreEstTot .= "<td>".number_format($estTot["TotCostoPrevisto"], 2, ',', '.')."</td></tr></table>";

// --- TOTALE GENERALE RISORSE ESTERNE (effettivo) ---
$tableOreEstEffTot = "<table class='tbVisua' id='tbOreEstEffTotali'>
    <tr>
        <th>Totale Ore Docenza Effettive</th>
        <th>Totale Costo Effettivo (€)</th>
    </tr>
    <tr>";

if(count($ArrPrj)>0){
    $placeholders = implode(',', array_fill(0, count($ArrPrj), '?'));
    $stmTotEstEff = $conn->prepare("
        SELECT 
            COALESCE(SUM(re.oreDocenzaEffettive), 0) AS TotOreDocEff,
            COALESCE(SUM(re.costoEffettivo), 0) AS TotCostoEff
        FROM risorseEsterne re
        JOIN progetti_risorse pr ON re.id = pr.fk_risorsaEsterna
        WHERE pr.fk_progetti IN ($placeholders)
    ");
    $stmTotEstEff->execute($ArrPrj);
    $rowEstEff = $stmTotEstEff->fetch();
} else {
    $rowEstEff = ["TotOreDocEff"=>0,"TotCostoEff"=>0];
}
$tableOreEstEffTot .= "<td>".$rowEstEff["TotOreDocEff"]."</td>";
$tableOreEstEffTot .= "<td>".number_format($rowEstEff["TotCostoEff"], 2, ',', '.')."</td></tr></table>";

echo json_encode(array(
    'success' => true, 
    'risposta' => $table, 
    'risposta_ore_progetti' => $tableProgettiOre,
    'risposta_ore_totali' => $tableOre,
    'risposta_ore_effettive' => $tableOreEffettive,
    'risposta_ore_eff_totali' => $tableOreEffTotali,
    'risposta_mat_pom' => $tableMatPom,
    'risposta_progetti' => $ArrPrj,
    'risposta_ore_est' => $tableOreEst,
    'risposta_ore_est_tot' => $tableOreEstTot,
    'risposta_ore_est_eff' => $tableOreEstEff,
    'risposta_ore_est_eff_tot' => $tableOreEstEffTot
));
?>
