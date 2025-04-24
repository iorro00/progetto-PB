<?php
require_once('fpdf.php');
require_once('db.php');

class PDF extends FPDF {
    private $firstPage = true;
    private $alternateRow = false;

    function Header() {
        if ($this->firstPage) {
            $this->SetFont('Arial', 'B', 22);
            $this->SetTextColor(0, 0, 128);
            $this->Cell(0, 10, "Progetti IIS BLAISE PASCAL", 0, 1, 'C');
            $this->Ln(5);
            $this->firstPage = false;
        }
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }

    // Stampa una riga “label: contenuto” con sfondo alternato
    function AddSectionRow($label, $content) {
        $this->alternateRow = !$this->alternateRow;
        $fill = $this->alternateRow ? [230,230,230] : [255,255,255];
        $this->SetFillColor($fill[0], $fill[1], $fill[2]);
        if ($this->GetY() + 20 > $this->PageBreakTrigger) {
            $this->AddPage();
        }
        $this->SetFont('Arial','B',12);
        $this->MultiCell(0, 10, utf8_decode($label . ":"), 0, 'L', true);
        $this->SetFont('Arial','',12);
        $this->MultiCell(0, 10, utf8_decode($content), 0, 'L', $this->alternateRow);
        $this->Ln(2);
    }

    // Helper per stampare titolo … dot leaders … pagina
    private function AddIndexEntry($title, $page) {
        $this->SetFont('Arial','',12);
        $avail = $this->GetPageWidth() - $this->lMargin - $this->rMargin;
        $wTitle = $this->GetStringWidth($title);
        $wPage  = $this->GetStringWidth($page);
        $dotW   = $this->GetStringWidth('.');
        $nDots  = max(1, floor(($avail - $wTitle - $wPage) / $dotW));
        $dots   = str_repeat('.', $nDots);
        $this->Cell($avail - $wPage, 8, utf8_decode($title . $dots), 0, 0, 'L');
        $this->Cell($wPage, 8, $page, 0, 1, 'R');
    }

    // Costruisce l’indice su pagina bianca
    function GenerateIndex($projectsInfo) {
        $this->AddPage();
        $this->SetFont('Arial','B',16);
        $this->SetTextColor(0,0,0);
        $this->Cell(0,15, utf8_decode("Indice dei Progetti"), 0,1,'C');
        $this->Ln(5);
        foreach ($projectsInfo as $p) {
            $this->AddIndexEntry($p['titolo'], $p['pagina']);
        }
        $this->Ln(4);
        $this->SetDrawColor(200,200,200);
        $this->SetLineWidth(0.2);
        $this->Line($this->lMargin, $this->GetY(),
                    $this->GetPageWidth() - $this->rMargin, $this->GetY());
        $this->Ln(2);
    }
}

// Funzione riutilizzabile per stampare i dettagli di un progetto
function renderProjectDetails($pdf, $projectId, $conn) {
    // Carica dettagli principali e docente
    $stm = $conn->prepare("
        SELECT * 
        FROM progetti
        INNER JOIN docenteReferente ON docenteReferente.id = progetti.fk_docenteReferente 
        WHERE progetti.id = :id
        GROUP BY progetti.id
    ");
    $stm->bindParam(':id', $projectId, PDO::PARAM_INT);
    $stm->execute();
    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) return;

    foreach ($rows as $row) {
        // Titolo con sfondo
        $pdf->SetFillColor(100,100,255);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10, "Progetto: " . utf8_decode($row['titolo']), 0,1,'C',true);
        $pdf->Ln(4);

        // Docente
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,8, "Docente referente: " . utf8_decode($row['nominativo']), 0,1,'L');
        $pdf->Ln(3);

        // Classi coinvolte
        $stmt2 = $conn->prepare("
            SELECT c.anno_classe, c.sezione 
            FROM progetti_classi pc
            JOIN classi c ON pc.fk_classe = c.id
            WHERE pc.fk_progetto = :id
        ");
        $stmt2->bindParam(':id', $projectId, PDO::PARAM_INT);
        $stmt2->execute();
        $cls = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $classi = "Classi coinvolte: ";
        foreach ($cls as $c) {
            $classi .= $c['anno_classe'] . $c['sezione'] . ", ";
        }
        $classi = rtrim($classi, ', ');
        $pdf->Cell(0,8, utf8_decode($classi), 0,1,'L');
        $pdf->Ln(3);

        // Altri campi
        $fields = [
            "Il progetto è strutturale?" => $row["strutturale"],
            "Parte dei percorsi di PCTO?" => $row["PCTO"],
            "Analisi del contesto e bisogni" => $row["analisi_contesto"],
            "Obiettivi attesi" => $row["obbiettivi_attesi"],
            "Attività previste" => $row["attivita_previste"],
            "Metodologia e strumenti" => $row["metodologia_e_strumenti"],
            "Luoghi di svolgimento" => $row["luoghi_svolgimento"],
            "Tempi di svolgimento" => $row["tempi_svolgimento"],
            "Ore mattina" => $row["ore_mattina"],
            "Ore pomeriggio" => $row["ore_pomeriggio"],
            "Verifica in itinere/finale" => $row["verifica_itinere_e_finale"]
        ];
        foreach ($fields as $k => $v) {
            $pdf->AddSectionRow($k, $v);
        }

        // Separatore
        $pdf->SetDrawColor(100,100,255);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(5);
    }
}

// ——— Main ———

// Riceve array di ID da POST
$idProjects = $_POST['idProjects'];

// 1) Preleva titoli
$projectsInfo = [];
foreach ($idProjects as $pid) {
    $q = $conn->prepare("SELECT id, titolo FROM progetti WHERE id = :id");
    $q->bindParam(':id', $pid, PDO::PARAM_INT);
    $q->execute();
    if ($r = $q->fetch(PDO::FETCH_ASSOC)) {
        $projectsInfo[] = $r;
    }
}

// 2) Collector: costruisce le pagine dettagli e salva il numero di inizio
$collector      = new PDF();
$projectIndex   = [];
foreach ($projectsInfo as $p) {
    // la pagina vera in cui comparirà (indice occupa 1 pagina)
    $start = $collector->PageNo() + 2;
    $projectIndex[] = ['titolo' => $p['titolo'], 'pagina' => $start];

    // pagina dei dettagli
    $collector->AddPage();
    renderProjectDetails($collector, $p['id'], $conn);
}

// 3) Final: genera indice + dettagli
$final = new PDF();
$final->GenerateIndex($projectIndex);
foreach ($projectsInfo as $p) {
    $final->AddPage();
    renderProjectDetails($final, $p['id'], $conn);
}

// 4) Esporta
$file = tempnam(sys_get_temp_dir(), 'pdf');
$final->Output('F', $file);
echo json_encode([
    'success' => true,
    'pdf_url' => 'scarica_pdf.php?file=' . basename($file)
]);
