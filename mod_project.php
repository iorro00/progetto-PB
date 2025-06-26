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
        <title>Modifica Progetto</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Orbitron">
        <link href='https://fonts.googleapis.com/css?family=Merriweather Sans' rel='stylesheet'>
        <link href="style.css" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
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
            
            .top-bar p {
                font-size: 1.5rem;
                font-weight: bold;
                margin: 0;
                text-align: center;
                flex-grow: 1;
            }

            .top-bar .logo {
                width: 60px; /* Logo più grande */
                height: 50px;
                
                object-fit: cover;
                margin-left: auto; /* Sposta il logo sulla destra */
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
            td{
                background-color: #fdfdff;
            }

            .modifica-campo-content,
            .add-ris-content,
            .modifica-campo-classi-content,
            .confermaElim-content {
                background: #fff;
                border-radius: 1rem;
                box-shadow: 0 0 24px 0 #00245d40;
                padding: 2rem 1.5rem 1.5rem 1.5rem;
                max-width: 550px;
                margin: 40px auto;
                position: relative;
                min-height: 180px;
                /* effetto fade-in */
                animation: fadeInPopup 0.25s;
            }

            @keyframes fadeInPopup {
                from { opacity: 0; transform: scale(0.96);}
                to { opacity: 1; transform: scale(1);}
            }

            #modifica-campo,
            #add-ris,
            #modifica-campo-classi,
            #confermaElim,
            #confermaElim2 {
                background: rgba(0,34,93,0.16); /* leggermente blu, più elegante */
                position: fixed !important;
                top: 0; left: 0;
                width: 100vw;
                height: 100vh;
                z-index: 2000;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: opacity 0.18s;
            }

        </style>
    </head>
