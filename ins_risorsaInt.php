<?php
require_once("db.php");

$idPrj = $_POST["idPrj"];
$nomeRisInt = $_POST['nomRis'];
$ruoloRisInt = $_POST['ruoloRis'];
$currRisInt = $_POST['oreCurrRis'];
$extraCurrRisInt = $_POST['oreExtraCurrRis'];
$sorvRisInt = $_POST['oreSorvRis'];
$progRisInt = $_POST['oreProgRis'];
$areaComp = $_POST['areaCompRis'];


$stmtRis = $conn->prepare("INSERT INTO risorseInterne (nominativo,oreCurricolari,oreExtraCurricolari,oreSorveglianza,oreProgettazione) VALUES ('".$nomeRisInt."',".$currRisInt.",".$extraCurrRisInt.",".$sorvRisInt.",".$progRisInt.")");
$stmtRis->execute();
$idRis = $conn->lastInsertId();

foreach ($areaComp as $checkbox) {
   if($ruoloRisInt == "Docente potenziamento")
   {
   		if($checkbox=="progettazione_potenziamento")
        {
        	$stmtRis2 = $conn->prepare("INSERT INTO progetti_risorse (areaCompetenza,ruolo,fk_risorsaInterna,fk_progetti) VALUES ('progettazione','".$ruoloRisInt."',".$idRis.",".$idPrj.")");
			$stmtRis2->execute();
        }
        if($checkbox=="docenza_potenziamento")
        {
        	$stmtRis3 = $conn->prepare("INSERT INTO progetti_risorse (areaCompetenza,ruolo,fk_risorsaInterna,fk_progetti) VALUES ('docenza','".$ruoloRisInt."',".$idRis.",".$idPrj.")");
			$stmtRis3->execute();
        }
   }
   if($ruoloRisInt == "Referente PCTO")
   {
   		if($checkbox=="progettazione_pcto")
        {
        	$stmtRis4 = $conn->prepare("INSERT INTO progetti_risorse (areaCompetenza,ruolo,fk_risorsaInterna,fk_progetti) VALUES ('progettazione','".$ruoloRisInt."',".$idRis.",".$idPrj.")");
			$stmtRis4->execute();
        }
        if($checkbox=="docenza_pcto")
        {
        	$stmtRis5 = $conn->prepare("INSERT INTO progetti_risorse (areaCompetenza,ruolo,fk_risorsaInterna,fk_progetti) VALUES ('docenza','".$ruoloRisInt."',".$idRis.",".$idPrj.")");
			$stmtRis5->execute();
        }
   }
   if($ruoloRisInt == "Docente interno")
   {
   		if($checkbox=="progettazione_interno")
        {
        	$stmtRis6 = $conn->prepare("INSERT INTO progetti_risorse (areaCompetenza,ruolo,fk_risorsaInterna,fk_progetti) VALUES ('progettazione','".$ruoloRisInt."',".$idRis.",".$idPrj.")");
			$stmtRis6->execute();
        }
        if($checkbox=="docenza_interno")
        {
        	$stmtRis7 = $conn->prepare("INSERT INTO progetti_risorse (areaCompetenza,ruolo,fk_risorsaInterna,fk_progetti) VALUES ('docenza','".$ruoloRisInt."',".$idRis.",".$idPrj.")");
			$stmtRis7->execute();
        }
   }
}



echo json_encode(array('success' => true));
?>