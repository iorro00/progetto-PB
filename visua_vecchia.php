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
                row.addEventListener('click', function() {
                    var projectId = row.cells[0].innerHTML; // L'ID del progetto è nella prima cella
                	document.getElementById('projectIdTaken').value = projectId; // Imposta l'ID del progetto nel campo nascosto
            		document.getElementById('projectForm').submit(); // Invia il modulo
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
        <button id="butt-filtri" onclick="showPopup()" >Filtri</button>
    </div>


    <?php
        require_once("db.php");

        $table = "<table id='tbVisua'>
                    <tr>
                    	<th style= display:none;></th>
                        <th>Titolo Progetto</th>
                        <th>Dipartimento</th>
                        <th>Docente Referente</th>
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
                    </tr>";
        }
        $table .= "</table>";
        echo $table;
    ?>
    <form id="projectForm" action="dettaglio_progetto.php" method="POST" style="display:none;">
    	<input type="hidden" name="id" id="projectIdTaken">
	</form>

    <div id="popupOverlay">
        <div id="popupContainer">
            <div class="colonna" id="colonna2">
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
            </div>
        </div>
    </div>

    <p id='exit-link'><b><a id='exit-link' href='ins_visua_project.php'>Ritorna alla home</a></b></p>
</body>
<script src="script.js"> </script>
</html>