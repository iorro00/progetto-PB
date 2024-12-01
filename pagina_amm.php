<?php
    ini_set('session.gc_maxlifetime', 7200);
    session_set_cookie_params(7200);
    session_start();
    
	// Verifico se l'utente ha efettuato l'accesso oppure no
    if (!isset($_SESSION['user_email'])) {
        header("Location: login.php");
        exit;
    }

    //Verifico ulteriormente che l'utente sia l'amministratore
    if($_SESSION["nominativo"] != "Amministratore")
    {
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pagina amministratore</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Orbitron">
        <link href='https://fonts.googleapis.com/css?family=Merriweather Sans' rel='stylesheet'>
        <link href="style.css" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </head>
<body>

    <div id='box3'>
        <img src='img/logo.png' alt='Immagine non trovata'>
        <h1><a id='pb-link' href='ins_visua_project.php'>PB</a></h1>
    </div>

    <div id='box2'>

        <p>Bentornato/a <?php echo $_SESSION["nominativo"]?></p>

        <button class='ButtGenerale' onclick=modProf()>Modificare elenco professori</button>
        <button class='ButtGenerale' onclick=modClassi()>Modificare elenco classi</button>
        <button class='ButtGenerale' onclick="window.location.href='rendicontazione.php'">Modificare progetti</button>

    </div>

    <div class="colonna" id="prof" style="display:none;">
            <p>Modifica elenco docenti</p>
            <p id='miniTitDoc'>Aggiungi docente</p>

            <?php
            require_once("db.php");

            $table = "<table class='tbVisua'>
                        <tr>
                            <th style= display:none;></th>
                            <th>Cognome       Nome</th>
                            <th></th>
                        </tr>";

            $stm = $conn->prepare("SELECT id, nominativo FROM docenteReferente");
            $stm->execute();
            $result = $stm->fetchAll();

            foreach($result as $row) {
                $table .= "<tr>
                            <td style= display:none; >" . $row["id"] . "</td>
                            <td>" . $row["nominativo"] . "</td>
        					<td class='icon-del'><div prendi-id='" . $row["id"] . "' contenuto='docente' style='display:none;' class='extra-option' id='extraOption-".$row["id"]."'><img src='img/delete.png' class='deleteIcon'></div></td>
                        </tr>";
            }
            $table .= "</table>";
            echo $table;
        ?>
    </div>

    <div class="colonna" id="classi" style="display:none;">
        <p>Modifica elenco classi</p>
        <p id='miniTitClass'>Aggiungi classe</p>

        <?php
            require_once("db.php");

            $table2 = "<table class='tbVisua'>
                        <tr>
                            <th style= display:none;></th>
                            <th>Classe</th>
                            <th>Indirizzo</th>
                            <th></th>
                        </tr>";

            $stm = $conn->prepare("SELECT classi.id, classi.anno_classe, classi.sezione, indirizzi.descrizione FROM classi INNER JOIN indirizzi ON indirizzi.id = classi.fk_indirizzo");
            $stm->execute();
            $result = $stm->fetchAll();

            foreach($result as $row) {
                $table2 .= "<tr>
                            <td style= display:none; >" . $row["id"] . "</td>
                            <td>" . $row["anno_classe"] . $row["sezione"] . "</td>
                            <td>" . $row["descrizione"] . "</td>
        					<td class='icon-del'><div prendi-id='" . $row["id"] . "' contenuto='classe' style='display:none;' class='extra-option' id='extraOption-". $row["anno_classe"]."". $row["sezione"]."'><img src='img/delete.png' class='deleteIcon'></div></td>
                        	</tr>";
            }
            $table2 .= "</table>";
            echo $table2;
        ?>
    </div>
    
    <div id="confermaElim" style="display:none;">
          <div class="confermaElim-content">
              <p>Vuoi procedere all'eliminazione?</p>
              <button id="confirmButton" class="conf-button">Conferma</button>
              <button id="cancelButton" class="conf-button">Annulla</button>
          </div>
    </div>
    
    <div id="aggiungi" style="display:none;">
        <div class="aggiungi-content">
        
        </div>
    </div>
    
    <div id="aggiungi-classe" style="display:none;">
        <div class="aggiungi-content-classe">
        
        </div>
    </div>

    <div id="buttInd"><button onclick=tornaIndietro(); id='reset' style="display:none;">indietro</button></div>

    <p id='exit-link'><b><a id='exit-link' href='login.php' onclick="logout()">Ritorna all'accesso</a></b></p>
    
</body>
<script src="script.js"> </script>
<script>
    const page1 = document.getElementById('box2');
    const prof = document.getElementById('prof');
    const classi = document.getElementById('classi');
    const indietro = document.getElementById('reset');
    const accesso = document.getElementById('exit-link');
    var modal = document.getElementById("confermaElim");
    var boxAdd = document.getElementById("aggiungi");
    var prendiId;
    var prendiCampo;
    
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

    function modProf(){
        indietro.style.display="inline-block";
        accesso.style.display="none";
        page1.style.display="none";
        classi.style.display="none";
        prof.style.display="flex";
    }

    function modClassi(){
        indietro.style.display="inline-block";
        accesso.style.display="none";
        page1.style.display="none";
        classi.style.display="flex";
        prof.style.display="none";
    }

    function tornaIndietro(){
        indietro.style.display="none";
        accesso.style.display="block";
        page1.style.display="flex";
        classi.style.display="none";
        prof.style.display="none";
    }
    

// Mostra il div extra quando il mouse entra nella riga della tabella
$('table').on('mouseenter', 'tr:not(:first-child)', function() {
    var $tr = $(this);
    var offset = $tr.offset();
    var trWidth = $tr.outerWidth();
    var divId = $tr.find('.extra-option').attr('id');
    $('#' + divId).css({
        top: offset.top,
        left: offset.left + $tr.outerWidth()
    }).show();
});

$('table').on('mouseleave', 'tr:not(:first-child)', function() {
    var $tr = $(this);
    // Nascondi il div con classe 'extra-option'
    var extraOptionId = $tr.find('.extra-option').attr('id');
    $('#' + extraOptionId).hide();
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
        prendiCampo = this.getAttribute('contenuto');
        console.log(prendiCampo);
        modal.style.display = "inline-block";
    });
});