<body>
	
    <?php
    require_once("db.php");
        if(isset($_POST['id']) && !empty($_POST['id'])) {
        $projectId = $_POST['id'];
            
        // Esegui la query per recuperare i dettagli del progetto utilizzando l'ID
        $stm = $conn->prepare("SELECT * FROM progetti WHERE id = :id");
        $stm->bindParam(':id', $projectId, PDO::PARAM_INT);
        $stm->execute();

        $project = $stm->fetchAll(); // Restituisce la riga corrente come array associativo
    	
        $tabGenerale = "<table id='tbVisuaDetails'><tr>";
        $tabRiferimenti = "<table id='tbVisuaDetails'><tr>";
        $tabAspettiDid = "<table id='tbVisuaDetails'><tr>";
        $tabDestinatari = "<table id='tbVisuaDetails'><tr>";
        $tabCompetenze = "<table id='tbVisuaDetails'><tr>";
   		$tabRisorseInt = "<table id='tbVisuaDetails'><tbody><tr><td><b>Nominativo</b></td><td><b>Ore Curricolari</b></td><td><b>Ore Extra-Curricolari</b></td><td><b>Ore Sorveglianza</b></td><td><b>Ore Progettazione</b></td><td><b>Area Competenza</b></td><td><b>Ruolo</b></td><td></td></tr>";
   		$tabRisorseExt = "<table id='tbVisuaDetails'><tbody><tr><td><b>Nominativo</b></td><td><b>Ore Docenza</b></td><td><b>Costo Previsto</b></td><td><b>Costi eventuali aggiuntivi</b></td><td></td></tr>";
        
        // Stampiamo dinamicamente tutti gli attributi del progetto
        foreach($project as $value) {
            echo "<div class='top-bar d-flex align-items-center p-3'>";
            echo "<button class='btn btn-light me-3' id='annullaModifiche'>←</button>";
            echo "<p class='m-0 mx-auto text-white' id='title' data-id='".$projectId."'><b>Modifica  ". $value["titolo"]."</b></p>";
            echo "<img src='img/logo.png' alt='Logo' class='logo'>";
            echo "</div>";
            echo "<br><br><br><br>";

            echo '<div class="container position-relative pt-4">';
            echo '    <!-- Info Circle with Tooltip -->';
            echo '    <div class="info-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Clicca un qualsiasi campo per cambiarlo">';
            echo '        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#00245d" class="bi bi-info-circle-fill" viewBox="0 0 16 16">';
            echo '            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>';
            echo '        </svg>';
            echo '    </div>';
            echo '</div>';


            $tabGenerale .= "<th>Titolo:</th><td class='mod_campo' id='titolo'>". $value["titolo"]."</td></tr>";
            $stmRef = $conn->prepare("SELECT nominativo FROM docenteReferente WHERE id = :id");
            $stmRef->bindParam(':id', $value["fk_docenteReferente"], PDO::PARAM_INT);
            $stmRef->execute();

        	$docRef = $stmRef->fetchAll();
            foreach($docRef as $doc){
            	$tabGenerale .= "<th>Docente referente:</th><td class='mod_campo' id='docRef'>".$doc["nominativo"]."</td></tr>";
            }

            $stmDip = $conn->prepare("SELECT nome FROM dipartimento WHERE id = :id");
            $stmDip->bindParam(':id', $value["fk_dipartimento"], PDO::PARAM_INT);
        	$stmDip->execute();
        	$dipart = $stmDip->fetchAll();
            foreach($dipart as $dip){
            	$tabGenerale .= "<tr><th>Dipartimento:</th><td class='mod_campo' id='dip'>".$dip["nome"]."</td>";
            }
            
            $tabRiferimenti .= "<th>Strutturale:</th><td class='mod_campo' id='strutt'>".$value["strutturale"]."</td></tr>";
            $tabRiferimenti .= "<th>Ore di orientamento:</th><td class='mod_campo' id='orient'>".$value["orientamento"]."</td></tr>";
            $tabRiferimenti .= "<th>Percorso di PCTO:</th><td class='mod_campo' id='pcto'>".$value["PCTO"]."</td></tr>";
            
            $tabAspettiDid .= "<th>Analisi del contesto:</th><td class='mod_campo' id='contesto'>".$value["analisi_contesto"]."</td></tr>";
            $tabAspettiDid .= "<th>Obbiettivi attesi:</th><td class='mod_campo' id='obb'>".$value["obbiettivi_attesi"]."</td></tr>";
            $tabAspettiDid .= "<th>Attività previste:</th><td class='mod_campo' id='attiv'>".$value["attivita_previste"]."</td></tr>";
            $tabAspettiDid .= "<th>Metodologia e strumenti:</th><td class='mod_campo' id='strum'>".$value["metodologia_e_strumenti"]."</td></tr>";
            $tabAspettiDid .= "<th>Tempi di svolgimento:</th><td class='mod_campo' id='tempi'>".$value["tempi_svolgimento"]."</td></tr>";
            $tabAspettiDid .= "<th>Luoghi di svolgimento:</th><td class='mod_campo' id='luoghi'>".$value["luoghi_svolgimento"]."</td></tr>";
            $tabAspettiDid .= "<th>Modalità di verifica in itinere e finale:</th><td class='mod_campo' id='finale'>".$value["verifica_itinere_e_finale"]."</td></tr>";
            $tabAspettiDid .= "<th>Documentazione:</th><td class='mod_campo' id='docum'>".$value["documentazione"]."</td></tr>";
            
            $stmClassi = $conn->prepare("SELECT c.anno_classe, c.sezione FROM progetti_classi p, classi c WHERE p.fk_progetto = :id AND p.fk_classe = c.id");
            $stmClassi->bindParam(':id', $value["id"], PDO::PARAM_INT);
            $stmClassi->execute();

        	$classi = $stmClassi->fetchAll();
            $tabDestinatari .= "<tr><th>Classi destinatarie del progetto:</th><td class='mod_campo' id='classi'>";
            $cont = 0;
            foreach($classi as $cla){
              $cont++;
              $tabDestinatari .= $cla["anno_classe"].$cla["sezione"]." - ";	
              if($cont == 4){
                $tabDestinatari .= "<br>";
                $cont = 0;
              }
            }
            $tabDestinatari = rtrim($tabDestinatari, "<br>");
            $tabDestinatari = substr($tabDestinatari, 0, -2);
        	$tabDestinatari .= "</td></tr>";
            
            $stmOreClassi = $conn->prepare("SELECT p.ore_pomeriggio, p.ore_mattina FROM progetti_classi p WHERE p.fk_progetto = :id");
            $stmOreClassi->bindParam(':id', $value["id"], PDO::PARAM_INT);
            $stmOreClassi->execute();

        	$oreClassi = $stmOreClassi->fetchAll();
            foreach($oreClassi as $cla){
            	$oreMatt = $cla["ore_mattina"];
                $orePom = $cla["ore_pomeriggio"];
            }
            $tabDestinatari .="<tr><th>Ore mattina:</th><td class='mod_campo' id='oreMatt'>".$oreMatt."</td></tr><tr><th>Ore pomeriggio:</th><td class='mod_campo' id='orePom'>".$orePom."</td></tr>";
            $stmComp = $conn->prepare("SELECT c.descrizione FROM progetti_competenze p, competenze c WHERE p.fk_progetti = :id AND p.fk_competenze = c.id");
        	$stmComp->bindParam(':id', $value["id"], PDO::PARAM_INT);
        	$stmComp->execute();
        	$competenze = $stmComp->fetchAll();
            $contaComp = 0;
              foreach($competenze as $cmp){
              	$contaComp++;
				$tabCompetenze .= "<tr><th>Competenza ".$contaComp.":</th><td class='mod_campo' id='comp".$contaComp."'>".$cmp["descrizione"]."</td>";
              }
              
            $stmRisInt= $conn->prepare("SELECT r.id, r.nominativo, r.oreCurricolari, r.oreExtraCurricolari, r.oreSorveglianza, r.oreProgettazione FROM risorseInterne r, progetti_risorse p WHERE p.fk_progetti = :id AND p.fk_risorsaInterna = r.id GROUP BY r.id, r.nominativo");
            $stmRisInt->bindParam(':id', $value["id"], PDO::PARAM_INT);
            $stmRisInt->execute();

        	$risInt = $stmRisInt->fetchAll();
            $conta = 0;
              foreach($risInt as $rsi){
              	$conta++;
				$tabRisorseInt .= "<tr><td class='mod_campo nomRisorsa' id='nomeRis".$conta."' id-risorsa='".$rsi["id"]."'>".$rsi["nominativo"]."</td><td class='mod_campo oreCurrRisorsa' id='oreCurrRis".$conta."' id-risorsa='".$rsi["id"]."'>".$rsi["oreCurricolari"]."</td><td class='mod_campo oreExtraCurrRisorsa' id='oreExtraCurrRis".$conta."' id-risorsa='".$rsi["id"]."'>".$rsi["oreExtraCurricolari"]."</td><td class='mod_campo oreSorvRisorsa' id='oreSorvRis".$conta."' id-risorsa='".$rsi["id"]."'>".$rsi["oreSorveglianza"]."</td><td class='mod_campo oreProgRisorsa' id='oreProgRis".$conta."' id-risorsa='".$rsi["id"]."'>".$rsi["oreProgettazione"]."</td>";
              	$stmAreaComp= $conn->prepare("SELECT DISTINCT p.areaCompetenza, p.ruolo FROM risorseInterne r, progetti_risorse p WHERE p.fk_risorsaInterna = :id");
              	$stmAreaComp->bindParam(':id', $rsi["id"], PDO::PARAM_INT);
        		$stmAreaComp->execute();
        		$areaComp = $stmAreaComp->fetchAll();
                $tabRisorseInt.= "<td class='mod_campo areaCompRisorsa' id='areaCompRis".$conta."' id-risorsa='".$rsi["id"]."'>";
                foreach($areaComp as $arc){
                	$tabRisorseInt .= $arc["areaCompetenza"].",";
                    $ruolo = $arc["ruolo"];
                }
                $tabRisorseInt = rtrim($tabRisorseInt, ", ");
                $tabRisorseInt.="</td><td class='mod_campo ruoloRisorsa' id='ruoloRis".$conta."' id-risorsa='".$rsi["id"]."'>".$ruolo."</td> <td class='icon-del'><div prendi-id='" . $rsi["id"] . "' prendi-ris='int' style='display:none;' class='extra-option delete' id='delete".$rsi["id"]."'><img src='img/delete.png' class='deleteIcon'></div></td></tr>";
              }
              
            $stmRisExt= $conn->prepare("SELECT r.id, r.nominativo, r.oreDocenza, r.costoPrevisto, r.costiEventuali FROM risorseEsterne r, progetti_risorse p WHERE p.fk_progetti = :id AND p.fk_risorsaEsterna = r.id");
            $stmRisExt->bindParam(':id', $value["id"], PDO::PARAM_INT);
            $stmRisExt->execute();

        	$risExt = $stmRisExt->fetchAll();
            $contaExt = 0;
              foreach($risExt as $rse){
              	$contaExt++;
				$tabRisorseExt .= "<tr><td class='mod_campo nomRisorsaExt' id='nomeRisExt".$contaExt."' id-risorsa='".$rse["id"]."'>".$rse["nominativo"]."</td><td class='mod_campo oreDocRisorsaExt' id='oreDocRisExt".$contaExt."' id-risorsa='".$rse["id"]."'>".$rse["oreDocenza"]."</td><td class='mod_campo costoRisorsaExt' id='costoRisExt".$contaExt."' id-risorsa='".$rse["id"]."'>".$rse["costoPrevisto"]."</td><td class='mod_campo costiEventualiRisorsaExt' id='costiEventualiRisExt".$contaExt."' id-risorsa='".$rse["id"]."'>".$rse["costiEventuali"]."</td> <td class='icon-del'><div prendi-id='" . $rse["id"] . "' prendi-ris='ext' style='display:none;' class='extra-option risorseExt' id='extraOption-".$rse["id"]."'><img src='img/delete.png' class='deleteIcon'></div></td></tr>";
              }
        }
        echo "<p class='subTit'>Informazioni generali</p>";
        $tabGenerale .= "</tr></table>";
        echo $tabGenerale;

        echo "<br>";
        echo "<p class='subTit'>Riferimenti al PTOF</p>";
        $tabRiferimenti .= "</tr></table>";
        echo $tabRiferimenti;

        echo "<br>";
        echo "<p class='subTit'>Aspetti didattici</p>";
        $tabAspettiDid .= "</tr></table>";
        echo $tabAspettiDid;
        echo "<br>";

        echo "<p class='subTit'>Destinatari del progetto</p>";
        
        $tabDestinatari .= "</table>";
        echo $tabDestinatari;

        echo "<br>";
        if($contaComp > 0){
          echo "<p class='subTit'>Competenze progetto</p>";
          $tabCompetenze .= "</tr></table>";
          echo $tabCompetenze;
        }
        echo "<br>";
        echo "<div id='contTit'><p class='subTit'>Risorse interne</p> <p id='miniTit'>Aggiungi risorsa</p></div>";
        $tabRisorseInt .= "</table>";
        echo $tabRisorseInt;
        echo "<br>";
        echo "<div id='contTit'><p class='subTit'>Risorse esterne</p> <p id='miniTit2'>Aggiungi risorsa</p></div>";
        $tabRisorseExt .= "</table>";
        echo $tabRisorseExt;
        
    } else {
        echo "<p>Progetto non trovato.</p>";
    }
     ?>  
     
    <div id="modifica-campo" style="display:none;">
        <div class="modifica-campo-content">
        
        </div>
    </div>
    
    <div id="add-ris" style="display:none;">
        <div class="add-ris-content">
        
        </div>
    </div>
    
    <div id="modifica-campo-classi" style="display:none;">
        <div class="modifica-campo-classi-content">
        
        </div>
    </div>
    
    <div id="confermaElim" style="display:none;">
          <div class="confermaElim-content">
              <p>Sei sicuro di eliminare questa risorsa?</p>
              <button id="confirmButton" class="conf-button">Conferma</button>
              <button id="cancelButton" class="conf-button">Annulla</button>
          </div>
    </div>
    
    <div id="confermaElim2" style="display:none;">
          <div class="confermaElim-content">
              <p>Sei sicuro di eliminare questa risorsa?</p>
              <button id="confirmButton2" class="conf-button">Conferma</button>
              <button id="cancelButton2" class="conf-button">Annulla</button>
          </div>
    </div>
    
    <br>
