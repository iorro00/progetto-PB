<?php
    session_start();
    // Verifico se l'utente è loggato, altrimenti reindirizzo alla pagina di accesso
    if (!isset($_SESSION['user_email'])) {
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Visualizzazione</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Orbitron">
        <link href='https://fonts.googleapis.com/css?family=Merriweather Sans' rel='stylesheet'>
        <link href="style.css" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            #title {
                font-family: 'Playfair Display', serif;
                font-size: 1.5rem;
                color: #2c3e50;
                font-weight: 700;
                margin-bottom: 30px;
                letter-spacing: -0.5px;
            }
            .top-bar {
                background-color: #00245d;
                color: white;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 80px;
                z-index: 1000;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .top-bar button {
                font-size: 1.8rem; /* Testo del pulsante più grande */
                background: none;
                border: none;
                color: white;
                cursor: pointer;
            }

            .top-bar #butt-filtri {
                font-size: 1.8rem; /* Testo del pulsante più grande */
                background: none;
                border: none;
                color: white;
                margin-left: auto;
            }
            #butt-filtri:hover {
                color: #007bff;
            }
            
            .top-bar p {
                font-size: 1.5rem;
                font-weight: bold;
                margin: 0;
                text-align: center;
                flex-grow: 1;
            }

            .info-circle {
                width: 24px;
                height: 24px;
                position: absolute;
                top: 10px;
                right: 12px;
                cursor: pointer;
                transition: transform 0.3s;
            }
            .info-circle:hover {
                transform: scale(1.1);
            }

            
            :root {
    --primary-color: #0d6efd;
    --secondary-color: #6c757d;
    --bg-color: #ffffff;
    --hover-bg: #f8f9fa;
    --border-color: #dee2e6;
    --shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    --transition-speed: 0.3s;
  }

  /* Modal base */
  #boxFiltri .modal-dialog {
    max-width: 800px;
  }

  .modal-content {
    border-radius: 12px;
    box-shadow: var(--shadow);
    overflow: hidden;
    border: none;
  }

  .modal-header {
    background: #00245d;
    color: var(--bg-color);
    padding: 1rem 1.5rem;
  }

  .modal-header .modal-title {
    margin: 0;
    font-weight: 600;
  }

  .btn-close {
    filter: brightness(0) invert(1);
    margin-top: 1px !important;
  }

  .modal-body {
    background: var(--bg-color);
    padding: 2rem 1.5rem;
  }

  h4 {
    color: #00245d;
    text-align: center;
    margin-bottom: 1.5rem;
  }

  .section-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--secondary-color);
  }

  /* Layout dei checkbox in riga, con wrapping e spaziatura uniforme */
  .checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: space-between;
  }

  .form-check-custom {
    flex: 1 1 calc(25% - 1rem); /* Per "Indirizzo": 4 elementi per riga */
    background-color: var(--hover-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.75rem;
    text-align: center;
    transition: background-color var(--transition-speed) ease, transform var(--transition-speed) ease;
  }

  /* Adattamento per "Annata" (5 elementi per riga) */
  #annata .form-check-custom {
    flex: 1 1 calc(20% - 1rem);
  }

  .form-check-custom:hover {
    background-color: var(--bg-color);
    transform: translateY(-3px);
    cursor: pointer;
  }

  .form-check-input {
    margin: 0;
    accent-color: var(--primary-color);
  }

  .form-check-label {
    display: block;
    margin-top: 0.5rem;
    font-weight: 500;
    color: var(--secondary-color);
  }

  /* Sezione Classi filtrate: centrata e con larghezza limitata */
  #classi-filtrate-container {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
  }
  #classi-selezionate {
    background: var(--hover-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    max-width: 330px; /* Limite di larghezza */
    width: 100%;      /* Per adattarsi su schermi stretti */
    min-height: 50px;
  }

  /* Bottoni Seleziona/Deseleziona */
  .toggle-select {
    cursor: pointer;
    font-weight: 500;
    transition: color var(--transition-speed) ease;
  }

  .toggle-select:hover {
    color: var(--primary-color);
  }

  /* Bottone Invia */
  #submitFiltri {
    border-radius: 8px;
    padding: 0.75rem;
    font-weight: 600;
    transition: background-color var(--transition-speed) ease;
  }

  #submitFiltri:hover {
    background-color: #00245d;
    box-shadow: (0 4px 8px rgba(0, 0, 0, 0.2));
  }  

  .small {
      font-size: 0.875rem;
    }

    .mx-2 {
      margin-left: 0.5rem;
      margin-right: 0.5rem;
    }

    #closePopupp{
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 25px;
    cursor: pointer;
    color: #00245d;
}
#closePopupp:hover{
    color:red;
}

            
        </style>
    </head>

