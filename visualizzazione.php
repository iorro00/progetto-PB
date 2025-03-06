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

            
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var rows = document.querySelectorAll('#firstTb tr');
                
                rows.forEach(function(row) {
                    var cells = row.querySelectorAll('td'); // Seleziona tutti i td eccetto l'ultimo
                    cells.forEach(function(cell) {
                        cell.addEventListener('click', function() {
                            var projectId = row.cells[0].innerHTML; // L'ID del progetto è nella prima cella
                            document.getElementById('projectIdTaken').value = projectId; // Imposta l'ID del progetto nel campo nascosto
                            document.getElementById('projectForm').submit(); // Invia il modulo
                        });
                    });
                });
            });
        </script>
    </head>
<body>
    <div id="top-bar" class="top-bar d-flex align-items-center p-3" style="display: none;">
            <button id="back-btn" class="btn btn-light me-3" onclick="torna()">←</button>
            <p id="title" class="m-0 mx-auto text-white">ELENCO PROGETTI IIS BLAISE PASCAL</p>
            <button id="butt-filtri" >Filtri</button>
    </div>
    <br><br><br>

            <?php
        require_once("db.php");

        // Prima tabella (progetti) - rimane invariata
        $table = "<table class='tbVisua' id='firstTb'>
                    <tr>
                        <th style= display:none;></th>
                        <th>Titolo Progetto</th>
                        <th>Dipartimento</th>
                        <th>Docente Referente</th>
                    </tr>";

        $stm = $conn->prepare("SELECT id, titolo, fk_dipartimento, fk_docenteReferente FROM progetti order by titolo asc");
        $stm->execute();
        $result = $stm->fetchAll();
                
        foreach($result as $row) {
            $varDip = '';
            if($row["fk_dipartimento"]){
                echo "<p get-progetti='".$row['id']."' style=display:none class='progett'></p>";
                $dip = $conn->prepare("SELECT nome FROM dipartimento WHERE id = ?");
                $dip->execute([$row["fk_dipartimento"]]);
                $resultDip = $dip->fetch();
                $varDip = $resultDip["nome"];
            }
            $varRef = '';
            if($row["fk_docenteReferente"]){
                $ref = $conn->prepare("SELECT nominativo FROM docenteReferente WHERE id = ?");
                $ref->execute([$row["fk_docenteReferente"]]);
                $resultRef = $ref->fetch();
                $varRef = $resultRef["nominativo"];
            }
            $table .= "<tr>
                        <td style= display:none; >" . $row["id"] . "</td>
                        <td>" . $row["titolo"] . "</td>
                        <td>" . $varDip . "</td>
                        <td>" . $varRef . "</td>
                        </tr>";
        }
        $table .= "</table>";
        echo $table;

        $tableProgettiOre = "<table class='tbVisua' id='tbProgettiOre'>
        <tr>
            <th>Titolo Progetto</th>
            <th>Ore Progettazione</th>
            <th>Ore Curricolari</th>
            <th>Ore Extracurricolari</th>
            <th>Ore Sorveglianza</th>
        </tr>";

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

        // Terza tabella (ore totali) - ora con somma senza duplicati
        $tableOre = "<table class='tbVisua' id='tbOre'>
                        <tr>
                            <th>Ore totali Progettazione</th>
                            <th>Ore totali Curricolari</th>
                            <th>Ore totali Extracurricolari</th>
                            <th>Ore totali Sorveglianza</th>
                        </tr>
                        <tr>";

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

        $tableOre .= "<td>".$row["oreProgettazione"]."</td>";
        $tableOre .= "<td>".$row["oreCurricolari"]."</td>";
        $tableOre .= "<td>".$row["oreExtraCurricolari"]."</td>";
        $tableOre .= "<td>".$row["oreSorveglianza"]."</td>";

        $tableOre .= "</tr></table>";
        echo $tableOre;


        
        $tableMatPom = "<table class='tbVisua' id='tbMatPom'>
                        <tr>
                            <th>Ore totali Mattino</th>
                            <th>Ore totali Pomeriggio</th>
                        </tr>
                        <tr>"; 
        $stm3 = $conn->prepare("SELECT *
                             FROM progetti_classi
                             GROUP BY fk_progetto");
     	$stm3->execute();
     	$oreMP = $stm3->fetchAll();
        $oreMattino = 0;
        $orePomeriggio = 0;

        foreach($oreMP as $row)
        {
        	$oreMattino += $row["ore_mattina"];
            $orePomeriggio += $row["ore_pomeriggio"];
        }
        $tableMatPom .= "<td>".$oreMattino."</td>";
        $tableMatPom .= "<td>".$orePomeriggio."</td>";
        $tableMatPom .= "</tr></table>";
        echo $tableMatPom;

    ?>
    <form id="projectForm" action="dettaglio_progetto.php" method="POST" style="display:none;">
    	<input type="hidden" name="id" id="projectIdTaken">
	</form>
    <div id="boxFiltri" class="modal fade show" tabindex="-1" style="display:none;">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="boxFiltri-content modal-content">
                        <div class="modal-header border-0">
                            <span id="closePopup" class="btn-close"></span>
                        </div>
                        
                        <div id="colonnaBis" class="modal-body">
                            <h4 class="text-center mb-4">Classi coinvolte</h4>

                            <div id="indirizzo" class="mb-4">
                                <p class="mb-3">Indirizzo</p>

                                <div class="content">
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="w-75"><label for="informatico" class="form-label mb-0">Informatico:</label></td>
                                                <td class="w-25">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="informatico" name="indirizzo" value="Informatico" onchange="mostraClassi()">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-75"><label for="relazioni_internazionali" class="form-label mb-0">Relazioni Int:</label></td>
                                                <td class="w-25">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="relazioni_internazionali" name="indirizzo" value="Relazioni Internazionali" onchange="mostraClassi()">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-75"><label for="grafico" class="form-label mb-0">Grafico:</label></td>
                                                <td class="w-25">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="grafico" name="indirizzo" value="Grafico" onchange="mostraClassi()">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-75"><label for="scienze_applicate" class="form-label mb-0">Scienze App:</label></td>
                                                <td class="w-25">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="scienze_applicate" name="indirizzo" value="Scienze Applicate" onchange="mostraClassi()">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div id="annata" class="mb-4">
                                <p class="mb-3">Annata</p>

                                <div class="content">
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="w-75"><label for="annata1" class="form-label mb-0">I:</label></td>
                                                <td class="w-25">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="annata1" name="annata" value="1" onchange="mostraClassi()">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-75"><label for="annata2" class="form-label mb-0">II:</label></td>
                                                <td class="w-25">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="annata2" name="annata" value="2" onchange="mostraClassi()">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-75"><label for="annata3" class="form-label mb-0">III:</label></td>
                                                <td class="w-25">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="annata3" name="annata" value="3" onchange="mostraClassi()">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-75"><label for="annata4" class="form-label mb-0">IV:</label></td>
                                                <td class="w-25">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="annata4" name="annata" value="4" onchange="mostraClassi()">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-75"><label for="annat5" class="form-label mb-0">V:</label></td>
                                                <td class="w-25">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="annat5" name="annata" value="5" onchange="mostraClassi()">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <p class="fw-bold mb-2">Classi filtrate</p>
                            <div id="classi-selezionate" class="border rounded p-2 bg-light"></div>
                            
                            <div class="d-flex justify-content-between mt-3">
                                <p id="selectAll" class="text-primary mb-0 text-decoration-none" style="cursor: pointer;" onclick="selezionaCheckbox()">Seleziona tutti</p>
                                <p id="deselectAll" class="text-primary mb-0 text-decoration-none" style="display:none; cursor: pointer;" onclick="deselezionaCheckbox()">Deseleziona tutti</p>
                            </div>
                            
                            <input type='button' value='Invia' id='submitFiltri' onclick="prendiClassi();" class="btn btn-primary w-100 mt-4">
                        </div>
                    </div>
                </div>
            </div>
    
    <button id="butt-stampa" >Stampa</button>

    <br>
</body>
<script> 
    const openPopupButton = document.getElementById('butt-filtri');
    const stampa = document.getElementById('butt-stampa');
    const closePopupButton = document.getElementById('closePopup');
    const popup = document.getElementById('boxFiltri');
    var progetti=[];
    
    var progettiElements = document.querySelectorAll('p[get-progetti]');

    // Itera sugli elementi trovati
    progettiElements.forEach(function(element) {
        // Recupera il valore dell'attributo get-progetti e aggiungilo all'array
        var idProgetto = element.getAttribute('get-progetti');
        progetti.push(idProgetto);
    });
    
    stampa.addEventListener('click', function() {
        jQuery.ajax({
          type: 'POST',
          url: "generaWord.php",
          dataType: 'json',
          data: {
              'idProjects': progetti,
          },
          success: function(response) {
              if (response.success) {
                  // Reindirizza il browser per scaricare il PDF
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
    
function evento(id){
	document.getElementById('projectIdTaken').value = id; // Imposta l'ID del progetto nel campo nascosto
    document.getElementById('projectForm').submit(); // Invia il modulo
}

    closePopupButton.addEventListener('click', function() {
        popup.style.display="none";
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
        var classiSelezionate = [];
        var checkboxes = document.querySelectorAll('#classi-selezionate input[type="checkbox"]:checked');

        checkboxes.forEach(function(checkbox) {
            classiSelezionate.push(checkbox.value);
        });
        
        jQuery.ajax({
            type: 'POST',
            url: "crea_selez_filtrata.php",
            dataType: 'json',
            data: {
                'classi': classiSelezionate,
            },
            success: function(response) {                        
                if (response.success) {
                    $('#firstTb').html(response.risposta);
                    $('#tbProgettiOre').html(response.risposta_ore_progetti);
                    $('#tbOre').html(response.risposta2);
                    $('#tbMatPom').html(response.risposta3);
                    
                    // Aggiorna l'array dei progetti per la stampa
                    progetti = response.risposta4;
                } else {
                    console.error("Errore: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Errore nella chiamata AJAX: ", error);
            }
        });

        popup.style.display = 'none'; // chiudi il popup
        enableScroll();
    }

    function torna(){
        window.location.href = "ins_visua_project.php";
    }
            
</script>
<script  src="script.js"></script>
</html>