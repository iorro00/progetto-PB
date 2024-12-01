<!DOCTYPE html>
<html>
<head>
    <title>Pagina amministratore</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Orbitron">
    <link href='https://fonts.googleapis.com/css?family=Merriweather Sans' rel='stylesheet'>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id='box3'>
        <img src='img/logo.png' alt='Immagine non trovata'>
        <h1><a id='pb-link' href='ins_visua_project.php'>PB</a></h1>
    </div>

    <div id='box2'>
        <p>Bentornato/a <?php echo $_SESSION["nominativo"]?></p>
        <button class='ButtGenerale' onclick="modProf()">Modificare elenco professori</button>
        <button class='ButtGenerale' onclick="modClassi()">Modificare elenco classi</button>
        <button class='ButtGenerale' onclick="window.location.href='rendicontazione.php'">Modificare progetti</button>
    </div>

    <div class="colonna" id="prof" style="display:none;">
        <p>Modifica elenco docenti</p>
        <button onclick="mostraFormAggiungiProf()">Aggiungi professore</button>

        <div id="formAggiungiProf" style="display:none;">
            <input type="text" id="nomeProf" placeholder="Nome">
            <input type="text" id="cognomeProf" placeholder="Cognome">
            <button onclick="aggiungiProf()">Aggiungi</button>
        </div>
        <div id="listaProfessori">
            <?php
            require_once("db.php");

            $table = "<table class='tbVisua' style='width:100%'>
                        <tr>
                            <th>ID</th>
                            <th>Nominativo</th>
                            <th></th>
                        </tr>";

            $stm = $conn->prepare("SELECT id, nominativo FROM docenteReferente");
            $stm->execute();
            $result = $stm->fetchAll();

            foreach($result as $row) {
                $table .= "<tr>
                            <td>" . $row["id"] . "</td>
                            <td contenteditable='true' data-id='" . $row["id"] . "' class='editable'>" . $row["nominativo"] . "</td>
                            <td class='icon-del'><div style='display:none;' onclick='eliminaProf(" . $row["id"] . ")' class='extra-option' id='extraOption-".$row["id"]."'><img src='img/delete.png' class='deleteIcon'></div></td>
                        </tr>";
            }
            $table .= "</table>";
            echo $table;
            ?>
        </div>
    </div>

    <div class="colonna" id="classi" style="display:none;">
        <p>Modifica elenco classi</p>
        <button onclick="mostraFormAggiungiClasse()">Aggiungi classe</button>

        <div id="formAggiungiClasse" style="display:none;">
            <input type="number" id="annoClasse" placeholder="Anno Classe">
            <input type="text" id="sezioneClasse" placeholder="Sezione">
            <input type="text" id="indirizzoClasse" placeholder="Indirizzo">
            <button onclick="aggiungiClasse()">Aggiungi</button>
        </div>

        <div id="listaClassi">
            <?php
            require_once("db.php");

            $table2 = "<table class='tbVisua' style='width:100%'>
                        <tr>
                            <th>ID</th>
                            <th>Classe</th>
                            <th>Indirizzo</th>
                            <th></th>
                        </tr>";

            $stm = $conn->prepare("SELECT classi.id, classi.anno_classe, classi.sezione, indirizzi.descrizione FROM classi INNER JOIN indirizzi ON indirizzi.id = classi.fk_indirizzo");
            $stm->execute();
            $result = $stm->fetchAll();

            foreach($result as $row) {
                $table2 .= "<tr>
                            <td>" . $row["id"] . "</td>
                            <td contenteditable='true' data-id='" . $row["id"] . "' class='editable'>" . $row["anno_classe"] . $row["sezione"] . "</td>
                            <td contenteditable='true' data-id='" . $row["id"] . "' class='editable'>" . $row["descrizione"] . "</td>
                            <td class='icon-del'><div style='display:none;' onclick='eliminaClasse(" . $row["id"] . ")' class='extra-option' id='extraOption-".$row["id"]."'><img src='img/delete.png' class='deleteIcon'></div></td>
                        </tr>";
            }
            $table2 .= "</table>";
            echo $table2;
            ?>
        </div>
    </div>

    <button onclick="tornaIndietro();" id='reset' style="display:none;">indietro</button>
    <p id='exit-link'><b><a id='exit-link' href='login.php' onclick="logout()">Ritorna all'accesso</a></b></p>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script.js"> </script>
<script>
const page1 = document.getElementById('box2');
const prof = document.getElementById('prof');
const classi = document.getElementById('classi');
const indietro = document.getElementById('reset');
const accesso = document.getElementById('exit-link');

function modProf(){
    page1.style.display="none";
    classi.style.display="none";
    prof.style.display="flex";
    indietro.style.display="flex";
    accesso.style.display="none";
}

function modClassi(){
    page1.style.display="none";
    classi.style.display="flex";
    prof.style.display="none";
    indietro.style.display="flex";
    accesso.style.display="none";
}

function tornaIndietro(){
    page1.style.display="flex";
    classi.style.display="none";
    prof.style.display="none";
    indietro.style.display="none";
    accesso.style.display="flex";
}

function mostraFormAggiungiProf() {
    document.getElementById('formAggiungiProf').style.display = 'flex';
}

function aggiungiProf() {
    var nome = document.getElementById('nomeProf').value;
    var cognome = document.getElementById('cognomeProf').value;
    var nominativo = cognome + ' ' + nome;

    $.ajax({
        type: "POST",
        url: "aggiungi_prof.php",
        data: { "nominativo": nominativo },
        success: function(response) {
            location.reload();
        }
    });
}

function mostraFormAggiungiClasse() {
    document.getElementById('formAggiungiClasse').style.display = 'flex';
}

function aggiungiClasse() {
    var anno = document.getElementById('annoClasse').value;
    var sezione = document.getElementById('sezioneClasse').value;
    var indirizzo = document.getElementById('indirizzoClasse').value;

    $.ajax({
        type: "POST",
        url: "aggiungi_classe.php",
        data: { "anno": anno, "sezione": sezione, "indirizzo": indirizzo },
        success: function(response) {
            location.reload();
        }
    });
}

function eliminaProf(id) {
    $.ajax({
        type: "POST",
        url: "elimina_prof.php",
        data: { "id": id },
        success: function(response) {
        	console.log(response)
            location.reload();
        }
    });
}

function eliminaClasse(id) {
    $.ajax({
        type: "POST",
        url: "elimina_classe.php",
        data: { "id": id },
        success: function(response) {
            location.reload();
        }
    });
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
$('.icon-del').on('mouseleave', function() {
    var divId = $(this).attr('prendi-id');
    $('#' + divId).hide();
});
</script>
</html>