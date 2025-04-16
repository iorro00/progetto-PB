<?php
require_once('fpdf.php');
require_once('db.php');

class PDF extends FPDF {
    private $firstPage = true;
    private $alternateRow = false;
    private $bookmarks = array(); // Array per memorizzare i segnalibri
    
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
    
    // Aggiunge un progetto all'indice
    function AddBookmark($title, $page) {
        $this->bookmarks[] = array('title' => $title, 'page' => $page);
    }
    
    // Genera la pagina dell'indice
    function CreateIndex() {
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(0, 0, 128);
        $this->Cell(0, 10, "Indice dei Progetti", 0, 1, 'C');
        $this->Ln(10);
        
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        
        foreach ($this->bookmarks as $bookmark) {
            $title = $bookmark['title'];
            $page = $bookmark['page'];
            
            // Calcola larghezza disponibile
            $maxWidth = $this->GetPageWidth() - $this->lMargin - $this->rMargin;
            $titleWidth = $this->GetStringWidth($title);
            $pageWidth = $this->GetStringWidth($page);
            
            // Spazio per i puntini
            $dotsWidth = $maxWidth - $titleWidth - $pageWidth - 5;
            
            // Aggiungi titolo
            $this->Cell($titleWidth + 2, 8, utf8_decode($title), 0, 0);
            
            // Aggiungi puntini
            $dots = str_repeat('.', floor($dotsWidth / 2));
            $this->Cell($dotsWidth, 8, $dots, 0, 0);
            
            // Aggiungi numero di pagina con link
            $this->SetTextColor(0, 0, 255); // Blu per il link
            $this->Cell($pageWidth + 2, 8, $page, 0, 1, 'R', false, $page);
            $this->SetTextColor(0, 0, 0); // Ripristina colore
            
            $this->Ln(2);
        }
    }
}

// Verifica se i dati sono stati inviati
if (!isset($_POST['idProjects']) || !is_array($_POST['idProjects']) || empty($_POST['idProjects'])) {
    // Log dell'errore
    error_log("Errore: nessun progetto selezionato o formato non valido");
    echo json_encode(array('success' => false, 'message' => 'Nessun progetto valido selezionato'));
    exit;
}

// Recupera i dati inviati tramite POST
$idProjects = $_POST['idProjects'];

try {
    // Genera il PDF
    $pdf = new PDF();
    
    // Crea la pagina dell'indice
    $pdf->AddPage();
    $indexPage = $pdf->PageNo();
    
    // Array per i dati dei progetti
    $projectsData = array();
    
    // Recupera tutti i dati dei progetti
    foreach ($idProjects as $project) {
        $sql = "SELECT * 
                FROM progetti
                INNER JOIN docenteReferente ON docenteReferente.id = progetti.fk_docenteReferente 
                WHERE progetti.id = :project";
        
        $stm = $conn->prepare($sql);
        $stm->bindParam(':project', $project, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Recupera le classi per questo progetto
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
            
            $result['classi'] = $classi;
            $result['sezioni'] = $sezioni;
            $projectsData[] = $result;
        }
    }
    
    // Generiamo tutte le pagine dei progetti
    foreach ($projectsData as $row) {
        // Nuova pagina per ogni progetto
        $pdf->AddPage();
        $projectPage = $pdf->PageNo();
        
        // Aggiungi il segnalibro per questo progetto
        $pdf->AddBookmark($row["titolo"], $projectPage);
        
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
    
        // Classi coinvolte
        $pdf->Cell(0, 8, utf8_decode($row["classi"]), 0, 1, 'L');
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
            "Ore alla mattina" => isset($row["ore_mattina"]) ? $row["ore_mattina"] : "",
            "Ore alla pomeriggio" => isset($row["ore_pomeriggio"]) ? $row["ore_pomeriggio"] : "",
            "Modalita' di verifica in itinere e finale" => $row["verifica_itinere_e_finale"]
        ];
    
        // Mostra ogni campo con stile alternato
        foreach ($fields as $key => $value) {
            if ($value !== null && $value !== "") {
                $pdf->AddSectionRow($key, $value);
            }
        }
    
        // Linea di separazione tra i progetti
        $pdf->SetDrawColor(100, 100, 255);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(5);
    }
    
    // Torna alla prima pagina e crea l'indice
    $pdf->setPage($indexPage);
    $pdf->CreateIndex();
    
    // Salva il PDF in un file temporaneo
    $file = tempnam(sys_get_temp_dir(), 'pdf');
    $pdf->Output('F', $file);
    
    // Risponde con l'URL del file PDF
    echo json_encode(array('success' => true, 'pdf_url' => 'scarica_pdf.php?file=' . basename($file)));
    
} catch (Exception $e) {
    // Log dell'errore
    error_log("Errore nella generazione del PDF: " . $e->getMessage());
    
    // Restituisci messaggio di errore
    echo json_encode(array('success' => false, 'message' => 'Errore nella generazione del PDF: ' . $e->getMessage()));
}
?>