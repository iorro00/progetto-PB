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
<html>
    <head>
        <title>Rendicontazione</title>
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
        </style>
    </head>
    <body>
    <div id="top-bar" class="top-bar d-flex align-items-center p-3">
        <button id="back-btn" class="btn btn-light me-3" onclick="tornaIndietroJS()">←</button>
        <p id="title" class="m-0 mx-auto text-white">RENDICONTAZIONE PROGETTI IIS BLAISE PASCAL</p>
        <button id="butt-filtri">Filtri</button>
    </div>
       <br><br><br><br> 
        <div class="container position-relative pt-4">
            <!-- Info Circle with Tooltip -->
            <div class="info-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cliccando su un progetto è possibile visualizzarne i contenuti e rendicontare le ore svolte; passando con il cursore sopra il progetto appaiono due icone sulla destra, la prima consente di modificarlo la seconda di eliminarlo.">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#00245d" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                </svg>
            </div>
        </div>
        <?php
            require_once 'db.php';

            // PHP function removed because we're now using JavaScript for redirection.
        ?>
        <script>
        function tornaIndietroJS(){
            window.location.href = "<?php echo ($_SESSION['nominativo'] === 'Amministratore') ? 'pagina_amm.php' : 'ins_visua_project.php'; ?>";
        }
        </script>
        <?php
            $table = "<table id='tbRendi'>
                        <tr>
                        	<th style= display:none;></th>
                            <th>Titolo Progetto</th>
                            <th>Dipartimento</th>
                            <th>Docente Referente</th>
                            <th></th>
                            <th></th>
                        </tr>";

            if($_SESSION["nominativo"] == "Amministratore")
            {
                $stm = $conn->prepare("SELECT id, titolo, fk_dipartimento, fk_docenteReferente FROM progetti order by titolo asc");
                $stm->bindParam(':nominativo', $_SESSION["nominativo"]);
                $stm->execute();
                $result = $stm->fetchAll();
            }
            else{
                $stm = $conn->prepare("SELECT p.id, p.titolo, p.fk_dipartimento, p.fk_docenteReferente FROM progetti p INNER JOIN docenteReferente ON docenteReferente.id = fk_docenteReferente WHERE docenteReferente.nominativo = :nominativo order by titolo asc");
                $stm->bindParam(':nominativo', $_SESSION["nominativo"]);
                $stm->execute();
                $result = $stm->fetchAll();
            }
			echo "<p style=display:none; get-nominativo='".$_SESSION["nominativo"]."'></p>";
            
            foreach($result as $row) {
                $varDip = '';
                if($row["fk_dipartimento"]){
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
                			<td style=display:none;>" . $row["id"] . "</td>
                            <td>" . $row["titolo"] . "</td>
                            <td>" . $varDip . "</td>
                            <td>" . $varRef . "</td>
                        	<td class='icon-mod'><div prendi-id2='". $row["id"]."' style='display:none;' class='extra-option-left' id='extraOptionLeft-".$row["id"]."'><img src='img/modifica.png' class='modIcon'></div></td>
        					<td class='icon-del'><div prendi-id='" . $row["id"] . "' style='display:none;' class='extra-option' id='extraOption-".$row["id"]."'><img src='img/delete.png' class='deleteIcon'></div></td>
                        </tr>";
            }
            $table .= "</table>";
            echo $table;
        ?>
        
        <div id="confermaElim" style="display:none;">
          <div class="confermaElim-content">
              <p>Sei sicuro di eliminare questo progetto?</p>
              <button id="confirmButton" class="conf-button">Conferma</button>
              <button id="cancelButton" class="conf-button">Annulla</button>
          </div>
    	</div>
        
        <div id="boxFiltri" class="modal fade show" tabindex="-1" style="display:none;">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                <!-- Header della Modal -->
                <div class="modal-header">
                    <h5 class="modal-title">Filtra Classi Coinvolte</h5>
                    <span id="closePopup" class="btn-close"></span>
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
                </div>
            </div>
            </div>
        
        <form id="projectForm" action="dettaglio_rendi.php" method="POST" style="display:none;">
    		<input type="hidden" name="id" id="projectIdTaken">
		</form>
        
        <form id="hiddenForm" action="mod_project.php" method="POST" style="display:none;">
    		<input type="hidden" name="id" id="hiddenId">
		</form>

    
         
    </body>
    <script  src="script.js"></script>
    <script>
      function eventi(id){
      document.getElementById('projectIdTaken').value = id; // Imposta l'ID del progetto nel campo nascosto
      document.getElementById('projectForm').submit(); // Invia il modulo
    }

    var rows = document.querySelectorAll('#tbRendi tr:not(:first-child)');
    const openPopupButton = document.getElementById('butt-filtri');
    const closePopupButton = document.getElementById('closePopup');
    const popup = document.getElementById('boxFiltri');
    var prendiId;
    var modal = document.getElementById("confermaElim");
    var confirmButton = document.getElementById("confirmButton");
    var cancelButton = document.getElementById("cancelButton");


    rows.forEach(function(row) {
        // Seleziona tutte le celle td tranne le ultime due in ogni riga
        var cells = row.querySelectorAll('td:not(:nth-last-child(-n+2))');

        cells.forEach(function(cell) {
            cell.addEventListener('click', function() {
                var projectId = row.cells[0].innerHTML; // L'ID del progetto è nella prima cella
                document.getElementById('projectIdTaken').value = projectId; // Imposta l'ID del progetto nel campo nascosto
                document.getElementById('projectForm').submit(); // Invia il modulo
            });
        });
    });
    
function eventi(id){
  document.getElementById('projectIdTaken').value = id; // Imposta l'ID del progetto nel campo nascosto
  document.getElementById('projectForm').submit(); // Invia il modulo
}

function elimina(id){
  modal.style.display = "inline-block";
  $('#confirmButton').on('click', function() {
      jQuery.ajax({
      type: 'POST',
      url: "del_pro.php",
      dataType: 'json',
      data: {
          'id': id,
      }
      });
      location.reload();
      modal.style.display = "none";
  });

  $('#cancelButton').on('click', function() {
      modal.style.display = "none";
  });

}

    openPopupButton.addEventListener('click', function() {
        popup.style.display='inline-block';
        disableScroll(); // Blocca lo scroll quando il popup viene aperto
    });

    closePopupButton.addEventListener('click', function() {
        popup.style.display='none';
        enableScroll(); // Consenti nuovamente lo scroll quando il popup viene chiuso
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

    

function prendiClassi() {
    var classiSelezionate = [];
    var checkboxes = document.querySelectorAll('#classi-selezionate input[type="checkbox"]:checked');
    checkboxes.forEach(function(checkbox) {
      classiSelezionate.push(checkbox.value);
    });
    
    var elemento = document.querySelector('p[get-nominativo]');
    var nominativo = elemento.getAttribute('get-nominativo');

    jQuery.ajax({
        type: 'POST',
        url: "crea_selez_filtrata2.php",
        dataType: 'json',
        data: {
          'classi': classiSelezionate,
          'nominativo': nominativo,
        },
        success: function(response) {                        
            if (response.success) {
                var tb = response.risposta;
                $('#tbRendi').html(tb);

                // *** Questa riga è fondamentale! ***
                bindRendiTableEvents();
            } else {
                console.error("Errore: " + response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Errore AJAX: " + textStatus + ", " + errorThrown);
        }
    });

    popup.style.display='none';
    enableScroll();
}

    

$('table').on('mouseleave', 'tr', function() {
    var divId = $(this).find('.extra-option').attr('id');
    $('#' + divId).hide();
});

// Mostra il div extra quando il mouse entra nella riga della tabella
$('table').on('mouseenter', 'tr:not(:first-child)', function() {
    var $tr = $(this);
    var offset = $tr.offset();
    var trWidth = $tr.outerWidth();
    var extraOptionLeft = $tr.find('.extra-option-left');
    var extraOptionLeftWidth = extraOptionLeft.outerWidth();
    var divId = $tr.find('.extra-option').attr('id');
    $('#' + divId).css({
        top: offset.top,
        left: offset.left + $tr.outerWidth() + extraOptionLeftWidth
    }).show();

    var extraOptionLeftId = $tr.find('.extra-option-left').attr('id');
    $('#' + extraOptionLeftId).css({
        top: offset.top,
        left: offset.left +trWidth
    }).show();
});

$('table').on('mouseleave', 'tr:not(:first-child)', function() {
    var $tr = $(this);

    // Nascondi il div con classe 'extra-option'
    var extraOptionId = $tr.find('.extra-option').attr('id');
    $('#' + extraOptionId).hide();

    // Nascondi il div con classe 'extra-option-left'
    var extraOptionLeftId = $tr.find('.extra-option-left').attr('id');
    $('#' + extraOptionLeftId).hide();
});

// Mostra il div extra quando il mouse entra in .icon-del
$('.icon-del').on('mouseenter', function() {
    var divId = $(this).attr('id');
    $('#' + divId).show();
});

$('.icon-mod').on('mouseenter', function() {
    var divId = $(this).attr('id');
    $('#' + divId).show();
});

// Nascondi il div extra quando il mouse esce da .icon-del
$('.icon-del').on('mouseleave', function() {
    var divId = $(this).attr('prendi-id');
    $('#' + divId).hide();
});


document.querySelectorAll('.extra-option').forEach(function(option) {
    option.addEventListener('click', function() {
        prendiId = this.getAttribute('prendi-id');
        modal.style.display = "inline-block";
    });
});

document.querySelectorAll('.extra-option-left').forEach(function(option) {
    option.addEventListener('click', function() {
        prendiId = this.getAttribute('prendi-id2');
        document.getElementById('hiddenId').value = prendiId;
        document.getElementById('hiddenForm').submit();
    });
});
// Gestisci la conferma o l'annullamento dell'eliminazione
$('#confirmButton').on('click', function() {
    jQuery.ajax({
    type: 'POST',
    url: "del_pro.php",
    dataType: 'json',
    data: {
        'id': prendiId,
    },
    success: function(response) {
		modal.style.display = "none";
    	location.reload();    
     }
    });
});

$('#cancelButton').on('click', function() {
    modal.style.display = "none";
});

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

  // Funzione per gestire la modifica
  function modifica(id) {
    document.getElementById('hiddenId').value = id;
    document.getElementById('hiddenForm').submit();
  }

  // Funzione per riattivare tutti gli event handler sulle icone dopo ogni refresh della tabella
  function bindRendiTableEvents() {
    // Titolo, dip, referente → dettagli
    document.querySelectorAll('#tbRendi tr:not(:first-child)').forEach(function(row) {
      // Click su colonne info (escludi le colonne delle icone)
      row.querySelectorAll('td:not(:nth-last-child(-n+2))').forEach(function(cell) {
        cell.onclick = function() {
          var projectId = row.cells[0].innerText;
          document.getElementById('projectIdTaken').value = projectId;
          document.getElementById('projectForm').submit();
        }
      });
    });

    // Click su icona MODIFICA
    document.querySelectorAll('.extra-option-left').forEach(div => {
      const id = div.getAttribute('prendi-id2');
      div.onclick = () => modifica(id);
    });

    // Click su icona ELIMINA
    document.querySelectorAll('.extra-option').forEach(div => {
      const id = div.getAttribute('prendi-id');
      div.onclick = function() { elimina(id); };
    });
  }

  // All'avvio
  document.addEventListener('DOMContentLoaded', bindRendiTableEvents);


</script>
</html>