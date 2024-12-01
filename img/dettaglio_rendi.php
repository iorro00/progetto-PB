<?php
    ini_set('session.gc_maxlifetime', 7200);
    session_set_cookie_params(7200);
    session_start();
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
        <title>Rendicontazione</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Orbitron">
        <link href='https://fonts.googleapis.com/css?family=Merriweather Sans' rel='stylesheet'>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
<body>
	<div id="box3">
        <img id="logo" src="img/logo.png" alt="Immagine non trovata">
        <h1><a id='pb-link' href='ins_visua_project.php'>PB</a></h1>
    </div>
    <?php
    require_once("db.php");
    
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        $projectId = $_POST['id'];
    
        // Esegui la query per recuperare i dettagli del progetto utilizzando l'ID
        $stm = $conn->prepare("SELECT * FROM progetti WHERE id =". $projectId);
        $stm->execute();
        $project = $stm->fetchAll(); // Restituisce la riga corrente come array associativo
        
        $oreEffCurr = 0;
        $oreEffExtrCurr = 0;
        $oreEffSorv = 0;
    	$oreEffProg = 0;
        
        $oreEffDoc = 0;
        
        $tabGenerale = "<table id='tbVisuaDetails'><tr>";
        $tabRiferimenti = "<table id='tbVisuaDetails'><tr>";
        $tabAspettiDid = "<table id='tbVisuaDetails'><tr>";
        $tabDestinatari = "<table id='tbVisuaDetails'><tr>";
        $tabCompetenze = "<table id='tbVisuaDetails'><tr>";
   		$tabRisorseInt = "<table id='tbVisuaDetails'><tbody><tr><td><b>Nominativo</b></td><td><b>Ore Curricolari</b></td><td><b>Ore Extra-Curricolari</b></td><td><b>Ore Sorveglianza</b></td><td><b>Ore Progettazione</b></td><td><b>Area Competenza</b></td><td></td></tr>";
   		$tabRisorseExt = "<table id='tbVisuaDetails'><tbody><tr><td><b>Nominativo</b></td><td><b>Ore Docenza</b></td><td><b>Costo Previsto</b></td></tr>";
        
        // Stampiamo dinamicamente tutti gli attributi del progetto
        foreach($project as $value) {
        	echo "<p id='titVisua'><b>Progetto ". $value["titolo"]."</b></p>";
            
            
            
            $stmRef = $conn->prepare("SELECT nominativo FROM docenteReferente WHERE id =". $value["fk_docenteReferente"]);
        	$stmRef->execute();
        	$docRef = $stmRef->fetchAll();
            foreach($docRef as $doc){
            	$tabGenerale .= "<th>Docente referente:</th><td>".$doc["nominativo"]."</td></tr>";
            }
            
            $stmDip = $conn->prepare("SELECT nome FROM dipartimento WHERE id =". $value["fk_dipartimento"]);
        	$stmDip->execute();
        	$dipart = $stmDip->fetchAll();
            foreach($dipart as $dip){
            	$tabGenerale .= "<tr><th>Dipartimento:</th><td>".$dip["nome"]."</td>";
            }
            
            $tabRiferimenti .= "<th>Strutturale:</th><td>".$value["strutturale"]."</td></tr>";
            $tabRiferimenti .= "<th>Ore di orientamento:</th><td>".$value["orientamento"]."</td></tr>";
            $tabRiferimenti .= "<th>Percorso di PCTO:</th><td>".$value["PCTO"]."</td></tr>";
            
            $tabAspettiDid .= "<th>Analisi del contesto:</th><td>".$value["analisi_contesto"]."</td></tr>";
            $tabAspettiDid .= "<th>Obbiettivi attesi:</th><td>".$value["obbiettivi_attesi"]."</td></tr>";
            $tabAspettiDid .= "<th>Attività previste:</th><td>".$value["attivita_previste"]."</td></tr>";
            $tabAspettiDid .= "<th>Metodologia e strumenti:</th><td>".$value["metodologia_e_strumenti"]."</td></tr>";
            $tabAspettiDid .= "<th>Tempi e luoghi di svolgimento:</th><td>".$value["tempi_luoghi_svolgimento"]."</td></tr>";
            $tabAspettiDid .= "<th>Modalità di verifica in itinere e finale:</th><td>".$value["verifica_itinere_e_finale"]."</td></tr>";
            $tabAspettiDid .= "<th>Documentazione:</th><td>".$value["documentazione"]."</td></tr>";
          
            $stmClassi = $conn->prepare("SELECT c.anno_classe, c.sezione FROM progetti_classi p, classi c WHERE p.fk_progetto =".$value["id"]." AND p.fk_classe = c.id");
        	$stmClassi->execute();
        	$classi = $stmClassi->fetchAll();
            $tabDestinatari .= "<tr><th>Classi destinatarie del progetto:</th><td>";
            $cont = 0;
            foreach($classi as $cla){
              $cont++;
              $tabDestinatari .= $cla["anno_classe"].$cla["sezione"]." - ";	
              if($cont == 4){
                $tabDestinatari .= "<br>";
                $cont = 0;
              }
            }
            
            $stmComp = $conn->prepare("SELECT c.descrizione FROM progetti_competenze p, competenze c WHERE p.fk_progetti =".$value["id"]." AND p.fk_competenze = c.id");
        	$stmComp->execute();
        	$competenze = $stmComp->fetchAll();
              foreach($competenze as $cmp){
				$tabCompetenze .= "<tr><th>Competenza ".$contaComp.":</th><td>".$cmp["descrizione"]."</td>";
                $contaComp++;
              }
              
            $stmRisInt= $conn->prepare("SELECT r.id, r.nominativo, r.oreCurricolari, r.oreExtraCurricolari, r.oreSorveglianza, r.oreProgettazione FROM risorseInterne r, progetti_risorse p WHERE p.fk_progetti =".$value["id"]." AND p.fk_risorsaInterna = r.id GROUP BY r.id, r.nominativo");
        	$stmRisInt->execute();
        	$risInt = $stmRisInt->fetchAll();
              foreach($risInt as $rsi){
				$tabRisorseInt .= "<tr><td>".$rsi["nominativo"]."</td><td>".$rsi["oreCurricolari"]." (".$oreEffCurr.")</td><td>".$rsi["oreExtraCurricolari"]." (".$oreEffExtrCurr.")</td><td>".$rsi["oreSorveglianza"]." (".$oreEffSorv.")</td><td>".$rsi["oreProgettazione"]." (".$oreEffProg.")</td><td><img src='img/hours.png' alt='Image not found'></td>";
              	$stmAreaComp= $conn->prepare("SELECT DISTINCT p.areaCompetenza FROM risorseInterne r, progetti_risorse p WHERE p.fk_risorsaInterna =".$rsi["id"]);
        		$stmAreaComp->execute();
        		$areaComp = $stmAreaComp->fetchAll();
                $tabRisorseInt.= "<td>";
                foreach($areaComp as $arc){
                	$tabRisorseInt .= $arc["areaCompetenza"].", ";
                }
                $tabRisorseInt = rtrim($tabRisorseInt, ", ");
                $tabRisorseInt.="</td></tr>";
              }
              
            $stmRisExt= $conn->prepare("SELECT r.nominativo, r.oreDocenza, r.costoPrevisto FROM risorseEsterne r, progetti_risorse p WHERE p.fk_progetti =".$value["id"]." AND p.fk_risorsaEsterna = r.id");
        	$stmRisExt->execute();
        	$risExt = $stmRisExt->fetchAll();
              foreach($risExt as $rse){
				$tabRisorseExt .= "<tr><td>".$rse["nominativo"]."</td><td>".$rse["oreDocenza"]." (".$oreEffDoc.")</td><td>".$rse["costoPrevisto"]."</td></tr>";
              }
        }
        echo "<p class='subTit'>Informazioni generali</p>";
        $tabGenerale .= "</tr></table>";
        echo $tabGenerale;
        
        echo "<p class='subTit'>Riferimenti al PTOF</p>";
        $tabRiferimenti .= "</tr></table>";
        echo $tabRiferimenti;
        
        echo "<p class='subTit'>Aspetti didattici</p>";
        $tabAspettiDid .= "</tr></table>";
        echo $tabAspettiDid;
        
        echo "<p class='subTit'>Destinatari del progetto</p>";
        $tabDestinatari = rtrim($tabDestinatari, "<br>");
        $tabDestinatari = substr($tabDestinatari, 0, -2);
        $tabDestinatari .= "</td></tr></table>";
        echo $tabDestinatari;
        
        if($contarigheComp > 1){
          echo "<p class='subTit'>Competenze progetto</p>";
          $tabCompetenze .= "</tr></table>";
          echo $tabCompetenze;
        }
        
        echo "<p class='subTit'>Risorse interne</p>";
        $tabRisorseInt .= "</table>";
        echo $tabRisorseInt;
        
        echo "<p class='subTit'>Risorse esterne</p>";
        $tabRisorseExt .= "</table>";
        echo $tabRisorseExt;
        
    } else {
        echo "<p>Progetto non trovato.</p>";
    }
