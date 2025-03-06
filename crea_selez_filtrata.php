<?php
require_once("db.php");
$classi = $_POST["classi"];
$progetti = [];
$oreCurr = 0;
$oreExtraCurr = 0;
$oreSorv = 0;
$oreProg = 0;
$oreMat = 0;
$orePom = 0;

if (!empty($classi)) {
    foreach ($classi as $classe) {
        $anno = (int) $classe[0];
        $sezione = substr($classe, 1);

        $stmt = $conn->prepare("SELECT DISTINCT p.id
                                FROM progetti p
                                JOIN progetti_classi pc ON p.id = pc.fk_progetto
                                JOIN classi c ON pc.fk_classe = c.id
                                WHERE c.anno_classe = :anno AND c.sezione = :sezione");
        $stmt->bindParam(':anno', $anno, PDO::PARAM_INT);
        $stmt->bindParam(':sezione', $sezione, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $progetti = array_merge($progetti, $result);
    }
} else {
    $stmt = $conn->prepare("SELECT id FROM progetti");
    $stmt->execute();
    $progetti = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$ArrPrj = array_unique($progetti);
$ArrPrj = array_values($ArrPrj);

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

    // Ore Progetto
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
    GROUP BY 
    p.id, p.titolo
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

    // Calcolo ore totali
    $stm2 = $conn->prepare("SELECT DISTINCT SUM(r.oreCurricolari)AS oreCurricolari, 
                            SUM(r.oreExtraCurricolari)AS oreExtraCurricolari, 
                            SUM(r.oreSorveglianza)AS oreSorveglianza, 
                            SUM(r.oreProgettazione)AS oreProgettazione
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
}

$table .= "</table>";
$tableProgettiOre .= "</table>";

// Tabella Ore Totali
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

echo json_encode(array(
    'success' => true, 
    'risposta' => $table, 
    'risposta2' => $tableOre, 
    'risposta3' => $tableMatPom, 
    'risposta4' => $progetti,
    'risposta_ore_progetti' => $tableProgettiOre
));
?>