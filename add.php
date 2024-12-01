<?php
    session_start();

	$titolo = $_POST["title"];
    $dipart = $_POST["dip"];
    $oreMat = $_POST["oreMat"];
    $orePom = $_POST["orePom"];
    $strutt = $_POST["strutt"];
    $orient = $_POST["orient"];
    $percorsi = $_POST["percorsi"];
    $origine = $_POST["originePrj"];
    $contesto = $_POST["contesto"];
    $obiettivi = $_POST["obb"];
    $attivita = $_POST["attiv"];
    $metodi = $_POST["metodi"];
    $tempi = $_POST["mese"];
    $luoghi = $_POST["luog"];
    $verifica = $_POST["verifica"];
    $documentazione = $_POST["document"];
    if(isset($_POST["progettazione_potenziamento"]))$progettazione_potenziamento = $_POST["progettazione_potenziamento"];
    if(isset($_POST["docenza_potenziamento"]))$docenza_potenziamento = $_POST["docenza_potenziamento"];
    if(isset($_POST["progettazione_pcto"]))$progettazione_pcto = $_POST["progettazione_pcto"];
    if(isset($_POST["docenza_pcto"]))$docenza_pcto = $_POST["docenza_pcto"];
    if(isset($_POST["progettazione_interno"]))$progettazione_interno = $_POST["progettazione_interno"];
    if(isset($_POST["docenza_interno"]))$docenza_interno = $_POST["docenza_interno"];
    if(isset($_POST["progettazione_esterno"]))$progettazione_esterno = $_POST["progettazione_esterno"];
    if(isset($_POST["docenza_esterno"]))$docenza_esterno = $_POST["docenza_esterno"];

	$data = array(
    "Docente potenziamentoP" => $progettazione_potenziamento,
    "Docente potenziamentoD" => $docenza_potenziamento,
    "Referente PCTOP" => $progettazione_pcto,
    "Referente PCTOD" => $docenza_pcto,
    "Docente internoP" => $progettazione_interno,
    "Docente internoD" => $docenza_interno
	);
    



    $numris = $_POST["numRisorse"];
    $numrisext = $_POST["numRisorseExt"];

    
    $comp = array();
    $comp['comp1']=$_POST["comp1"];
    $comp['comp2']=$_POST["comp2"];
    $comp['comp3']=$_POST["comp3"];
    $comp['comp4']=$_POST["comp4"];
    $comp['comp5']=$_POST["comp5"];
    $comp['comp6']=$_POST["comp6"];
    $comp['comp7']=$_POST["comp7"];
    $comp['comp8']=$_POST["comp8"];
    
    
    $classiSelezionate = array();

	foreach ($_POST as $nomeCampo => $valoreCampo) {
        // Verifico se il nome del campo corrisponde a una classe (considerando che è una checkbox con classe 'classe')
        if (isset($valoreCampo) && $valoreCampo === $nomeCampo && isset($_POST[$nomeCampo])) {
            // Aggiungo la classe selezionata all'array delle classi selezionate
            $classiSelezionate[] = $valoreCampo;
        }
    }
	
    $tempiConcatenati = implode(", ", $tempi);
    $mesiSelezionati = $tempiConcatenati;
    
    
    require_once("db.php");
    $stm0 = $conn->prepare("SELECT id FROM docenteReferente WHERE nominativo = '".$_SESSION["nominativo"]."'");
    $stm0->execute();
    $idDoc = $stm0->fetchAll();
    foreach($idDoc as $row0) {
    	$res = $row0["id"];
    }
    $stm = $conn->prepare("INSERT INTO progetti(titolo,fk_dipartimento,strutturale,orientamento,PCTO,origineProgetto,analisi_contesto,obbiettivi_attesi,attivita_previste,metodologia_e_strumenti,tempi_svolgimento, luoghi_svolgimento,verifica_itinere_e_finale,documentazione,fk_docenteReferente) VALUES ('".$titolo."',".$dipart.",'".$strutt."','".$orient."','".$percorsi."','".$origine."','".$contesto."','".$obiettivi."','".$attivita."','".$metodi."','".$mesiSelezionati."','".$luoghi."','".$verifica."','".$documentazione."',".$res.")");
    if($stm->execute()){
	  $id = $conn->lastInsertId();

      foreach($comp as $campo => $valore){
      	if(!empty($valore)){
        $stm3 = $conn->prepare("INSERT INTO progetti_competenze(fk_progetti, fk_competenze) VALUES (".$id.",".$valore.")");
        $stm3->execute();
        }
    }
    
    foreach ($classiSelezionate as $classe) {
    	$anno = substr($classe, 0, 1); 
    	$sezione = substr($classe, 1);
        $stm8 = $conn->prepare("SELECT id FROM classi WHERE anno_classe = ".$anno." AND sezione = '".$sezione."'");
        $stm8->execute();
        $result = $stm8->fetchAll();
        foreach($result as $row) {
            $stm9 = $conn->prepare("INSERT INTO progetti_classi(ore_mattina, ore_pomeriggio,fk_classe, fk_progetto) VALUES (".$oreMat.",".$orePom.",".$row['id'].",".$id.")");
            $stm9->execute();
        }
		
	}
        $risorse = array();
    // Loop per aggiungere il numero desiderato di risorse all'array
    for ($i = 0; $i < $numris; $i++) {
            // Creazione di un array associativo con i dati della risorsa
            $risorse[$i] = array(
            	'nome' => $_POST['risorse'.$i.'nome'],
                'ruolo' => $_POST['risorse'.$i.'ruolo'],
                'progett' => $_POST['risorse'.$i.'progett'],
                'oreCurr' => $_POST['risorse'.$i.'oreCurr'],
                'oreExtraCurr' => $_POST['risorse'.$i.'oreExtraCurr'],
                'sorveglianza' => $_POST['risorse'.$i.'sorveglianza']
            );
      }      
     
      $risorseext = array();
      
      for ($j = 0; $j < $numrisext; $j++) {
            // Creazione di un array associativo con i dati della risorsa
            $risorseext[$j] = array(
            	'nome' => $_POST['risorseExt'.$j.'nome'],
                'ore' => $_POST['risorseExt'.$j.'ore'],
                'costo' => $_POST['risorseExt'.$j.'costo'],
                'costiEventuali' => $_POST['risorseExt'.$j.'eventualicosti']
            );      
      }
      

    foreach ($risorse as $risorsa) {
        $stm4 = $conn->prepare("INSERT INTO risorseInterne (nominativo, oreCurricolari, oreExtraCurricolari, oreSorveglianza, oreProgettazione) VALUES ('".$risorsa['nome']."',".$risorsa['oreCurr'].",".$risorsa['oreExtraCurr'].",".$risorsa['sorveglianza'].",".$risorsa['progett'].")");
        $stm4->execute();
        $id2 = $conn->lastInsertId();
        
        $flag = false;
        foreach ($data as $key => $value) {
        $key_without_last_letter = substr($key, 0, -1);
			if($key_without_last_letter == $risorsa['ruolo'])
            {
            	if(isset($value))
                {
                  $flag = true;	
                  $stm5 = $conn->prepare("INSERT INTO progetti_risorse(areaCompetenza, ruolo, fk_risorsaInterna, fk_progetti) VALUES ('".$value."','".$risorsa['ruolo']."',".$id2.",".$id.")");
                  $stm5->execute();
                  $conta = 0;
                }
            }
		}
        if($flag == false)
        {
        	$stm5 = $conn->prepare("INSERT INTO progetti_risorse(ruolo, fk_risorsaInterna, fk_progetti) VALUES ('".$risorsa['ruolo']."',".$id2.",".$id.")");
            $stm5->execute();
        }
        $flag = false;
    }
    
    foreach ($risorseext as $index => $risorsaext) {
    $risorsaext['costiEventuali'];
        $stm6 = $conn->prepare("INSERT INTO risorseEsterne (nominativo, oreDocenza,costoPrevisto,costiEventuali) VALUES ('".$risorsaext['nome']."',".$risorsaext['ore'].",".$risorsaext['costo'].",'".$risorsaext['costiEventuali']."')");
        $stm6->execute();
        $id3 = $conn->lastInsertId();
        
        $stm7 = $conn->prepare("INSERT INTO progetti_risorse(fk_risorsaEsterna, fk_progetti) VALUES (".$id3.",".$id.")");
        $stm7->execute();
    }
    header("Location:ins_visua_project.php");
   }
?>
