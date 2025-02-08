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
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina amministratore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
       /* Evitare che gli stili interni influenzino gli elementi con classe .colonna */
      
       .main-container img:hover {
            transform: scale(1.05);
        }
        .title {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }
       
        
    

      .main-container {
          background: white;
          padding: 40px;
          border-radius: 20px;
          box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
          max-width: 600px;
          text-align: center;
      }
        
       .btn-action {
            background-color: #00245d;
            color: white;
            border: none;
            margin: 12px 0;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: 'Source Sans Pro', sans-serif;
            transition: all 0.3s ease-in-out;
            width: 100%;
            letter-spacing: 0.3px;
        }
        
       .btn-action:hover {
            background-color: #0b5ed7;
            box-shadow: 0 8px 15px rgba(13, 110, 253, 0.2);
            transform: translateY(-2px);
        }
        

       .back-link {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            margin-top: 80px;
            color: #2c3e50;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            position: relative;
            transition: color 0.3s ease;
        }
        
        .back-link::after {
            content: '';
            position: absolute;
            width: 100%;
            transform: scaleX(0);
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #0d6efd;
            transform-origin: bottom right;
            transition: transform 0.3s ease-out;
        }
       .back-link:hover {
            color: #0d6efd;
        }
        
        .back-link:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }
        
       
       @media (max-width: 576px) {
            .main-container {
                margin: 1rem;
                padding: 1rem;
            }
        }
	.btn-action {
                font-size: 1rem;
                padding: 12px 20px;
          }
          
          .top-bar {
    background-color: #00245d;
    color: white;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 80px; /* Altezza aumentata */
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

.top-bar .logo {
    width: 60px; /* Logo più grande */
    height: 50px;
    
    object-fit: cover;
    margin-left: auto; /* Sposta il logo sulla destra */
}

.top-bar p {
    font-size: 1.5rem; /* Testo del titolo più grande */
    font-weight: bold;
    margin: 0;
    text-align: center;
    flex-grow: 1;
}
       
    </style>
</head>
<body>

    
<div class="container d-flex align-items-center justify-content-center min-vh-100" id="box1">
    <div class="main-container" id="box2">
        <img src="img/logo.png" alt="Logo">
        <h2 class="title">Bentornato/a <?php echo $_SESSION["nominativo"]?></h2>
        <button class="btn-action" onclick="modProf()">Modificare elenco professori</button>
        <button class="btn-action" onclick="modClassi()">Modificare elenco classi</button>
        <button class="btn-action" onclick="window.location.href='rendicontazione.php'">Modificare progetti</button>
        <a href="login.php" class="logout back-link" id="exit-link">Ritorna all'accesso</a>
    </div>
</div>
    

    <div class="colonna" id="prof" style="display:none;">
        <div id="top-bar" class="top-bar d-flex align-items-center p-3" style="display: none;">
            <button id="back-btn" class="btn btn-light me-3" onclick="tornaIndietro()">←</button>
            <p id="page-title" class="m-0 mx-auto text-white">MODIFICA ELENCO DOCENTI</p>
            <img src="img/logo.png" alt="Logo" class="logo">
        </div>
        <br><br><br><br><br>  
            <p id='miniTitDoc'>Aggiungi docente</p>

            <?php
            require_once("db.php");

            $table = "<table class='tbVisua'>
                        <tr>
                            <th style='display:none;'></th>
                            <th>Cognome       Nome</th>
                            <th></th>
                        </tr>";

            $stm = $conn->prepare("SELECT id, nominativo FROM docenteReferente");
            $stm->execute();
            $result = $stm->fetchAll();

            foreach($result as $row) {
                $table .= "<tr>
                            <td style='display:none;' >" . $row["id"] . "</td>
                            <td>" . $row["nominativo"] . "</td>
        					<td class='icon-del'><div prendi-id='" . $row["id"] . "' contenuto='docente' style='display:none;' class='extra-option' id='extraOption-".$row["id"]."'><img src='img/delete.png' class='deleteIcon'></div></td>
                        </tr>";
            }
            $table .= "</table>";
            echo $table;
        ?>
        <br><br>
    </div>

    <div class="colonna" id="classi" style="display:none;">
        <div id="top-bar" class="top-bar d-flex align-items-center p-3" style="display: none;">
                <button id="back-btn" class="btn btn-light me-3" onclick="tornaIndietro()">←</button>
                <p id="page-title" class="m-0 mx-auto text-white">MODIFICA ELENCO CLASSI</p>
                <img src="img/logo.png" alt="Logo" class="logo">
        </div>
        <br><br><br><br><br>
            
        <p id='miniTitClass'>Aggiungi classe</p>

        <?php
            require_once("db.php");

            $table2 = "<table class='tbVisua'>
                        <tr>
                            <th style='display:none;'></th>
                            <th>Classe</th>
                            <th>Indirizzo</th>
                            <th></th>
                        </tr>";

            $stm = $conn->prepare("SELECT classi.id, classi.anno_classe, classi.sezione, indirizzi.descrizione FROM classi INNER JOIN indirizzi ON indirizzi.id = classi.fk_indirizzo");
            $stm->execute();
            $result = $stm->fetchAll();

            foreach($result as $row) {
                $table2 .= "<tr>
                            <td style='display:none;' >" . $row["id"] . "</td>
                            <td>" . $row["anno_classe"] . $row["sezione"] . "</td>
                            <td>" . $row["descrizione"] . "</td>
        					<td class='icon-del'><div prendi-id='" . $row["id"] . "' contenuto='classe' style='display:none;' class='extra-option' id='extraOption-". $row["anno_classe"]."". $row["sezione"]."'><img src='img/delete.png' class='deleteIcon'></div></td>
                        	</tr>";
            }
            $table2 .= "</table>";
            echo $table2;
        ?>
        <br><br>
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
    <div id="buttInd"><button onclick=tornaIndietro(); id='reset' style="display:none;" disabled>indietro</button></div>	
</body>

    <script src="script.js"></script>
<script>
    const page0 = document.getElementById('box1');
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
        // Hide all other sections and buttons
        page0.classList.remove('container', 'd-flex', 'align-items-center', 'justify-content-center', 'min-vh-100');
        page0.style.display = "none";
        page1.style.display = "none";
        classi.style.display = "none";
        accesso.style.display = "none";
        
        // Show professor section and back button
        indietro.style.display = "none";
        prof.style.display = "flex";
        
    }

    function modClassi(){
        page0.classList.remove('container', 'd-flex', 'align-items-center', 'justify-content-center', 'min-vh-100');
        indietro.style.display="none";
        accesso.style.display="none";
        page0.style.display="none";
        page1.style.display="none";
        classi.style.display="flex";
        prof.style.display="none";
    }

    function tornaIndietro(){
        page0.classList.add('container', 'd-flex', 'align-items-center', 'justify-content-center', 'min-vh-100');
        indietro.style.display="none";
        accesso.style.display="block";
        page0.style.display="flex";
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
    $('.aggiungi-content').append('<span id="closer">&times;</span><p class="addDoc">Aggiungi docente</p> <input type="text" id="newNomin" name="newNomin" placeholder="forma: COGNOME NOME"><input type="button" value="Salva" id="submitModifiche" onclick="addDoc();">');
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