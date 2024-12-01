<?php
require_once("db.php");

$idPrj = $_POST["id"];
$idRis = $_POST["idRis"];
$newAreaComp = $_POST["newAreaComp"];

$stmtNumRis = $conn->prepare("SELECT COUNT(*) AS num, areaCompetenza, ruolo FROM progetti_risorse WHERE fk_risorsaInterna = ".$idRis);
$stmtNumRis->execute();
$numRis = $stmtNumRis->fetchAll();
foreach($numRis as $num){
    $nRis=$num["num"];
    if($nRis==1)$areaAttuale = $num["areaCompetenza"];
}

if($nRis>1 && $newAreaComp!== "Progettazione,Docenza"){
    if($newAreaComp=="Progettazione")
    {
    	$stmtDelAreaRis = $conn->prepare("DELETE FROM progetti_risorse WHERE areaCompetenza = 'docenza' AND fk_risorsaInterna = ".$idRis);
		$stmtDelAreaRis->execute();
    }
    else{
    	$stmtDelAreaRis = $conn->prepare("DELETE FROM progetti_risorse WHERE areaCompetenza = 'progettazione' AND fk_risorsaInterna = ".$idRis);
		$stmtDelAreaRis->execute();
    }

}
else if($nRis==1 && $newAreaComp!== "Progettazione,Docenza"){
    	$stmtAreaComp = $conn->prepare("UPDATE progetti_risorse
                             SET areaCompetenza ='".$newAreaComp."'
                             WHERE fk_risorsaInterna=".$idRis);
  		$stmtAreaComp->execute(); 
}
if($nRis==1 && $newAreaComp === "Progettazione,Docenza"){

	$stmtRuoloRis = $conn->prepare("SELECT ruolo FROM progetti_risorse WHERE fk_risorsaInterna = ".$idRis);
	$stmtRuoloRis->execute();
	$ruoloRis = $stmtRuoloRis->fetchAll();
	foreach($ruoloRis as $ruo)$ruolo=$ruo["ruolo"];
    
    if($areaAttuale=="Progettazione"){
    	$stmtInsRis = $conn->prepare("INSERT INTO progetti_risorse(areaCompetenza, ruolo, fk_risorsaInterna, fk_progetti) VALUES('docenza', '".$ruolo."',".$idRis.",".$idPrj.")");
		$stmtInsRis->execute();
	}
    else{
    	$stmtInsRis = $conn->prepare("INSERT INTO progetti_risorse(areaCompetenza, ruolo, fk_risorsaInterna, fk_progetti) VALUES('progettazione', '".$ruolo."',".$idRis.",".$idPrj.")");
		$stmtInsRis->execute();
    }
}

echo json_encode(array('success' => true));


?>