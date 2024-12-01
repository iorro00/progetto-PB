<?php
require_once("db.php");
$mess = $_POST["mess"];

  if($mess=="int"){      
        $dato = $_POST["dato"]; 
        $id_progetto = $_POST["id"];

        $stmRisInt= $conn->prepare("SELECT r.id, r.nominativo, r.oreCurricolari, r.oreExtraCurricolari, r.oreSorveglianza, r.oreProgettazione, r.OreCurricolariEffettive, r.OreExtraCurricolariEffettive, r.OreSorveglianzaEffettive, r.OreProgettazioneEffettive FROM risorseInterne r, progetti_risorse p WHERE p.fk_progetti =".$id_progetto." AND r.id =".$dato);
        $stmRisInt->execute();
        $risInt = $stmRisInt->fetchAll();

        $oreEff = "<table id='tbOreEff'><tr>";
        foreach($risInt as $rsi){
          $id = $rsi["id"];
          $nome = $rsi["nominativo"];
          $oreCurrEff = $rsi["OreCurricolariEffettive"];
          $oreExtraCurrEff = $rsi["OreExtraCurricolariEffettive"];
          $oreSorvEff = $rsi["OreSorveglianzaEffettive"];
          $oreProgEff = $rsi["OreProgettazioneEffettive"];
        }
        $oreEff.= "<p class='subTit' id='get-id' data-id='".$id."'>".$nome."</p><tr>";
        $oreEff.= "<tr><td><label class='subLab' for='oreCurrForm'>Ore curricolari effettuate:</label></td><td><input type='number' id='oreCurrForm' name='oreCurrForm' value=".$oreCurrEff." min=0></td></tr>";
        $oreEff.= "<td><label class='subLab' for='oreExtrCurrForm'>Ore extra-curricolari effettuate:</label></td><td><input type='number' id='oreExtrCurrForm' name='oreExtrCurrForm' value=".$oreExtraCurrEff." min=0></td></tr>";
        $oreEff.= "<td><label class='subLab' for='oreSorvForm'>Ore sorveglianza effettuate:</label></td><td><input type='number' id='oreSorvForm' name='oreSorvForm' value=".$oreSorvEff." min=0></td></tr>";
        $oreEff.= "<td><label class='subLab' for='oreProgForm'>Ore progettazione effettuate:</label></td><td><input type='number' id='oreProgForm' name='oreProgForm' value=".$oreProgEff." min=0></td></tr>";
        $oreEff.= "</table>";
        $oreEff.= "<input type='button' value='Invia' id='submit2' onclick=addPopupEventListeners();>";
        
        if ($dato !== null) {
          // Rispondi con il dato elaborato
          echo json_encode(array('success' => true, 'risposta' => $oreEff));
        } else {
          // Rispondi con un messaggio di errore
          echo json_encode(array('success' => false, 'message' => 'Dato non impostato'));
        }

        // Interrompe l'esecuzione dello script per evitare di inviare ulteriori contenuti
        exit();
  }
  if($mess=="ext"){
		$datoExt = $_POST["dato-ext"]; 
        $id_progetto = $_POST["id"];
        
        $stmRisExt= $conn->prepare("SELECT r.id, r.nominativo, r.oreDocenza, r.costoPrevisto, r.oreDocenzaEffettive, r.costoEffettivo FROM risorseEsterne r, progetti_risorse p WHERE p.fk_progetti =".$id_progetto." AND r.id =".$datoExt);
        $stmRisExt->execute();
        $risExt = $stmRisExt->fetchAll();
        
        $oreEff = "<table id='tbOreEff'><tr>";
        
        foreach($risExt as $rse){
          $id = $rse["id"];
          $nome = $rse["nominativo"];
          $oreDocEff = $rse["oreDocenzaEffettive"];
          $costoEff = $rse["costoEffettivo"];
        }
        
        $oreEff.= "<p class='subTit' id='get-id' data-id='".$id."'>".$nome."</p><tr>";
        $oreEff.= "<tr><td><label class='subLab' for='oreDocForm'>Ore docenza effettuate:</label></td><td><input type='number' id='oreDocForm' name='oreDocForm' value=".$oreDocEff." min=0></td></tr>
        <tr><td><label class='subLab' for='costoDocForm'>Costo effettivo:</label></td><td><input type='number' id='costoDocForm' name='costoDocForm' value=".$costoEff." min=0></td></tr>";
		$oreEff.= "</table>";
        $oreEff.= "<input type='button' value='Invia' id='submit3' onclick=addPopupEventListenersExt();>";

        if ($datoExt !== null) {
          // Rispondi con il dato elaborato
          echo json_encode(array('success' => true, 'risposta' => $oreEff));
        } else {
          // Rispondi con un messaggio di errore
          echo json_encode(array('success' => false, 'message' => 'ERRORE'));
        }
  }
?>