$('#confirmButton').on('click', function() {
	if(prendiCampo=='docente')
    {
      jQuery.ajax({
        type: 'POST',
        url: " elimina_prof.php",
        dataType: 'json',
        data: {
            'id': prendiId,
        },
        success: function(response) {
            modal.style.display = "none";
            location.reload();    
         }
      });
    }
    else
    {
      jQuery.ajax({
        type: 'POST',
        url: " elimina_classe.php",
        dataType: 'json',
        data: {
            'id': prendiId,
        },
        success: function(response) {
            modal.style.display = "none";
            location.reload();    
         }
      });
    } 
});

$('#cancelButton').on('click', function() {
    modal.style.display = "none";
});

$(document).on('click', '#closer', function() {
    enableScroll();
    $('#aggiungi').hide();
    $('#aggiungi-classe').hide();
});

$('#miniTitDoc').on('click', function(){
    $('#aggiungi').show();
    disableScroll();
    $('.aggiungi-content').empty(); // Pulisci il contenuto precedente
    $('.aggiungi-content').append('<span id="closer">&times;</span><p class="addDoc">Aggiungi docente</p> <input type="text" id="newNomin" name="newNomin"><input type="button" value="Salva" id="submitModifiche" onclick="addDoc();">');
});

$('#miniTitClass').on('click', function(){
    $('#aggiungi-classe').show();
    disableScroll();
    $('.aggiungi-content-classe').empty(); // Pulisci il contenuto precedente
    $('.aggiungi-content-classe').append(`<span id="closer">&times;</span><p class="addClass">Aggiungi classe</p>
    <div class="form-group">
    <label for="newAnnata">Anno:</label>
        <select id="newAnnata" name="newAnnata">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    
        <label for="newSezione">Sezione:</label>
        <select id="newSezione" name="newSezione">
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="E">E</option>
            <option value="F">F</option>
            <option value="G">G</option>
            <option value="H">H</option>
            <option value="I">I</option>
            <option value="J">J</option>
            <option value="K">K</option>
            <option value="L">L</option>
            <option value="M">M</option>
            <option value="N">N</option>
            <option value="O">O</option>
            <option value="P">P</option>
            <option value="Q">Q</option>
            <option value="R">R</option>
            <option value="S">S</option>
            <option value="T">T</option>
            <option value="U">U</option>
            <option value="V">V</option>
            <option value="W">W</option>
            <option value="X">X</option>
            <option value="Y">Y</option>
            <option value="Z">Z</option>
        </select>
    
        <label for="newIndirizzo">Indirizzo:</label>
        <select id="newIndirizzo" name="newIndirizzo">
            <option value="1">Informatica</option>
            <option value="4">Scienze Applicate</option>
            <option value="2">Relazioni Internazionali</option>
            <option value="3">Grafico</option>
        </select>
     </div>
<input type="button" value="Salva" id="submitModifiche" onclick="addClasse();">
    `);
});

function addDoc(){
	var nomin = $("#newNomin").val();
	if (nomin.trim()) {
    	jQuery.ajax({
          type: 'POST',
          url: "aggiungi_prof.php",
          dataType: 'json',
          data: {
            'nominativo': nomin,
          },
          success: function(response) {                        
              if (response.success) {
                  location.reload();
              } else {
                  console.error("Errore: " + response.message);
              }
          }
    	});
    }
    enableScroll()
	$('#aggiungi').hide();

}

function addClasse(){
	var anno = $("#newAnnata").val();
    var sezione = $("#newSezione").val();
    var indirizzo = $("#newIndirizzo").val();
	if ((anno.trim())&&(sezione.trim())&&(indirizzo.trim())) {
    	jQuery.ajax({
          type: 'POST',
          url: "aggiungi_classe.php",
          dataType: 'json',
          data: {
            'anno': anno,
            'sezione': sezione,
            'indirizzo': indirizzo,
          },
          success: function(response) {                        
              if (response.success) {
                  location.reload();
              } else {
                  console.error("Errore: " + response.message);
              }
          }
    	});
    }
    enableScroll()
	$('#aggiungi').hide();

}


</script>
</html>