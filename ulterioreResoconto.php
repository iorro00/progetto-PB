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


/*css del filtro */
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
    max-width: 390px; /* Limite di larghezza */
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
        <div class='top-bar d-flex align-items-center p-3'>
        <button id="back-btn" class="btn btn-light me-3" onclick="torna()">←</button>
        <p id="title" class="m-0 mx-auto text-white">ULTERIORE RESOCONTO</p>
        <button id="butt-filtri" >Filtri</button>
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
      <table id="activityTable" class="table table-bordered">
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
        <tbody id="activityBody">
          <?php
          // Per ogni progetto, genera una riga
          foreach ($projects as $project) {
              // Memorizza l'ID progetto per JavaScript
              echo "<p get-progetti='" . $project['id'] . "' style='display:none' class='progett'></p>";

              // Ottieni informazioni sulle classi destinatarie
              $stmDestinatari = $conn->prepare("
                  SELECT c.anno_classe, c.sezione, p.ore_mattina, p.ore_pomeriggio, pr.origineProgetto
                  FROM progetti_classi p
                  JOIN progetti pr ON p.fk_progetto = pr.id
                  JOIN classi c ON p.fk_classe = c.id
                  WHERE p.fk_progetto = :projectId
              ");
              $stmDestinatari->bindParam(':projectId', $project['id'], PDO::PARAM_INT);
              $stmDestinatari->execute();
              $destinatari = $stmDestinatari->fetchAll();

              // Determina mattino/pomeriggio
              $hasMattina = false;
              $hasPomeriggio = false;
              $tipoDestinatari = $destinatari[0]['origineProgetto'];
              foreach ($destinatari as $dest) {
                  if ($dest['ore_mattina'] > 0)   $hasMattina   = true;
                  if ($dest['ore_pomeriggio'] > 0) $hasPomeriggio = true;
              }
              $periodo = $hasMattina && $hasPomeriggio
                      ? 'mattino e pomeriggio'
                      : ($hasPomeriggio ? 'pomeriggio' : 'mattino');

              // Prepara array mesi
              $mesi = [
                  'Set'=>false,'Ott'=>false,'Nov'=>false,'Dic'=>false,
                  'Gen'=>false,'Feb'=>false,'Mar'=>false,'Apr'=>false,
                  'Mag'=>false,'Giu'=>false,'Lug'=>false,'Ago'=>false
              ];
              $tempi = strtolower($project['tempi_svolgimento']);
              if (strpos($tempi,'settembre')  !== false) $mesi['Set'] = true;
              if (strpos($tempi,'ottobre')    !== false) $mesi['Ott'] = true;
              if (strpos($tempi,'novembre')   !== false) $mesi['Nov'] = true;
              if (strpos($tempi,'dicembre')   !== false) $mesi['Dic'] = true;
              if (strpos($tempi,'gennaio')    !== false) $mesi['Gen'] = true;
              if (strpos($tempi,'febbraio')   !== false) $mesi['Feb'] = true;
              if (strpos($tempi,'marzo')      !== false) $mesi['Mar'] = true;
              if (strpos($tempi,'aprile')     !== false) $mesi['Apr'] = true;
              if (strpos($tempi,'maggio')     !== false) $mesi['Mag'] = true;
              if (strpos($tempi,'giugno')     !== false) $mesi['Giu'] = true;
              if (strpos($tempi,'luglio')     !== false) $mesi['Lug'] = true;
              if (strpos($tempi,'agosto')     !== false) $mesi['Ago'] = true;
          ?>
            <tr>
              <td>
                <select class="form-select" disabled>
                  <option selected><?php echo $periodo; ?></option>
                </select>
              </td>
              <td>
                <select class="form-select" disabled>
                  <option selected><?php echo htmlspecialchars($tipoDestinatari); ?></option>
                </select>
              </td>
              <td><?php echo htmlspecialchars($project['titolo']); ?></td>
              <?php foreach ($mesi as $attivo): ?>
                <td>
                  <input type="checkbox" class="form-check-input" <?php echo $attivo ? 'checked' : ''; ?> disabled>
                </td>
              <?php endforeach; ?>
            </tr>
          <?php
          } // end foreach
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>




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
                    <!-- Sezione Classi Filtrate: ora centrata e con larghezza limitata -->
                    <p class="section-title">Classi filtrate</p>
                    <div id="classi-filtrate-container">
                    <div id="classi-selezionate"></div>
                    </div>

                    <!-- Bottoni Seleziona/Deseleziona -->
                    <div class="d-flex justify-content-center mt-3">
                    <p id="selectAll" class="toggle-select text-primary mx-2 small" onclick="selezionaCheckbox()">Seleziona tutti</p>
                    <p id="deselectAll" class="toggle-select text-primary mx-2 small" style="display:none;" onclick="deselezionaCheckbox()">Deseleziona tutti</p>
                    </div>

                    <!-- Bottone Invia -->
                    <button type="button" id="submitFiltri" onclick="prendiClassi();" class="btn btn-primary w-100 mt-4">Invia</button>
                </div>





    <script>
    // Funzione per tornare indietro
    function torna() {
        window.location.href = "ins_visua_project.php";
    }

    // Apri/chiudi popup
        const openPopupButton = document.getElementById('butt-filtri');
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

    function disableScroll() {
        // Calcola la larghezza della barra di scorrimento
        var scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

        // Aggiungi una classe al body per bloccare lo scroll e compensare la larghezza della barra di scorrimento
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = scrollbarWidth + 'px';
    }

    function enableScroll() {
        // Rimuovi la classe che blocca lo scroll e ripristina il padding destro
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }


    openPopupButton.addEventListener('click', function() {
        popup.style.display='inline-block';
        disableScroll(); // Blocca lo scroll quando il popup viene aperto
    });

    closePopupButton.addEventListener('click', function() {
        popup.style.display='none';
        enableScroll(); // Consenti nuovamente lo scroll quando il popup viene chiuso
    });

        function prendiClassi() {
            // 1) Raccogli le classi selezionate
            const selected = [];
            document.querySelectorAll('#classi-selezionate input[type="checkbox"]:checked')
                    .forEach(cb => selected.push(cb.value));

            // 2) Chiamata AJAX al nuovo endpoint
            jQuery.ajax({
                type: 'POST',
                url: 'selezione_filtrata3.php',
                dataType: 'json',
                data: { classi: selected },
                success: function(resp) {
                if (!resp.success) {
                    console.error('Filtro fallito:', resp.message);
                    return;
                }
                // 3) Sostituisci SOLO il <tbody>
                document.getElementById('activityBody').innerHTML = resp.html;
                // 4) Chiudi popup e riabilita scroll
                popup.style.display = 'none';
                enableScroll();
                },
                error: function(_,_,err) {
                console.error('AJAX error:', err);
                }
            });
            }





</script>
<script  src="script.js"></script>
    </body>
</html>