?>    

	<button id="insOreEff">Inserire ore effettive</button>
    
    <div style=display:none id='popup'>
    		<div class="popup-content">
              <span id="closePopup">&times;</span>
              <p id="subTitPopup">Ore effettive</p>
              	<?php
                 	$stmRisInt= $conn->prepare("SELECT r.id, r.nominativo, r.oreCurricolari, r.oreExtraCurricolari, r.oreSorveglianza, r.oreProgettazione FROM risorseInterne r, progetti_risorse p WHERE p.fk_progetti =".$value["id"]." AND p.fk_risorsaInterna = r.id GROUP BY r.id, r.nominativo");
        			$stmRisInt->execute();
        			$risInt = $stmRisInt->fetchAll();
                    
                    $oreEff = "<table id='tbOreEff'><tr>";
                    
                    foreach($risInt as $rsi){
                    
                    	$oreEff.= "<p class='subTit'>".$rsi["nominativo"]."</p><tr>";
                        $oreEff.= "<tr><td><label class='subLab' for='oreCurr'>Ore curricolari effettuate:</label></td><td><input type='number' id='oreCurr' name='oreCurr' value=0 min=0></td></tr>";
                        $oreEff.= "<td><label class='subLab' for='oreExtrCurr'>Ore extra-curricolari effettuate:</label></td><td><input type='number' id='oreExtrCurr' name='oreExtrCurr' value=0 min=0></td></tr>";
                        $oreEff.= "<td><label class='subLab' for='oreSorv'>Ore sorveglianza effettuate:</label></td><td><input type='number' id='oreSorv' name='oreSorv' value=0 min=0></td></tr>";
                        $oreEff.= "<td><label class='subLab' for='oreProg'>Ore progettazione effettuate:</label></td><td><input type='number' id='oreProg' name='oreProg' value=0 min=0></td>";
                    }
                    $oreEff.= "</tr></table>";
                    $oreEff.= "<input type='button' value='Invia' id='submit'>";
                    echo $oreEff;
                ?>
        	</div>
    </div>
    
    <p id='exit-link'><b><a id='exit-link' href='ins_visua_project.php'>Ritorna alla home</a></b></p>
</body>
  <script>
  	const openPopupButton = document.getElementById('insOreEff');
    const closePopupButton = document.getElementById('closePopup');
    const popup = document.getElementById('popup');
    
    openPopupButton.addEventListener('click', function() {
        popup.style.display="inline-block";
    });

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
    popup.classList.remove('hidden');
    disableScroll(); // Blocca lo scroll quando il popup viene aperto
});

closePopupButton.addEventListener('click', function() {
    popup.classList.add('hidden');
    enableScroll(); // Consenti nuovamente lo scroll quando il popup viene chiuso
});


  </script>
</html>
