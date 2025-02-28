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
        </style>
    </head>
    <body>
    <div id="top-bar" class="top-bar d-flex align-items-center p-3">
        <button id="back-btn" class="btn btn-light me-3" onclick="tornaIndietroJS()">←</button>
        <p id="title" class="m-0 mx-auto text-white">RENDICONTAZIONE PROGETTI IIS BLAISE PASCAL</p>
        <button id="butt-filtri">Filtri</button>
    </div>
       <br><br><br><br> 
        
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
                // Prendi solo la risposta e assegnala a una variabile
                var tb = response.risposta;
                $('#tbRendi').html(tb);
				
            } else {
                console.error("Errore: " + response.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Errore AJAX: " + textStatus + ", " + errorThrown);
        }
    });

    popup.style.display='none'; //quando schiacchio invio, il popup si chiude
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

</script>
</html>