<?php
require_once('fpdf.php');
require_once('db.php');

// Recupera i dati inviati tramite POST
$idProjects = $_POST['idProjects'];

// Genera il PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, "Progetti IIS BLAISE PASCAL");
$pdf->Ln(); // Line break
$pdf->Ln(); // Line break
$pdf->SetFont('Arial', '', 12);

foreach ($idProjects as $project) {
    // Esegui la query per ottenere i dettagli del progetto
    $sql = "SELECT * FROM progetti
            INNER JOIN docenteReferente ON docenteReferente.id = progetti.fk_docenteReferente
            WHERE progetti.id = $project";
    
    $stm = $conn->prepare($sql);
    $stm->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC); // Fetch results as associative array
    
    if ($result) {
        foreach ($result as $row) {
        	$pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(10, 10, "Progetto: " . $row["titolo"]);
            $pdf->Ln(); // Line break
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(10, 10, "Docente referente: " . $row["nominativo"]);
            $pdf->Ln(); // Line break
            $classi="Classi: ";
            $stmt = $conn->prepare("SELECT c.anno_classe, c.sezione 
                        FROM progetti_classi pc
                        JOIN classi c ON pc.fk_classe = c.id
                        WHERE pc.fk_progetto = :id");

            // Associa il parametro alla query
            $stmt->bindParam(':id', $project, PDO::PARAM_INT);

            // Esegui la query
            $stmt->execute();

            // Ottieni tutti i risultati
            $sezioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($sezioni as $cls)
            {
            	$classi.= $cls["anno_classe"] . $cls["sezione"] . ", ";
            }
            $classi2 = substr($classi, 0, -2);
            $pdf->Cell(10, 10, $classi2);
            $pdf->Ln(); // Line break
            $pdf->Cell(10, 10, "Il progetto e' strutturale? " . $row["strutturale"]);
            $pdf->Ln(); // Line break
            $pdf->Cell(10, 10, "Il progetto fa parte dei percorsi di PCTO?: " . $row["PCTO"]);
            $pdf->Ln(); // Line break
            $pdf->Cell(10, 10, "Breve analisi del contesto in cui si intende operare e dei bisogni rilevati: " . $row["analisi_contesto"]);
            $pdf->Ln(); // Line break
            $pdf->Cell(10, 10, "Obbiettivi attesi: " . $row["obbiettivi_attesi"]);
            $pdf->Ln(); // Line break
            $pdf->Cell(10, 10, "Attivita' previste: " . $row["attivita_previste"]);
            $pdf->Ln(); // Line break
            $pdf->Cell(10, 10, "Metodologia e strumenti: " . $row["metodologia_e_strumenti"]);
            $pdf->Ln(); // Line break
            $pdf->Cell(10, 10, "Luoghi di svolgimento: " . $row["luoghi_svolgimento"]);
            $pdf->Ln(); // Line break
            $pdf->Cell(10, 10, "Tempi di svolgimento: " . $row["tempi_svolgimento"]);
            $pdf->Ln(); // Line break
            $pdf->Cell(10, 10, "Modalita' di verifica in itinere e finale: " . $row["verifica_itinere_e_finale"]);
            $pdf->Ln(); // Line break
            $pdf->Ln(); // Additional line break for spacing between projects
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
