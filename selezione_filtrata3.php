<?php
// selezione_filtrata3.php
// Restituisce le righe <tr> filtrate per la tabella “ATTIVITÀ”

header('Content-Type: application/json');
require_once 'db.php';

// 1) Recupera le classi selezionate dal filtro
$classi = $_POST['classi'] ?? [];

// 2) Costruisci un array associativo di ID progetto unici
$progettiAssociativi = [];

if (!empty($classi)) {
    foreach ($classi as $classe) {
        // Estrai anno (prime cifre) e sezione (resto)
        preg_match('/^(\d+)/', $classe, $m);
        $anno = isset($m[1]) ? (int)$m[1] : 0;
        $sezione = substr($classe, strlen($m[1] ?? '' ));

        $stmt = $conn->prepare("
            SELECT DISTINCT pc.fk_progetto
            FROM progetti_classi pc
            JOIN classi c ON pc.fk_classe = c.id
            WHERE c.anno_classe = :anno
              AND c.sezione = :sezione
        ");
        $stmt->execute([':anno' => $anno, ':sezione' => $sezione]);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($ids as $idPrj) {
            $progettiAssociativi[$idPrj] = $idPrj;
        }
    }
} else {
    // Se non ci sono filtri, prendi tutti i progetti
    $all = $conn->query("SELECT id FROM progetti")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($all as $idPrj) {
        $progettiAssociativi[$idPrj] = $idPrj;
    }
}

// 3) Se non ci sono progetti, restituisci un errore
if (empty($progettiAssociativi)) {
    echo json_encode([
        'success' => false,
        'message' => 'Nessun progetto trovato per i filtri selezionati'
    ]);
    exit;
}

// 4) Genera l’HTML delle <tr> filtrate
$html = '';
foreach (array_keys($progettiAssociativi) as $projectId) {
    // 4a) Dati base del progetto
    $p = $conn->prepare("
        SELECT titolo, tempi_svolgimento
        FROM progetti
        WHERE id = :id
    ");
    $p->execute([':id' => $projectId]);
    $proj = $p->fetch(PDO::FETCH_ASSOC);
    $titolo = htmlspecialchars($proj['titolo']);
    $tempi  = strtolower($proj['tempi_svolgimento']);

    // 4b) Recupera destinatarî (origineProgetto + ore mattina/pomeriggio)
    $d = $conn->prepare("
        SELECT pr.origineProgetto, pc.ore_mattina, pc.ore_pomeriggio
        FROM progetti_classi pc
        JOIN progetti pr ON pc.fk_progetto = pr.id
        WHERE pc.fk_progetto = :id
    ");
    $d->execute([':id' => $projectId]);
    $dest = $d->fetchAll(PDO::FETCH_ASSOC);

    // 4c) Calcola mattino/pomeriggio
    $hasMattina   = false;
    $hasPomeriggio= false;
    $tipoDest     = $dest[0]['origineProgetto'] ?? '';
    foreach ($dest as $row) {
        if ($row['ore_mattina']   > 0) $hasMattina    = true;
        if ($row['ore_pomeriggio']> 0) $hasPomeriggio = true;
    }
    if ($hasMattina && $hasPomeriggio) {
        $periodo = 'mattino e pomeriggio';
    } elseif ($hasPomeriggio) {
        $periodo = 'pomeriggio';
    } else {
        $periodo = 'mattino';
    }

    // 4d) Determina i mesi di svolgimento
    $mesi = [
        'Set'=>false,'Ott'=>false,'Nov'=>false,'Dic'=>false,
        'Gen'=>false,'Feb'=>false,'Mar'=>false,'Apr'=>false,
        'Mag'=>false,'Giu'=>false,'Lug'=>false,'Ago'=>false,
    ];
    foreach ($mesi as $abbr => $_) {
        switch ($abbr) {
            case 'Set': if (strpos($tempi,'settembre') !== false) $mesi[$abbr] = true; break;
            case 'Ott': if (strpos($tempi,'ottobre')   !== false) $mesi[$abbr] = true; break;
            case 'Nov': if (strpos($tempi,'novembre')  !== false) $mesi[$abbr] = true; break;
            case 'Dic': if (strpos($tempi,'dicembre')  !== false) $mesi[$abbr] = true; break;
            case 'Gen': if (strpos($tempi,'gennaio')   !== false) $mesi[$abbr] = true; break;
            case 'Feb': if (strpos($tempi,'febbraio')  !== false) $mesi[$abbr] = true; break;
            case 'Mar': if (strpos($tempi,'marzo')     !== false) $mesi[$abbr] = true; break;
            case 'Apr': if (strpos($tempi,'aprile')    !== false) $mesi[$abbr] = true; break;
            case 'Mag': if (strpos($tempi,'maggio')    !== false) $mesi[$abbr] = true; break;
            case 'Giu': if (strpos($tempi,'giugno')    !== false) $mesi[$abbr] = true; break;
            case 'Lug': if (strpos($tempi,'luglio')    !== false) $mesi[$abbr] = true; break;
            case 'Ago': if (strpos($tempi,'agosto')    !== false) $mesi[$abbr] = true; break;
        }
    }

    // 4e) Costruisci la riga HTML
    $html .= "<tr>
      <td><select class='form-select' disabled><option>$periodo</option></select></td>
      <td><select class='form-select' disabled><option>" . htmlspecialchars($tipoDest) . "</option></select></td>
      <td>$titolo</td>";
    foreach ($mesi as $flag) {
        $checked = $flag ? 'checked' : '';
        $html .= "<td><input type='checkbox' class='form-check-input' $checked disabled></td>";
    }
    $html .= "</tr>\n";
}

// 5) Restituisci il JSON
echo json_encode([
    'success' => true,
    'html'    => $html
]);
