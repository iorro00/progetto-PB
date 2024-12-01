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
        <script>
document.addEventListener('DOMContentLoaded', function() {
    var rows = document.querySelectorAll('#tbVisua tr');
    rows.forEach(function(row) {
        var cells = row.querySelectorAll('td:not(:last-child)'); // Seleziona tutti i td eccetto l'ultimo
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
	<div id="box3">
        <img id="logo" src="img/logo.png" alt="Immagine non trovata">
        <h1><a id='pb-link' href='ins_visua_project.php'>PB</a></h1>
    </div>


    <div class="header-container">
        <p>Elenco progetti IIS Blaise Pascal</p>
        <button id="butt-filtri" >Filtri</button>
    </div>

ddddddd
    <?php
        require_once("db.php");

        $table = "<table id='tbVisua'>
                    <tr>
                    	<th style= display:none;></th>
                        <th>Titolo Progetto</th>
                        <th>Dipartimento</th>
                        <th>Docente Referente</th>
                        <th></th>
                    </tr>";

        $stm = $conn->prepare("SELECT id, titolo, fk_dipartimento, fk_docenteReferente FROM progetti");
        $stm->execute();
        $result = $stm->fetchAll();
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
            			<td style= display:none; >" . $row["id"] . "</td>
                        <td>" . $row["titolo"] . "</td>
                        <td>" . $varDip . "</td>
                        <td>" . $varRef . "</td>
                        <td class='icon-del'><div prendi-id='" . $row["id"] . "' style='display:none;' class='extra-option' id='extraOption-".$row["id"]."'><img src='img/delete.png' class='deleteIcon'></div></td>
                    </tr>";
        }
        $table .= "</table>";
        echo $table;
    ?>
    <form id="projectForm" action="dettaglio_progetto.php" method="POST" style="display:none;">
    	<input type="hidden" name="id" id="projectIdTaken">
	</form>

    <div id="boxFiltri" style="display:none;">
        <div class="boxFiltri-content">
            <span id="closePopup">&times;</span>
            <div id="colonnaBis">
                <p>Classi coinvolte</p>

                <div id="indirizzo">
                    <p class="subTit">Indirizzo</p>

                    <div class="content">
                        <table>
                            <tr>
                                <td><label for="informatico">Informatico:</label></td>
                                <td><input type="checkbox" id="informatico" name="indirizzo" value="Informatico" onchange="mostraClassi()"></td>
                            </tr>
                            <tr>
                                <td><label for="relazioni_internazionali">Relazioni Int:</label></td>
                                <td><input type="checkbox" id="relazioni_internazionali" name="indirizzo" value="Relazioni Internazionali" onchange="mostraClassi()"></td>
                            </tr>
                            <tr>
                                <td><label for="grafico">Grafico:</label></td>
                                <td><input type="checkbox" id="grafico" name="indirizzo" value="Grafico" onchange="mostraClassi()"></td>
                            </tr>
                            <tr>
                                <td><label for="scienze_applicate">Scienze App:</label></td>
                                <td><input type="checkbox" id="scienze_applicate" name="indirizzo" value="Scienze Applicate" onchange="mostraClassi()"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="annata">

                    <p class="subTit">Annata</p>

                    <div class="content">
                        <table>
                            <tr>
                                <td><label for="annata1">I:</label></td>
                                <td><input type="checkbox" id="annata1" name="annata" value="1" onchange="mostraClassi()"></td>
                            </tr>
                            <tr>
                                <td><label for="annata2">II:</label></td>
                                <td><input type="checkbox" id="annata2" name="annata" value="2" onchange="mostraClassi()"></td>
                            </tr>
                            <tr>
                                <td><label for="annata3">III:</label></td>
                                <td><input type="checkbox" id="annata3" name="annata" value="3" onchange="mostraClassi()"></td>
                            </tr>
                            <tr>
                                <td><label for="annata4">IV:</label></td>
                                <td><input type="checkbox" id="annata4" name="annata" value="4" onchange="mostraClassi()"></td>
                            </tr>
                            <tr>
                                <td><label for="annat5">V:</label></td>
                                <td><input type="checkbox" id="annat5" name="annata" value="5" onchange="mostraClassi()"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            
                <p class="subTit">Classi filtrate</p>
                <div id="classi-selezionate"></div>
                <p id="selectAll" onclick="selezionaCheckbox()" >Seleziona tutti</p>
                <p id="deselectAll" onclick="deselezionaCheckbox()" style="display:none" >Deseleziona tutti</p>
                <input type='button' value='Invia' id='submitFiltri' onclick=prendiClassi();>
            </div>
            
        </div>
    </div>
    
    <div id="confermaElim" style="display:none;">
        <div class="confermaElim-content">
            <p>Sei sicuro di eliminare questo progetto?</p>
            <button id="confirmButton" class="conf-button">Conferma</button>
            <button id="cancelButton" class="conf-button">Annulla</button>
        </div>
    </div>

    <p id='exit-link'><b><a id='exit-link' href='ins_visua_project.php'>Ritorna alla home</a></b></p>
</body>
<script> 
    const openPopupButton = document.getElementById('butt-filtri');
    const closePopupButton = document.getElementById('closePopup');
    const popup = document.getElementById('boxFiltri');
    var prendiId;


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

        popup.style.display='none'; //quando schiacchio invio, il popup si chiude
        enableScroll();
    }
    
    
    var modal = document.getElementById("confermaElim");
    var confirmButton = document.getElementById("confirmButton");
    var cancelButton = document.getElementById("cancelButton");

     $('table').on('mouseleave', 'tr', function() {
    var divId = $(this).find('.extra-option').attr('id');
    $('#' + divId).hide();
});

// Mostra il div extra quando il mouse entra nella riga della tabella
$('table').on('mouseenter', 'tr:not(:first-child)', function() {
    var $tr = $(this);
    var offset = $tr.offset();
    var divId = $tr.find('.extra-option').attr('id');
    $('#' + divId).css({
        top: offset.top,
        left: offset.left + $tr.outerWidth()
    }).show();
});

// Mostra il div extra quando il mouse entra in .icon-del
$('.icon-del').on('mouseenter', function() {
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

// Gestisci la conferma o l'annullamento dell'eliminazione
$('#confirmButton').on('click', function() {
    jQuery.ajax({
      type: 'POST',
      url: "del_pro.php",
      dataType: 'json',
      data: {
        'id': prendiId,
      }
    });
    location.reload();
    modal.style.display = "none";
});

$('#cancelButton').on('click', function() {
    modal.style.display = "none";
});
            
            
</script>
<script  src="script.js"></script>
</html>