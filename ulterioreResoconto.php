<?php
ini_set('session.gc_maxlifetime', 7200);
session_set_cookie_params(7200);
session_start();

// Verifico se l'utente ha efettuato l'accesso oppure no
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Resoconto</title>
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
            .logo {
            width: 140px;
            margin: 0 auto;
            transition: transform 0.3s ease;
        }
        .logo:hover {
            transform: scale(1.05);
        }

        .top-bar p {
                font-size: 1.5rem;
                font-weight: bold;
                margin: 0;
                text-align: center;
                flex-grow: 1;
            }
            .card-header{
                background-color:#00245d !important;
            }
            .table-primary {
                background-color:#00245d;
                color: white;
                font-size: 0.9em;
            }
            .form-select:disabled {
                background-color: #e3e8ee;
                cursor: not-allowed;
                border: 1px solid #e3e8ee;
                padding: 0.25rem 0.5rem;
            }
            .form-check-input {
                margin: 0 auto;
                display: block;
                transform: scale(1.2);
                cursor: not-allowed;
            }
            .form-check-input:disabled {
                opacity: 1;
                filter: none;
            }
            th {
                text-align: center;
                vertical-align: middle;
                white-space: nowrap;
            }
            td {
                vertical-align: middle;
                padding: 0.5rem;
            }

        </style>
    </head>
    <body>
        <div class='top-bar d-flex align-items-center p-3'>
        <button id="back-btn" class="btn btn-light me-3" onclick="torna()">←</button>
        <p id="title" class="m-0 mx-auto text-white">ULTERIORE RESOCONTO</p>
            <img src='img/logo.png' alt='Logo' class='logo'>
        </div>
        <br><br><br><br>
        <?php
            // Prima di iniziare la tabella, estrai tutti i progetti dal database
            require_once("db.php");

            // Query per ottenere tutti i progetti
            $stmProjects = $conn->prepare("SELECT id, titolo, tempi_svolgimento FROM progetti");
            $stmProjects->execute();
            $projects = $stmProjects->fetchAll();

            // Inizia la struttura della tabella
            ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header text-white bg-primary">
                    <h5 class="mb-0">ATTIVITÀ</h5>
                </div>
                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th style="width: 17%">Periodo</th>
                                    <th style="width: 15%">Destinatari</th>
                                    <th style="width: 18%">Nome progetto</th>
                                    <th style="width: 4.16%">Set</th>
                                    <th style="width: 4.16%">Ott</th>
                                    <th style="width: 4.16%">Nov</th>
                                    <th style="width: 4.16%">Dic</th>
                                    <th style="width: 4.16%">Gen</th>
                                    <th style="width: 4.16%">Feb</th>
                                    <th style="width: 4.16%">Mar</th>
                                    <th style="width: 4.16%">Apr</th>
                                    <th style="width: 4.16%">Mag</th>
                                    <th style="width: 4.16%">Giu</th>
                                    <th style="width: 4.16%">Lug</th>
                                    <th style="width: 4.16%">Ago</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Per ogni progetto, genera una riga
                            foreach($projects as $project) {
                                // Ottieni informazioni sulle classi destinatarie del progetto
                                $stmDestinatari = $conn->prepare("
                                    SELECT c.anno_classe, c.sezione, p.ore_mattina, p.ore_pomeriggio, pr.origineProgetto
                                    FROM progetti_classi p
                                    JOIN progetti pr ON p.fk_progetto = pr.id
                                    JOIN classi c ON p.fk_classe = c.id
                                    WHERE p.fk_progetto = :projectId
                                ");
                                $stmDestinatari->bindParam(':projectId', $project['id']);
                                $stmDestinatari->execute();
                                $destinatari = $stmDestinatari->fetchAll();
                                
                                // Determina se il progetto è mattino, pomeriggio o entrambi
                                $hasMattina = false;
                                $hasPomeriggio = false;
                                $tipoDestinatari = $destinatari[0]['origineProgetto'];
                                foreach($destinatari as $dest) {
                                    if ($dest['ore_mattina'] > 0) {
                                        $hasMattina = true;
                                    }
                                    if ($dest['ore_pomeriggio'] > 0) {
                                        $hasPomeriggio = true;
                                    }
                                }
                                
                                // Determina il periodo in base alle ore
                                $periodo = "mattino";
                                if ($hasMattina && $hasPomeriggio) {
                                    $periodo = "mattino e pomeriggio";
                                } elseif ($hasPomeriggio) {
                                    $periodo = "pomeriggio";
                                }
                                
                                // Analisi del tempo di svolgimento per determinare i mesi
                                $mesi = [
                                    'Set' => false, 'Ott' => false, 'Nov' => false, 'Dic' => false,
                                    'Gen' => false, 'Feb' => false, 'Mar' => false, 'Apr' => false,
                                    'Mag' => false, 'Giu' => false, 'Lug' => false, 'Ago' => false
                                ];
                                
                                // Analizza il campo tempi_svolgimento per determinare i mesi di attività
                                $tempiSvolgimento = strtolower($project['tempi_svolgimento']);
                                
                                // Cerca le parole chiave dei mesi nel campo tempi_svolgimento
                                if (strpos($tempiSvolgimento, 'settembre') !== false) $mesi['Set'] = true;
                                if (strpos($tempiSvolgimento, 'ottobre') !== false) $mesi['Ott'] = true;
                                if (strpos($tempiSvolgimento, 'novembre') !== false) $mesi['Nov'] = true;
                                if (strpos($tempiSvolgimento, 'dicembre') !== false) $mesi['Dic'] = true;
                                if (strpos($tempiSvolgimento, 'gennaio') !== false) $mesi['Gen'] = true;
                                if (strpos($tempiSvolgimento, 'febbraio') !== false) $mesi['Feb'] = true;
                                if (strpos($tempiSvolgimento, 'marzo') !== false) $mesi['Mar'] = true;
                                if (strpos($tempiSvolgimento, 'aprile') !== false) $mesi['Apr'] = true;
                                if (strpos($tempiSvolgimento, 'maggio') !== false) $mesi['Mag'] = true;
                                if (strpos($tempiSvolgimento, 'giugno') !== false) $mesi['Giu'] = true;
                                if (strpos($tempiSvolgimento, 'luglio') !== false) $mesi['Lug'] = true;
                                if (strpos($tempiSvolgimento, 'agosto') !== false) $mesi['Ago'] = true;
                                
                                
                                
                                // Crea una singola riga per il progetto
                                ?>
                                <tr>
                                    <td>
                                        <select class="form-select" disabled>
                                            <option selected><?php echo $periodo; ?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select" disabled>
                                            <option selected><?php echo $tipoDestinatari; ?></option>
                                        </select>
                                    </td>
                                    <td><?php echo htmlspecialchars($project['titolo']); ?></td>
                                    <?php foreach ($mesi as $mese => $attivo): ?>
                                        <td><input type="checkbox" class="form-check-input" <?php echo $attivo ? 'checked' : ''; ?> disabled></td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>





            <script>
                    function torna(){
                        window.location.href = "ins_visua_project.php";
                    }

            </script>
    </body>
</html>