</body>

<script>
var element = document.getElementById('title');
var idProgetto = element.getAttribute('data-id'); // Legge l'attributo corretto
var modal = document.getElementById("confermaElim");
var modal2 = document.getElementById("confermaElim2");
var confirmButton = document.getElementById("confirmButton");
var confirmButton2 = document.getElementById("confirmButton2");
var cancelButton = document.getElementById("cancelButton");
var prendiId;

$(document).on('click', '#titolo', function(){
    $('#modifica-campo').show();
    disableScroll();
    $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
    $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica il titolo</p> <input type="text" id="newTitolo" name="newTitolo"><button class="btn btn-success mt-3" id="submitModifiche" onclick="prendiTitolo()">Salva</button>');
});

$(document).on('click', '#docRef', function(){
    $('#modifica-campo').show();
    $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
    disableScroll();
    jQuery.ajax({
        type: 'POST',
        url: "crea_datalist.php",
        dataType: 'json',
        success: function(response) {                        
            if (response.success) {
                // Prendi solo la risposta e assegnala a una variabile
                var risposta = response.risposta;

                // Aggiorna il contenuto dell'elemento con ID 'contenuto'
                $('.modifica-campo-content').append("<span id='closer'>&times;</span><p class='titMod'>Seleziona nuovo docente referente</p>"+risposta+"<input type='button' value='Salva' id='submitModifiche' onclick='prendiDocRef();'>");
            } else {
                console.error("Errore: " + response.message);
            }
        }
    });
});