<body>
    <!-- Barra superiore con pulsante indietro, titolo e pulsante filtri -->
    <div id="top-bar" class="top-bar d-flex align-items-center p-3" style="display: none;">
            <button id="back-btn" class="btn btn-light me-3" onclick="torna()">←</button>
            <p id="title" class="m-0 mx-auto text-white">ELENCO PROGETTI IIS BLAISE PASCAL</p>
            <button id="butt-filtri">Filtri</button>
    </div>
    <br><br><br><br>
    
    <!-- Container con icona informativa e tooltip -->
    <div class="container position-relative pt-4">
        <div class="info-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cliccare su un qualsiasi progetto per visualizzarne i dettagli.">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#00245d" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </svg>
        </div>
    </div>

    <?php
        // Inclusione del file di connessione al database
        require_once("db.php");
        
        // PRIMA TABELLA: Elenco progetti con titolo, dipartimento e docente referente
        $table = "<table class='tbVisua' id='firstTb'>
                    <tr>
                        <th style= display:none;></th>
                        <th>Titolo Progetto</th>
                        <th>Dipartimento</th>
                        <th>Docente Referente</th>
                    </tr>";

        // Query per ottenere i progetti ordinati per titolo
        $stm = $conn->prepare("SELECT id, titolo, fk_dipartimento, fk_docenteReferente FROM progetti order by titolo asc");
        $stm->execute();
        $result = $stm->fetchAll();
                
        // Iterazione sui risultati per popolare la tabella
        foreach($result as $row) {
            // Elemento nascosto per memorizzare l'ID del progetto (usato per JavaScript)
            echo "<p get-progetti='".$row['id']."' style=display:none class='progett'></p>";
            
            // Recupero del nome del dipartimento
            $varDip = '';
            if($row["fk_dipartimento"]){
                $dip = $conn->prepare("SELECT nome FROM dipartimento WHERE id = ?");
                $dip->execute([$row["fk_dipartimento"]]);
                $resultDip = $dip->fetch();
                $varDip = $resultDip["nome"];
            }
            
            // Recupero del nome del docente referente
            $varRef = '';
            if($row["fk_docenteReferente"]){
                $ref = $conn->prepare("SELECT nominativo FROM docenteReferente WHERE id = ?");
                $ref->execute([$row["fk_docenteReferente"]]);
                $resultRef = $ref->fetch();
                $varRef = $resultRef["nominativo"];
            }
            
            // Costruzione della riga della tabella
            $table .= "<tr>
                        <td style= display:none; >" . $row["id"] . "</td>
                        <td>" . $row["titolo"] . "</td>
                        <td>" . $varDip . "</td>
                        <td>" . $varRef . "</td>
                        </tr>";
        }
        $table .= "</table>";
        echo $table;

                        //Riepilogo delle ore mattino e pomeriggio
                $tableMatPom = "<table class='tbVisua' id='tbMatPom'>
                                <tr>
                                    <th>Ore totali Mattino</th>
                                    <th>Ore totali Pomeriggio</th>
                                </tr>
                                <tr>"; 
                
                // Query per ottenere i dati delle classi raggruppati per progetto
                $stm3 = $conn->prepare("SELECT *
                                    FROM progetti_classi
                                    GROUP BY fk_progetto");
                $stm3->execute();
                $oreMP = $stm3->fetchAll();
                $oreMattino = 0;
                $orePomeriggio = 0;

                // Calcola il totale delle ore mattino e pomeriggio
                foreach($oreMP as $row)
                {
                    $oreMattino += $row["ore_mattina"];
                    $orePomeriggio += $row["ore_pomeriggio"];
                }
                
                // Popola la tabella con i risultati
                $tableMatPom .= "<td>".$oreMattino."</td>";
                $tableMatPom .= "<td>".$orePomeriggio."</td>";
                $tableMatPom .= "</tr></table>";
                echo $tableMatPom;

        echo "<p class='subTit' style='margin-top:40px;'>Risorse Interne - Valori Previsti</p>";


        // Verifica se l'utente corrente è un docente per mostrare tabelle specifiche
        $stm0 = $conn->prepare("SELECT id FROM docenteReferente WHERE nominativo = ?");
        $stm0->execute([$_SESSION["nominativo"]]);
        $idDoc = $stm0->fetch(PDO::FETCH_ASSOC);

    // SECONDA TABELLA: Dettaglio delle ore per progetto (visibile solo se NON docente)
    if (!$idDoc) {
        $tableProgettiOre = "<table class='tbVisua' id='tbProgettiOre'>
        <tr>
            <th>Titolo Progetto</th>
            <th>Ore Progettazione</th>
            <th>Ore Curricolari</th>
            <th>Ore Extracurricolari</th>
            <th>Ore Sorveglianza</th>
        </tr>";

        // Query per calcolare il totale delle ore per ogni progetto
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
        GROUP BY 
        p.id, p.titolo
        ORDER BY 
        p.titolo
        ");
        $stmProgetti->execute();
        $progetti = $stmProgetti->fetchAll();

        // Popola la tabella con i risultati
        foreach($progetti as $progetto) {
        $tableProgettiOre .= "<tr>
                    <td>" . $progetto["Titolo_Progetto"] . "</td>
                    <td>" . $progetto["TotaleOreProgettazione"] . "</td>
                    <td>" . $progetto["TotaleOreCurricolari"] . "</td>
                    <td>" . $progetto["TotaleOreExtraCurricolari"] . "</td>
                    <td>" . $progetto["TotaleOreSorveglianza"] . "</td>
                </tr>";
        }
        $tableProgettiOre .= "</table>";
        echo $tableProgettiOre;
    }


        // TERZA TABELLA: Riepilogo delle ore totali per tutti i progetti
        $tableOre = "<table class='tbVisua' id='tbOre'>
                        <tr>
                            <th>Ore totali Progettazione</th>
                            <th>Ore totali Curricolari</th>
                            <th>Ore totali Extracurricolari</th>
                            <th>Ore totali Sorveglianza</th>
                        </tr>
                        <tr>";

        // Query per calcolare il totale complessivo di tutte le ore
        $stm = $conn->prepare("
            SELECT 
                COALESCE(SUM(ri.oreProgettazione), 0) AS oreProgettazione,
                COALESCE(SUM(ri.oreCurricolari), 0) AS oreCurricolari,
                COALESCE(SUM(ri.oreExtraCurricolari), 0) AS oreExtraCurricolari,
                COALESCE(SUM(ri.oreSorveglianza), 0) AS oreSorveglianza
            FROM risorseInterne ri
            JOIN progetti_risorse pr ON ri.id = pr.fk_risorsaInterna
        ");
        $stm->execute();
        $row = $stm->fetch();

        // Popola la tabella con i risultati
        $tableOre .= "<td>".$row["oreProgettazione"]."</td>";
        $tableOre .= "<td>".$row["oreCurricolari"]."</td>";
        $tableOre .= "<td>".$row["oreExtraCurricolari"]."</td>";
        $tableOre .= "<td>".$row["oreSorveglianza"]."</td>";

        $tableOre .= "</tr></table>";
        echo $tableOre;
        echo "<p class='subTit' style='margin-top:40px;'>Risorse Interne - Valori Rendicontati</p>";
        if (!$idDoc) {
                        // Query per calcolare le ore effettive per ogni progetto
        $stmOreEffettive = $conn->prepare("
            SELECT 
                p.id AS ID_Progetto,
                p.titolo AS Titolo_Progetto,
                COALESCE(SUM(ri.OreCurricolariEffettive), 0) AS TotOreCurrEff,
                COALESCE(SUM(ri.OreExtraCurricolariEffettive), 0) AS TotOreExtraCurrEff,
                COALESCE(SUM(ri.OreSorveglianzaEffettive), 0) AS TotOreSorvEff,
                COALESCE(SUM(ri.OreProgettazioneEffettive), 0) AS TotOreProgEff
            FROM 
                progetti p
            LEFT JOIN progetti_risorse pr ON p.id = pr.fk_progetti
            LEFT JOIN risorseInterne ri ON pr.fk_risorsaInterna = ri.id
            GROUP BY 
                p.id, p.titolo
            ORDER BY 
                p.titolo
        ");

        $stmOreEffettive->execute();
        $oreEffettive = $stmOreEffettive->fetchAll();

        // Creazione della tabella delle ore effettive
        $tableOreEffettive = "<table class='tbVisua' id='tbOreEffettive'>
            <tr>
                <th>Titolo Progetto</th>
                <th>Ore Curricolari Effettive</th>
                <th>Ore Extra-Curricolari Effettive</th>
                <th>Ore Sorveglianza Effettive</th>
                <th>Ore Progettazione Effettive</th>
            </tr>";

        foreach($oreEffettive as $eff) {
            $tableOreEffettive .= "<tr>
                <td>" . htmlspecialchars($eff["Titolo_Progetto"], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . $eff["TotOreCurrEff"] . "</td>
                <td>" . $eff["TotOreExtraCurrEff"] . "</td>
                <td>" . $eff["TotOreSorvEff"] . "</td>
                <td>" . $eff["TotOreProgEff"] . "</td>
            </tr>";
        }

        $tableOreEffettive .= "</table>";

        // Visualizza la tabella delle ore effettive
        echo $tableOreEffettive;
}

        // Creazione della tabella delle ore effettive totali
        $tableOreEffTotali = "<table class='tbVisua' id='tbOreEffTotali'>
        <tr>
            <th>Totale Ore Curricolari Effettive</th>
            <th>Totale Ore Extra-Curricolari Effettive</th>
            <th>Totale Ore Sorveglianza Effettive</th>
            <th>Totale Ore Progettazione Effettive</th>
        </tr>
        <tr>";

        $stmTotEff = $conn->prepare("
            SELECT 
                COALESCE(SUM(ri.OreCurricolariEffettive), 0) AS TotCurrEff,
                COALESCE(SUM(ri.OreExtraCurricolariEffettive), 0) AS TotExtraCurrEff,
                COALESCE(SUM(ri.OreSorveglianzaEffettive), 0) AS TotSorvEff,
                COALESCE(SUM(ri.OreProgettazioneEffettive), 0) AS TotProgEff
            FROM risorseInterne ri
            JOIN progetti_risorse pr ON ri.id = pr.fk_risorsaInterna
        ");
        $stmTotEff->execute();
        $rowEff = $stmTotEff->fetch();

        $tableOreEffTotali .= "<td>".$rowEff["TotCurrEff"]."</td>";
        $tableOreEffTotali .= "<td>".$rowEff["TotExtraCurrEff"]."</td>";
        $tableOreEffTotali .= "<td>".$rowEff["TotSorvEff"]."</td>";
        $tableOreEffTotali .= "<td>".$rowEff["TotProgEff"]."</td>";
        $tableOreEffTotali .= "</tr></table>";
        echo $tableOreEffTotali;

        echo "<p class='subTit' style='margin-top:40px;'>Risorse Esterne - Valori Previsti</p>";

        if (!$idDoc) {
            // --- RIEPILOGO ORE DOCENZA E COSTI RISORSE ESTERNE (per progetto) ---
            $tableOreEst = "<table class='tbVisua' id='tbOreEst'>
                <tr>
                    <th>Titolo Progetto</th>
                    <th>Totale Ore Docenza Esterna</th>
                    <th>Totale Costo Previsto</th>
                </tr>";

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
                GROUP BY p.id, p.titolo
                ORDER BY p.titolo
            ");
            $stmEst->execute();
            $esterni = $stmEst->fetchAll();

            foreach($esterni as $est) {
                $tableOreEst .= "<tr>
                    <td>" . htmlspecialchars($est["Titolo_Progetto"], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . $est["TotOreDocenza"] . "</td>
                    <td>" . $est["TotCostoPrevisto"] . " €</td>
                </tr>";
            }
            $tableOreEst .= "</table>";
            echo $tableOreEst;
        }
        

        // --- TOTALE GENERALE DOCENZA ESTERNA E COSTO PREVISTO ---
            $tableOreEstTot = "<table class='tbVisua' id='tbOreEstTotali'>
                <tr>
                    <th>Totale Ore Docenza Esterna</th>
                    <th>Totale Costo Previsto</th>
                </tr>
                <tr>";

            $stmEstTot = $conn->prepare("
                SELECT 
                    COALESCE(SUM(re.oreDocenza), 0) AS TotOreDocenza,
                    COALESCE(SUM(re.costoPrevisto), 0) AS TotCostoPrevisto
                FROM risorseEsterne re
                JOIN progetti_risorse pr ON re.id = pr.fk_risorsaEsterna
            ");
            $stmEstTot->execute();
            $estTot = $stmEstTot->fetch();

            $tableOreEstTot .= "<td>".$estTot["TotOreDocenza"]."</td>";
            $tableOreEstTot .= "<td>".$estTot["TotCostoPrevisto"]." €</td>";
            $tableOreEstTot .= "</tr></table>";
            echo $tableOreEstTot;

            echo "<p class='subTit' style='margin-top:40px;'>Risorse Esterne - Valori Rendicontati</p>";
        if (!$idDoc) {
                        // Tabella: Ore docenza effettive e costo effettivo per progetto
            $tableOreEstEff = "<table class='tbVisua' id='tbOreEstEffettive'>
                <tr>
                    <th>Titolo Progetto</th>
                    <th>Ore Docenza Effettive</th>
                    <th>Costo Effettivo (€)</th>
                </tr>";

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
                GROUP BY 
                    p.id, p.titolo
                ORDER BY 
                    p.titolo
            ");
            $stmEstEff->execute();
            $oreEstEffettive = $stmEstEff->fetchAll();

            foreach($oreEstEffettive as $estEff) {
                $tableOreEstEff .= "<tr>
                    <td>" . htmlspecialchars($estEff["Titolo_Progetto"], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . $estEff["TotOreDocEff"] . "</td>
                    <td>" . number_format($estEff["TotCostoEff"], 2, ',', '.') . "</td>
                </tr>";
            }
            $tableOreEstEff .= "</table>";

            echo $tableOreEstEff;
        }

        // Tabella: Totale generale ore docenza effettive e costo effettivo
        $tableOreEstEffTot = "<table class='tbVisua' id='tbOreEstEffTotali'>
            <tr>
                <th>Totale Ore Docenza Effettive</th>
                <th>Totale Costo Effettivo (€)</th>
            </tr>
            <tr>";

        $stmTotEstEff = $conn->prepare("
            SELECT 
                COALESCE(SUM(re.oreDocenzaEffettive), 0) AS TotOreDocEff,
                COALESCE(SUM(re.costoEffettivo), 0) AS TotCostoEff
            FROM risorseEsterne re
            JOIN progetti_risorse pr ON re.id = pr.fk_risorsaEsterna
        ");
        $stmTotEstEff->execute();
        $rowEstEff = $stmTotEstEff->fetch();

        $tableOreEstEffTot .= "<td>".$rowEstEff["TotOreDocEff"]."</td>";
        $tableOreEstEffTot .= "<td>".number_format($rowEstEff["TotCostoEff"], 2, ',', '.')."</td>";
        $tableOreEstEffTot .= "</tr></table>";

        echo $tableOreEstEffTot;




    ?>
    
    <!-- Form nascosto per reindirizzare al dettaglio progetto -->
    <form id="projectForm" action="dettaglio_progetto.php" method="POST" style="display:none;">
    	<input type="hidden" name="id" id="projectIdTaken">
	</form>
    
    <!-- Modal per i filtri delle classi (inizialmente nascosto) -->
    <div id="boxFiltri" class="modal fade show" tabindex="-1" style="display:none;">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                <!-- Header della Modal -->
                <div class="modal-header">
                    <h5 class="modal-title">Filtra Classi Coinvolte</h5>
                    <span id="closePopupp" class="btn-close"></span>
                </div>

                <!-- Corpo della Modal -->
                <div class="modal-body">
                    <h4>Classi Coinvolte</h4>

                    <!-- Sezione Indirizzo -->
                    <div id="indirizzo" class="mb-4">
                    <p class="section-title">Indirizzo</p>
                    <div class="checkbox-group">
                        <div class="form-check form-check-custom">
                        <input type="checkbox" class="form-check-input" id="informatico" name="indirizzo" value="Informatico" onchange="mostraClassi()">
                        <label for="informatico" class="form-check-label">Informatico</label>
                        </div>
                        <div class="form-check form-check-custom">
                        <input type="checkbox" class="form-check-input" id="relazioni_internazionali" name="indirizzo" value="Relazioni Internazionali" onchange="mostraClassi()">
                        <label for="relazioni_internazionali" class="form-check-label">Relazioni Int.</label>
                        </div>
                        <div class="form-check form-check-custom">
                        <input type="checkbox" class="form-check-input" id="grafico" name="indirizzo" value="Grafico" onchange="mostraClassi()">
                        <label for="grafico" class="form-check-label">Grafico</label>
                        </div>
                        <div class="form-check form-check-custom">
                        <input type="checkbox" class="form-check-input" id="scienze_applicate" name="indirizzo" value="Scienze Applicate" onchange="mostraClassi()">
                        <label for="scienze_applicate" class="form-check-label">Scienze App.</label>
                        </div>
                    </div>
                    </div>
                    <br>
                    <!-- Sezione Annata -->
                    <div id="annata" class="mb-4">
                    <p class="section-title">Annata</p>
                    <div class="checkbox-group">
                        <div class="form-check form-check-custom">
                        <input type="checkbox" class="form-check-input" id="annata1" name="annata" value="1" onchange="mostraClassi()">
                        <label for="annata1" class="form-check-label">I</label>
                        </div>
                        <div class="form-check form-check-custom">
                        <input type="checkbox" class="form-check-input" id="annata2" name="annata" value="2" onchange="mostraClassi()">
                        <label for="annata2" class="form-check-label">II</label>
                        </div>
                        <div class="form-check form-check-custom">
                        <input type="checkbox" class="form-check-input" id="annata3" name="annata" value="3" onchange="mostraClassi()">
                        <label for="annata3" class="form-check-label">III</label>
                        </div>
                        <div class="form-check form-check-custom">
                        <input type="checkbox" class="form-check-input" id="annata4" name="annata" value="4" onchange="mostraClassi()">
                        <label for="annata4" class="form-check-label">IV</label>
                        </div>
                        <div class="form-check form-check-custom">
                        <input type="checkbox" class="form-check-input" id="annat5" name="annata" value="5" onchange="mostraClassi()">
                        <label for="annat5" class="form-check-label">V</label>
                        </div>
                    </div>
                    </div>
                    <br>
                    <!-- Visualizzazione delle classi filtrate -->
                    <p class="section-title">Classi filtrate</p>
                    <div id="classi-filtrate-container">
                    <div id="classi-selezionate"></div>
                    </div>

                    <!-- Bottoni per selezionare/deselezionare tutti i filtri -->
                    <div class="d-flex justify-content-center mt-3">
                    <p id="selectAll" class="toggle-select text-primary mx-2 small" onclick="selezionaCheckbox()">Seleziona tutti</p>
                    <p id="deselectAll" class="toggle-select text-primary mx-2 small" style="display:none;" onclick="deselezionaCheckbox()">Deseleziona tutti</p>
                    </div>

                    <!-- Bottone per inviare i filtri -->
                    <button type="button" id="submitFiltri" onclick="prendiClassi();" class="btn btn-primary w-100 mt-4">Invia</button>
                </div>
                </div>
            </div>
            </div>
    
    <!-- Pulsante per generare e stampare il report -->
    <button id="butt-stampa">Stampa</button>

    <br>
</body>
<script> 
    // Riferimenti agli elementi DOM per il popup e stampa
    const openPopupButton = document.getElementById('butt-filtri');
    const stampa = document.getElementById('butt-stampa');
    const closePopupButton = document.getElementById('closePopupp');
    const popup = document.getElementById('boxFiltri');
    
    // Array per memorizzare gli ID dei progetti
    var progetti = [];
    
    // Raccoglie tutti gli ID dei progetti dai tag <p> nascosti
    var progettiElements = document.querySelectorAll('p[get-progetti]');

    // Itera sugli elementi trovati e li aggiunge all'array progetti
    progettiElements.forEach(function(element) {
        var idProgetto = element.getAttribute('get-progetti');
        progetti.push(idProgetto);
    });
    
    // Funzione per collegare gli event listener alle righe della tabella progetti
    function attachClickEventsToRows() {
        var rows = document.querySelectorAll('#firstTb tr');
        
        rows.forEach(function(row, index) {
            if (index > 0) { // Salta l'intestazione (prima riga)
                var cells = row.querySelectorAll('td');
                cells.forEach(function(cell) {
                    cell.addEventListener('click', function() {
                        // Quando si clicca su una cella, ottiene l'ID del progetto dalla prima colonna
                        var projectId = row.cells[0].innerHTML;
                        // Imposta il valore nel form nascosto e lo invia
                        document.getElementById('projectIdTaken').value = projectId;
                        document.getElementById('projectForm').submit();
                    });
                });
            }
        });
    }
    
    // Inizializza gli event listener quando il DOM è caricato
    document.addEventListener('DOMContentLoaded', function() {
        attachClickEventsToRows();
    });
    
    // Gestione evento click sul pulsante stampa
    stampa.addEventListener('click', function() {
        // Richiesta AJAX per generare un file Word con i progetti
        jQuery.ajax({
          type: 'POST',
          url: "generaWord.php",
          dataType: 'json',
          data: {
              'idProjects': progetti, // Passa l'array degli ID progetti
          },
          success: function(response) {
              if (response.success) {
                  // Reindirizza il browser per scaricare il file
                  window.location.href = response.pdf_url;
              } else {
                  console.log("Errore: " + response.success);
              }
          },
          error: function(xhr, status, error) {
              console.log("Errore nella chiamata AJAX: " + error);
          }
      });
    });
    
    // Funzione per gestire il click su un progetto specifico (navigazione al dettaglio)
    function evento(id){
        document.getElementById('projectIdTaken').value = id; // Imposta l'ID del progetto nel campo nascosto
        document.getElementById('projectForm').submit(); // Invia il modulo per andare alla pagina di dettaglio
    }

    // Chiusura del popup dei filtri
    closePopupButton.addEventListener('click', function() {
        popup.style.display="none";
    });
    
    // Funzione per disabilitare lo scroll quando il popup è aperto
    function disableScroll() {
        // Calcola la larghezza della barra di scorrimento
        var scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

        // Blocca lo scroll e compensa la larghezza della barra di scorrimento
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = scrollbarWidth + 'px';
    }

    // Funzione per riabilitare lo scroll quando il popup è chiuso
    function enableScroll() {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    // Gestione evento apertura popup filtri
    openPopupButton.addEventListener('click', function() {
        popup.style.display='inline-block';
        disableScroll(); // Blocca lo scroll
    });

    // Gestione evento chiusura popup filtri
    closePopupButton.addEventListener('click', function() {
        popup.style.display='none';
        enableScroll(); // Riabilita lo scroll
    });

// Funzione per raccogliere le classi selezionate e filtrare la tabella
function prendiClassi() {
    var classiSelezionate = [];
    // Raccoglie tutti i checkbox selezionati nel contenitore delle classi
    var checkboxes = document.querySelectorAll('#classi-selezionate input[type="checkbox"]:checked');

    // Aggiunge i valori selezionati all'array
    checkboxes.forEach(function(checkbox) {
        classiSelezionate.push(checkbox.value);
    });
    
    // Richiesta AJAX per filtrare i progetti in base alle classi selezionate
    jQuery.ajax({
        type: 'POST',
        url: "crea_selez_filtrata.php",
        dataType: 'json',
        data: {
            'classi': classiSelezionate,
        },
        success: function(response) {                        
            if (response.success) {
                // Aggiorna le tabelle con i dati filtrati
                $('#firstTb').html(response.risposta); // Tabella elenco progetti
                $('#tbProgettiOre').html(response.risposta_ore_progetti); // Ore teoriche per progetto
                $('#tbOre').html(response.risposta_ore_totali); // Ore totali teoriche
                $('#tbOreEffettive').html(response.risposta_ore_effettive); // Ore effettive per progetto
                $('#tbOreEffTotali').html(response.risposta_ore_eff_totali); // Ore effettive totali
                $('#tbMatPom').html(response.risposta_mat_pom); // Mattino/pomeriggio

                // Aggiorna anche le tabelle delle RISORSE ESTERNE
                $('#tbOreEst').html(response.risposta_ore_est); // Ore docenza e costi previsti per progetto
                $('#tbOreEstTotali').html(response.risposta_ore_est_tot); // Totale docenza/costi previsti
                $('#tbOreEstEffettive').html(response.risposta_ore_est_eff); // Ore docenza e costi effettivi per progetto
                $('#tbOreEstEffTotali').html(response.risposta_ore_est_eff_tot); // Totale docenza/costi effettivi

                // Aggiorna l'array dei progetti per la stampa
                progetti = response.risposta_progetti;
                
                // Ricollegare gli event listener alle righe della tabella filtrata
                attachClickEventsToRows();
            } else {
                console.error("Errore: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Errore nella chiamata AJAX: ", error);
        }
    });

    // Chiude il popup e riabilita lo scroll
    popup.style.display = 'none';
    enableScroll();
}


    // Funzione per tornare alla pagina precedente
    function torna(){
        window.location.href = "ins_visua_project.php";
    }

    // Inizializza i tooltip di Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
            
</script>
<script src="script.js"></script>
</html>