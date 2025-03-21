<?php
require_once('fpdf.php');
require_once('db.php');

class PDF extends FPDF {
    private $firstPage = true;
    private $alternateRow = false;
    
    function Header() {
        if ($this->firstPage) {
            $this->SetFont('Arial', 'B', 16);
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

    function AddSectionRow($label, $content) {
        // Alterna i colori di sfondo per una migliore leggibilità
        $this->alternateRow = !$this->alternateRow;
        $fillColor = $this->alternateRow ? [230, 230, 230] : [255, 255, 255];
        
        $this->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);
        
        // Etichetta in grassetto
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 0, 0);
        
        // Crea una nuova pagina se lo spazio non è sufficiente
        if ($this->GetY() + 20 > $this->PageBreakTrigger) {
            $this->AddPage();
        }
        
        $this->MultiCell(0, 10, utf8_decode($label . ":"), 0, 'L', true);
        
        // Contenuto in carattere normale
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 10, utf8_decode($content), 0, 'L', $this->alternateRow);
        
        $this->Ln(2);
    }
}

// Recupera i dati inviati tramite POST
$idProjects = $_POST['idProjects'];

// Genera il PDF
$pdf = new PDF();
$pdf->AddPage();

foreach ($idProjects as $project) {
    // Esegui la query per ottenere i dettagli del progetto
    $sql = "SELECT * 
            FROM progetti
            INNER JOIN docenteReferente ON docenteReferente.id = progetti.fk_docenteReferente JOIN progetti_classi prc ON prc.fk_progetto = progetti.id
            WHERE progetti.id = :project
            GROUP BY progetti.id";
    
    $stm = $conn->prepare($sql);
    $stm->bindParam(':project', $project, PDO::PARAM_INT);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        foreach ($result as $row) {
            // Titolo del progetto con sfondo
            $pdf->SetFillColor(100, 100, 255);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, "Progetto: " . utf8_decode($row["titolo"]), 0, 1, 'C', true);
            $pdf->Ln(4);

            // Docente referente
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 8, "Docente referente: " . utf8_decode($row["nominativo"]), 0, 1, 'L');
            $pdf->Ln(3);

            // Recupera le classi
            $stmt = $conn->prepare("SELECT c.anno_classe, c.sezione 
                                    FROM progetti_classi pc
                                    JOIN classi c ON pc.fk_classe = c.id
                                    WHERE pc.fk_progetto = :id");
                                    
            $stmt->bindParam(':id', $project, PDO::PARAM_INT);
            $stmt->execute();
            $sezioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $classi = "Classi coinvolte: ";
            foreach ($sezioni as $cls) {
                $classi .= $cls["anno_classe"] . $cls["sezione"] . ", ";
            }
            $classi = rtrim($classi, ', ');
            $pdf->Cell(0, 8, utf8_decode($classi), 0, 1, 'L');
            $pdf->Ln(3);

            // Campi da mostrare
            $fields = [
                "Il progetto e' strutturale? " => $row["strutturale"],
                "Il progetto fa parte dei percorsi di PCTO?" => $row["PCTO"],
                "Breve analisi del contesto in cui si intende operare e dei bisogni rilevati: " => $row["analisi_contesto"],
                "Obiettivi attesi" => $row["obbiettivi_attesi"],
                "Attività previste" => $row["attivita_previste"],
                "Metodologia e strumenti" => $row["metodologia_e_strumenti"],
                "Luoghi di svolgimento" => $row["luoghi_svolgimento"],
                "Tempi di svolgimento" => $row["tempi_svolgimento"],
                "Ore alla mattina" =>$row["ore_mattina"],
                "Ore alla pomeriggio" =>$row["ore_pomeriggio"],
                "Modalita' di verifica in itinere e finale" => $row["verifica_itinere_e_finale"]
            ];

            // Mostra ogni campo con stile alternato
            foreach ($fields as $key => $value) {
                $pdf->AddSectionRow($key, $value);
            }

            // Linea di separazione tra i progetti
            $pdf->SetDrawColor(100, 100, 255);
            $pdf->SetLineWidth(0.5);
            $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
            $pdf->Ln(5);
        }
    } else {
        error_log("Nessun risultato trovato per l'ID progetto $project");
    }
}

// Salva il PDF in un file temporaneo
$file = tempnam(sys_get_temp_dir(), 'pdf');
$pdf->Output('F', $file);

// Risponde con l'URL del file PDF
echo json_encode(array('success' => true, 'pdf_url' => 'scarica_pdf.php?file=' . basename($file)));
?>