$(document).on('click', '#dip', function(){
    $('#modifica-campo').show();
    disableScroll();
    $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
    $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica il dipartimento</p> <select id="newDip" name="newDip"><option value="" disabled selected></option><option value="Scienze">Scienze</option><option value="Informatica">Informatica</option><option value="Lingue">Lingue</option><option value="Arte">Arte</option><option value="Matematica">Matematica</option></select> <input type="button" value="Salva" id="submitModifiche" onclick="prendiDip();">');
});

$('#strutt, #orient, #pcto').on('click', function() {
        var id = $(this).attr('id');
        $('#modifica-campo').show();
        disableScroll();
        $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
        $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica riferimenti al PTOF</p><select id="newRif" name="newRif"><option value="" disabled selected></option><option value="Si">Sì</option><option value="No">No</option></select>   <input type="button" value="Salva" id="submitModifiche" onclick="prendiRif(\'' + id + '\');">');
});

$('#contesto, #obb, #attiv, #strum, #luoghi, #finale, #docum').on('click', function() {
        var id = $(this).attr('id');
        $('#modifica-campo').show();
        disableScroll();
        $('.modifica-campo-content').empty().append(
        	'<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button>' +
            '<p class="titMod">Modifica Aspetti didattici</p>' +
            '<textarea name="newAspDid" id="newAspDid" placeholder="Scrivi qui..."></textarea>' +
            '<input type="button" value="Salva" id="submitModifiche" onclick="prendiAspDid(\'' + id + '\');">'
        );
});

$('#tempi').on('click', function(){
    $('#modifica-campo').show();
    disableScroll();
    $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
	$('.modifica-campo-content').append(
        '<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button>' +
        '<p class="titMod">Modifica i tempi del progetto</p>'+
        '<table id="modTempi">'+
           '<tr>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Gennaio" name="mese[]"> Gennaio</label></td>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Febbraio" name="mese[]"> Febbraio</label></td>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Marzo" name="mese[]"> Marzo</label></td>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Aprile" name="mese[]"> Aprile</label></td>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Maggio" name="mese[]"> Maggio</label></td>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Giugno" name="mese[]"> Giugno</label></td>'+
            '</tr>'+
            '<tr>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Luglio" name="mese[]"> Luglio</label></td>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Agosto" name="mese[]"> Agosto</label></td>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Settembre" name="mese[]"> Settembre</label></td>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Ottobre" name="mese[]"> Ottobre</label></td>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Novembre" name="mese[]"> Novembre</label></td>'+
               '<td><label class="checkbox-mod-label"><input type="checkbox" value="Dicembre" name="mese[]"> Dicembre</label></td>'+
            '</tr>'+
        '</table>'+
        '<input type="button" value="Salva" id="submitModifiche" onclick="prendiMesi();">'
    );
});

$('#comp1, #comp2').on('click', function() {
    var id = $(this).attr('id');
    var elemento = document.querySelector("#title");
    var idProgetto = elemento.getAttribute("data-id");
    $('#modifica-campo').show();
    disableScroll();
    $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
    
    jQuery.ajax({
        type: 'POST',
        url: "crea_select.php",
        dataType: 'json',
        data: {
            'id': idProgetto,
        },
        success: function(response) {
            if (response.success) {
                // Prendi solo la risposta e assegnala a una variabile
                var risposta = response.risposta;
                $('.modifica-campo-content').append(
                    '<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button>' +
                    '<p class="titMod">Modifica competenza</p> ' + 
                    risposta +
                    '<input type="button" value="Salva" id="submitModifiche" onclick="prendiComp(\'' + id + '\', \'' + idProgetto + '\');">'
                );
            } else {
                console.error("Errore: " + response.message);
            }
        }
    });
});

function prendiComp(id, idProgetto) {
    var newComp = $("#newComp").val();
    var flag = false;
    var comp = "";
    
    if (newComp.trim()) {
        // Determina quale competenza stiamo modificando
        if (id === "comp2") {
            flag = true;
            comp = $("#comp2").text();
        } else if (id === "comp1") {
            flag = false;
            comp = $("#comp1").text();
        }
        
        // Se stiamo modificando una competenza esistente
        if (comp !== "") {
            if (comp !== newComp) {
                jQuery.ajax({
                    type: 'POST',
                    url: "upd_comp.php",
                    dataType: 'json',
                    data: {
                        'id': idProgetto,
                        'comp': newComp,
                        'compExist': comp,
                        'flag': flag,
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            console.error("Errore: " + response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Funzione chiamata in caso di errore
                        console.error('Error:', textStatus, errorThrown);
                        // Puoi gestire qui l'errore, ad esempio mostrando un messaggio all'utente
                        alert('Si è verificato un errore: ' + textStatus);
                    }
                });
            }
        } else {
            // Se stiamo aggiungendo una nuova competenza
            jQuery.ajax({
                type: 'POST',
                url: "upd_comp.php",
                dataType: 'json',
                data: {
                    'id': idProgetto,
                    'comp': newComp,
                    'flag': flag,
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        console.error("Errore: " + response.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Funzione chiamata in caso di errore
                    console.error('Error:', textStatus, errorThrown);
                    // Puoi gestire qui l'errore, ad esempio mostrando un messaggio all'utente
                    alert('Si è verificato un errore: ' + textStatus);
                }
            });
        }
    }
    
    enableScroll();
    $('#modifica-campo').hide();
}

$('#oreMatt, #orePom').on('click', function(){
	var id = $(this).attr('id');
    var valueOra = $(this).text(); 
    $('#modifica-campo').show();
    disableScroll();
    $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
    if(id=="oreMatt")$('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica le ore mattutine</p> <input type="number" id="neworeMatt" name="newOreMatt" min="0" value="'+valueOra+'">  <input type="button" value="Salva" id="submitModifiche" onclick="prendiOre(\'' + id + '\');">');
	else $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica le ore pomeridiane</p> <input type="number" id="neworePom" name="newOrePom" min="0" value="'+valueOra+'"> <input type="button" value="Salva" id="submitModifiche" onclick="prendiOre(\'' + id + '\');">');
});

$('#classi').on('click', function(){
    $('#modifica-campo-classi').show();
    disableScroll();
    $('.modifica-campo-classi-content').empty(); // Pulisci il contenuto precedente
    const tbSceltaClassi = `
<div id="indirizzo2" >
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
<br>

<div id="annata2">
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
<br>
<p class="subTit">Classi filtrate</p>
    <div id="classi-selezionate" class='tbClassi'>
    	
    </div>
    <br>

    <p id="selectAll" onclick="selezionaCheckbox()" >Seleziona tutti</p>
    <p id="deselectAll" onclick="deselezionaCheckbox()" style="display:none" >Deseleziona tutti</p>

`;
    $('.modifica-campo-classi-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica le ore mattutine</p> '+tbSceltaClassi+' <input type="button" value="Salva" id="submitModifiche" onclick="prendiClassi();">');
});

$('.nomRisorsa').on('click', function(){
	var idRis = $(this).attr('id-risorsa');
    $('#modifica-campo').show();
    $('.modifica-campo-content').empty();
    disableScroll();
    jQuery.ajax({
        type: 'POST',
        url: "crea_datalist.php",
        dataType: 'json',
        success: function(response) {                        
            if (response.success) {
                // Prendi solo la risposta e assegnala a una variabile
                var risposta = response.risposta;
				    $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica nominativo risorsa interna</p> '+risposta+' <input type="button" value="Salva" id="submitModifiche" onclick="prendiNomeRis(\'' + idRis + '\');">');

            } else {
                console.error("Errore: " + response.message);
            }
        }
    });
});

$('.oreCurrRisorsa, .oreExtraCurrRisorsa, .oreSorvRisorsa, .oreProgRisorsa').on('click', function(){
	var idRis = $(this).attr('id-risorsa');
    var idCell = $(this).attr('id');
    var valueOra = $(this).text();
    $('#modifica-campo').show();
    $('.modifica-campo-content').empty();
    $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica ore risorsa interna</p> <input type="number" id="ore" name="ore" min=0 value='+valueOra+'> <input type="button" value="Salva" id="submitModifiche" onclick="prendiOreRis(\'' + idRis + '\', \'' + idCell + '\');">');
    disableScroll();
});

$('.ruoloRisorsa').on('click', function(){
	var idRis = $(this).attr('id-risorsa');
    var idCell = $(this).attr('id');
    var valueOra = $(this).text();
    $('#modifica-campo').show();
    $('.modifica-campo-content').empty();
    $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica ruolo risorsa interna</p> <select id="ruolo" name="ruolo"><option value="Docente potenziamento">Docente potenziamento</option> <option value="Referente PCTO">Referente PCTO</option> <option value="Docente interno">Docente interno</option> </select> <input type="button" value="Salva" id="submitModifiche" onclick="prendiRuoloRis(\'' + idRis + '\', \'' + idCell + '\');">');
    disableScroll();
});

$('.areaCompRisorsa').on('click', function(){
	var idRis = $(this).attr('id-risorsa');
    var idCell = $(this).attr('id');
    $('#modifica-campo').show();
    $('.modifica-campo-content').empty();
    $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica area competenza risorsa interna</p> <select id="areaComp" name="areaComp"><option value="Progettazione">Solo progettazione</option> <option value="Docenza">Solo docenza</option> <option value="Progettazione,Docenza">Entrambe</option> </select> <input type="button" value="Salva" id="submitModifiche" onclick="prendiAreaCompRis(\'' + idRis + '\', \'' + idCell + '\');">');
    disableScroll();
});

$('.nomRisorsaExt').on('click', function(){
	var idRis = $(this).attr('id-risorsa');
    var idCell = $(this).attr('id');
    $('#modifica-campo').show();
    disableScroll();
    $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
    $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica nominativo risorsa esterna</p> <input type="text" id="newNomRisExt" name="newNomRisExt"><input type="button" value="Salva" id="submitModifiche" onclick="prendiNomExt(\'' + idRis + '\', \'' + idCell + '\');">');
});

$('.oreDocRisorsaExt').on('click', function(){
	var idRis = $(this).attr('id-risorsa');
    var idCell = $(this).attr('id');
    var valueOra = $(this).text();
    $('#modifica-campo').show();
    disableScroll();
    $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
    $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica ore docenza risorsa esterna</p> <input type="number" id="newOreDocRisExt" name="newOreDocRisExt" value='+valueOra+' min=0><input type="button" value="Salva" id="submitModifiche" onclick="prendiOreRisExt(\'' + idRis + '\', \'' + idCell + '\');">');
});

$('.costoRisorsaExt').on('click', function(){
	var idRis = $(this).attr('id-risorsa');
    var idCell = $(this).attr('id');
    var valueOra = $(this).text();
    $('#modifica-campo').show();
    disableScroll();
    $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
    $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica costo previsto risorsa esterna</p> <input type="number" id="newCostoRisExt" name="newCostoRisExt" value='+valueOra+' min=0><input type="button" value="Salva" id="submitModifiche" onclick="prendiCostoRisExt(\'' + idRis + '\', \'' + idCell + '\');">');
});

$('.costiEventualiRisorsaExt').on('click', function(){
	var idRis = $(this).attr('id-risorsa');
    var idCell = $(this).attr('id');
    $('#modifica-campo').show();
    disableScroll();
    $('.modifica-campo-content').empty(); // Pulisci il contenuto precedente
    $('.modifica-campo-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Modifica costo previsto risorsa esterna</p> <textarea name="newCostiEventualiRisExt" id="newCostiEventualiRisExt" placeholder="Scrivi qui..."></textarea> <input type="button" value="Salva" id="submitModifiche" onclick="prendiCostiEventualiRisExt(\'' + idRis + '\', \'' + idCell + '\');">');
});

function prendiTitolo() {
    var newTitolo = $('#newTitolo').val();
    if (newTitolo.trim()) {
        jQuery.ajax({
            type: 'POST',
            url: "upd_titolo.php",
            dataType: 'json',
            data: {
                'id': idProgetto, // Assicurati che idProgetto sia definito
                'titolo': newTitolo,
            },
            success: function(response) {                        
                console.log("Risposta AJAX:", response);
                if (response.success) {
                    console.log("Titolo aggiornato con successo:", response.risposta);
                    location.reload(); // Ricarica la pagina per vedere il cambiamento
                } else {
                    console.error("Errore: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Errore AJAX:", error);
            }
        });
    } 
    enableScroll();
    $('#modifica-campo').hide();
}


function prendiDocRef(){
var newDocRef = $('#doc').val();
    if (newDocRef.trim()) {
    	jQuery.ajax({
          type: 'POST',
          url: "upd_docRef.php",
          dataType: 'json',
          data: {
            'id': idProgetto,
            'docRef': newDocRef,
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
	$('#modifica-campo').hide();
}

function prendiDip(){

	var newDip = $('#newDip').val();
    if (newDip.trim()) {
		jQuery.ajax({
          type: 'POST',
          url: "upd_dip.php",
          dataType: 'json',
          data: {
            'id': idProgetto,
            'dip': newDip,
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
	$('#modifica-campo').hide();

}

function prendiRif(id){

	var newRif = $("#newRif").val();
	if (newRif.trim()) {
    	
        jQuery.ajax({
          type: 'POST',
          url: "upd_rif.php",
          dataType: 'json',
          data: {
            'id': idProgetto,
            'riferimento': id,
            'newRif': newRif,
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
	$('#modifica-campo').hide();

}

function prendiAspDid(id){
	var newAspDid = $("#newAspDid").val();
	if (newAspDid.trim()) {
    	jQuery.ajax({
          type: 'POST',
          url: "upd_aspDid.php",
          dataType: 'json',
          data: {
            'id': idProgetto,
            'aspDid': id,
            'newAsp': newAspDid,
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
	$('#modifica-campo').hide();
}

function prendiMesi(){
	var checkboxes = document.querySelectorAll('input[name="mese[]"]');

    var valoriSelezionati = '';

    checkboxes.forEach(function(checkbox) {
        // Controlla se la checkbox è selezionata
        if (checkbox.checked) {
            // Aggiungi il valore della checkbox selezionata alla stringa, separato da virgola
            valoriSelezionati += checkbox.value + ', ';
        }
    });

    valoriSelezionati = valoriSelezionati.slice(0, -2);
    
    if (valoriSelezionati.length > 0)
    {
      	jQuery.ajax({
            type: 'POST',
            url: "upd_tempi.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'tempi': valoriSelezionati,
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
	$('#modifica-campo').hide();
    
}

function prendiComp(id, idProgetto) {
    var newComp = $("#newComp").val();
    var flag = false;
    var comp = "";
    
    if (newComp.trim()) {
        // Determina quale competenza stiamo modificando
        if (id === "comp2") {
            flag = true;
            comp = $("#comp2").text();
        } else if (id === "comp1") {
            flag = false;
            comp = $("#comp1").text();
        }
        
        // Se stiamo modificando una competenza esistente
        if (comp !== "") {
            if (comp !== newComp) {
                jQuery.ajax({
                    type: 'POST',
                    url: "upd_comp.php",
                    dataType: 'json',
                    data: {
                        'id': idProgetto,
                        'comp': newComp,
                        'compExist': comp,
                        'flag': flag,
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            console.error("Errore: " + response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Funzione chiamata in caso di errore
                        console.error('Error:', textStatus, errorThrown);
                        // Puoi gestire qui l'errore, ad esempio mostrando un messaggio all'utente
                        alert('Si è verificato un errore: ' + textStatus);
                    }
                });
            }
        } else {
            // Se stiamo aggiungendo una nuova competenza
            jQuery.ajax({
                type: 'POST',
                url: "upd_comp.php",
                dataType: 'json',
                data: {
                    'id': idProgetto,
                    'comp': newComp,
                    'flag': flag,
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        console.error("Errore: " + response.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Funzione chiamata in caso di errore
                    console.error('Error:', textStatus, errorThrown);
                    // Puoi gestire qui l'errore, ad esempio mostrando un messaggio all'utente
                    alert('Si è verificato un errore: ' + textStatus);
                }
            });
        }
    }
    
    enableScroll();
    $('#modifica-campo').hide();
}

function prendiOre(id){
	var newOre = $("#new"+id).val();
    if (newOre.trim()) {
    	jQuery.ajax({
            type: 'POST',
            url: "upd_oreMatPom.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'orario': id,
              'ora': newOre,
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
	$('#modifica-campo').hide();
}

function prendiClassi(){
	const checkboxes = document.querySelectorAll('#classi-selezionate input[type="checkbox"]');
    const selected = Array.from(checkboxes).filter(checkbox => checkbox.checked);
    
    var oreMat = $("#oreMatt").text();
    var orePom = $("#orePom").text();
    
    var textClassi = "";
    var conta = 0;
    selected.forEach(checkbox => {
    	conta++;
        textClassi+= checkbox.value + "-";
    });
    textClassi = textClassi.slice(0, -1);
    	  jQuery.ajax({
            type: 'POST',
            url: "upd_classi.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'classi': textClassi,
              'oreMat': oreMat,
              'orePom': orePom,
            },
            success: function(response) {                        
                if (response.success) {
                    location.reload();
                } else {
                    console.error("Errore: " + response.message);
                }
            }
          });
      
    enableScroll()
	$('#modifica-campo-classi').hide();
}

function prendiNomeRis(idRis){

	var newNomRis = $('#doc').val();
    if (newNomRis.trim()) {		
          jQuery.ajax({
            type: 'POST',
            url: "upd_nomRis.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'idRis': idRis,
              'newNom': newNomRis,
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
	$('#modifica-campo').hide();
}

function prendiOreRis(idRis, idCell){
	var newOraRis = $('#ore').val();
    jQuery.ajax({
            type: 'POST',
            url: "upd_oreRis.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'idRis': idRis,
              'tipologia': idCell,
              'newOre': newOraRis,
            },
            success: function(response) {                        
                if (response.success) {
                    location.reload();
                } else {
                    console.error("Errore: " + response.message);
                }
            }
          });
     
    enableScroll()
	$('#modifica-campo').hide();
}

function prendiRuoloRis(idRis, idCell){
	
    var newRuoloRis = $('#ruolo').val();
    
    	jQuery.ajax({
            type: 'POST',
            url: "upd_ruoloRis.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'idRis': idRis,
              'newRuolo': newRuoloRis,
            },
            success: function(response) {                        
                if (response.success) {
                    location.reload();
                } else {
                    console.error("Errore: " + response.message);
                }
            }
          });
    	
	enableScroll()
	$('#modifica-campo').hide();
}

function prendiAreaCompRis(idRis, idCell){
	var newAreaCompRis = $('#areaComp').val();
    
    	jQuery.ajax({
            type: 'POST',
            url: "upd_areaCompRis.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'idRis': idRis,
              'newAreaComp': newAreaCompRis,
            },
            success: function(response) {                        
                if (response.success) {
                    location.reload();
                } else {
                    console.error("Errore: " + response.message);
                }
            }
          });
    
	enableScroll()
	$('#modifica-campo').hide();
}

function prendiNomExt(idRis, idCell){
	var newNomRisExt = $('#newNomRisExt').val();
    
    if (newNomRisExt.trim()) {		
          jQuery.ajax({
            type: 'POST',
            url: "upd_nomRisExt.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'idRis': idRis,
              'newNom': newNomRisExt,
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
	$('#modifica-campo').hide();
}

function prendiOreRisExt(idRis, idCell){
	var newOreDocRisExt = $('#newOreDocRisExt').val();
    
    	jQuery.ajax({
            type: 'POST',
            url: "upd_oreRisExt.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'idRis': idRis,
              'newOreDoc': newOreDocRisExt,
            },
            success: function(response) {                        
                if (response.success) {
                    location.reload();
                } else {
                    console.error("Errore: " + response.message);
                }
            }
          });
    
    enableScroll()
	$('#modifica-campo').hide();
}

function prendiCostoRisExt(idRis, idCell){
var newCostoRisExt = $('#newCostoRisExt').val();
    
    	jQuery.ajax({
            type: 'POST',
            url: "upd_costoRisExt.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'idRis': idRis,
              'newCosto': newCostoRisExt,
            },
            success: function(response) {                        
                if (response.success) {
                    location.reload();
                } else {
                    console.error("Errore: " + response.message);
                }
            }
          });
    
    enableScroll()
	$('#modifica-campo').hide();
}

function prendiCostiEventualiRisExt(idRis, idCell){
var newCostiEventualiRisExt = $('#newCostiEventualiRisExt').val();
    
    	jQuery.ajax({
            type: 'POST',
            url: "upd_costoEventRisExt.php",
            dataType: 'json',
            data: {
              'id': idProgetto,
              'idRis': idRis,
              'newCostoEvent': newCostiEventualiRisExt,
            },
            success: function(response) {                        
                if (response.success) {
                    location.reload();
                } else {
                    console.error("Errore: " + response.message);
                }
            }
          });
    
    enableScroll()
	$('#modifica-campo').hide();
}

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

$('#annullaModifiche').on('click', function(){
	window.location.href = 'rendicontazione.php';
});

$('#miniTit').on('click', function(){
	$('#add-ris').show();
    disableScroll();
    $('.add-ris-content').empty(); // Pulisci il contenuto precedente
    
    var areaComp = `<table id="tbAreaComp">
            <thead>
                <tr>
                    <th></th>
                    <th>Progettazione</th>
                    <th>Docenza</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Docente Potenziamento</td>
                    <td><input type="checkbox" name="progettazione_potenziamento" value="progettazione"></td>
                    <td><input type="checkbox" name="docenza_potenziamento" value="docenza"></td>
                </tr>
                <tr>
                    <td>Referente PCTO</td>
                    <td><input type="checkbox" name="progettazione_pcto" value="progettazione"></td>
                    <td><input type="checkbox" name="docenza_pcto" value="docenza"></td>
                </tr>
                <tr>
                    <td>Docente Interno (no potenziamento, no referente PCTO)</td>
                    <td><input type="checkbox" name="progettazione_interno" value="progettazione"></td>
                    <td><input type="checkbox" name="docenza_interno" value="docenza"></td>
                </tr>
                <tr>
                    <td>Docente Esterno</td>
                    <td><input type="checkbox" name="progettazione_esterno" value="progettazione"></td>
                    <td><input type="checkbox" name="docenza_esterno" value="docenza"></td>
                </tr>
            </tbody>
        </table>`;
    
    jQuery.ajax({
        type: 'POST',
        url: "crea_datalist2.php",
        dataType: 'html',
        data:{
        	'numRis':1,
        },
        success: function(response) {
       	 	// Aggiungi direttamente la risposta HTML al tuo elemento
        	$('.add-ris-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Aggiungi risorsa interna</p><div id="contNewRis">'+areaComp+' '+response+'</div><input type="button" value="Salva" id="submitModifiche" onclick="insNewRisInt();">');
    	},
        error: function(xhr, status, error) {
               console.error("Errore AJAX: " + status + " - " + error);
        }
    });
});

$('#miniTit2').on('click', function(){
	$('#add-ris').show();
    disableScroll();
    $('.add-ris-content').empty(); // Pulisci il contenuto precedente
    var formHtml = `
                        <p>Risorsa esterna</p>
                        <label class="labAddRis" for="risorseExtnome">Nome Docente:</label>
                        <input type="text" id="risorseExtnome" name="risorseExtnome">

                        <label class="labAddRis" for="risorseExtore">Numero Ore di docenza:</label>
                        <input type="number" id="risorseExtore" name="risorseExtore" min="1" value="1"> 
                        
                        <label class="labAddRis" for="risorseExtore">Costo Previsto (Euro):</label>
                        <input type="number" id="risorseExtcosto" name="risorseExtcosto" min="1" value="1">
                        
                        <label class="labAddRis" for="risorseExtore">Eventuali Costi Aggiuntivi:</label>
                        <textarea id="risorseExteventualicosti" name="risorseExteventualicosti" ></textarea>
                        `;
    $('.add-ris-content').append('<button type="button" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Chiudi" id="closer"></button><p class="titMod">Aggiungi risorsa interna</p><div id="contNewRis"> '+formHtml+' </div><input type="button" value="Salva" id="submitModifiche" onclick="insNewRisExt();">');

});

function insNewRisInt(){
	var nomeRisInt = $('#risorse0nome').val();
    var ruoloRisInt = $('#risorse0ruolo').val();
    var currRisInt = $('#risorse0oreCurr').val();
    var extraCurrRisInt = $('#risorse0oreExtraCurr').val();
    var sorvRisInt = $('#risorse0sorveglianza').val();
    var progRisInt = $('#risorse0progett').val();
    var checkboxes = document.querySelectorAll('#tbAreaComp input[type="checkbox"]');
    // Array per memorizzare i nomi delle checkbox selezionate
    var selectedCheckboxes = [];

    // Itera attraverso tutte le checkbox
    checkboxes.forEach(function(checkbox) {
        // Controlla se la checkbox è selezionata
        if (checkbox.checked) {
            // Aggiungi il nome della checkbox selezionata all'array
            selectedCheckboxes.push(checkbox.name);
        }
    });
        
    jQuery.ajax({
        type: 'POST',
        url: "ins_risorsaInt.php",
        dataType: 'html',
        data:{
        	'idPrj':idProgetto,
        	'nomRis':nomeRisInt,
            'ruoloRis':ruoloRisInt,
            'oreCurrRis':currRisInt,
            'oreExtraCurrRis':extraCurrRisInt,
            'oreSorvRis':sorvRisInt,
            'oreProgRis':progRisInt,
            'areaCompRis':selectedCheckboxes,
        },
        success: function(response) {
			location.reload();
    	},
        error: function(xhr, status, error) {
               console.error("Errore AJAX: " + status + " - " + error);
        }
    });
    enableScroll()
	$('#add-ris').hide();
    
}

function insNewRisExt(){
	var nomeRisExt = $('#risorseExtnome').val();
    var oreDocRisExt = $('#risorseExtore').val();
    var costoRisExt = $('#risorseExtcosto').val();
    var costoEventRisExt = $('#risorseExteventualicosti').val();
    
    jQuery.ajax({
        type: 'POST',
        url: "ins_risorsaExt.php",
        dataType: 'html',
        data:{
        	'idPrj':idProgetto,
        	'nomRis':nomeRisExt,
            'oreDocRis':oreDocRisExt,
            'costoRis':costoRisExt,
            'costoEvenRis':costoEventRisExt,
        },
        success: function(response) {
			location.reload();
    	},
        error: function(xhr, status, error) {
               console.error("Errore AJAX: " + status + " - " + error);
        }
    });
    enableScroll()
	$('#add-ris').hide();
    
}

$(document).on('click', '#closer', function() {
    enableScroll();
    $('#modifica-campo').hide();
    $('#modifica-campo-classi').hide();
    $('#add-ris').hide();
});

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
    var $tr = $(this);

    // Nascondi il div con classe 'extra-option'
    var extraOptionId = $tr.find('.extra-option').attr('id');
    $('#' + extraOptionId).show();
});

// Nascondi il div extra quando il mouse esce da .icon-del
$('.icon-del').on('mouseleave', function() {
    var $tr = $(this);

    // Nascondi il div con classe 'extra-option'
    var extraOptionId = $tr.find('.extra-option').attr('id');
    $('#' + extraOptionId).hide();
});

document.querySelectorAll('.extra-option').forEach(function(option) {
    option.addEventListener('click', function() {
        prendiId = this.getAttribute('prendi-id');
        var prendiRis = this.getAttribute('prendi-ris');
        
        if(prendiRis == "int")modal.style.display = "inline-block";
        if(prendiRis == "ext")modal2.style.display = "inline-block";       
    });
});

$('#confirmButton').on('click', function() {
    jQuery.ajax({
    type: 'POST',
    url: "del_ris.php",
    dataType: 'json',
    data: {
        'idRis': prendiId,
    },
    success: function(response) {
		modal.style.display = "none";
    	location.reload();    
     }
    });
});

$('#confirmButton2').on('click', function() {
    jQuery.ajax({
    type: 'POST',
    url: "del_ris_ext.php",
    dataType: 'json',
    data: {
        'idRis': prendiId,
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

$('#cancelButton2').on('click', function() {
    modal2.style.display = "none";
});

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
</script>
<script src='script.js'></script>
</html>