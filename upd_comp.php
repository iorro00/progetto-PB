<?php
require_once("db.php");

$idPrj = $_POST["id"];
$competenza = $_POST["comp"];
$flag = $_POST["flag"];

if($flag===true){
	$secondaComp = $_POST["compExist"];
    
    $stmtNumComp = $conn->prepare("SELECT id FROM competenze WHERE descrizione = '".$competenza."'");
    $stmtNumComp->execute();
    $result = $stmtNumComp->fetchAll();
    foreach($result as $row)$comp1=$row["id"];
    
    $stmtScComp = $conn->prepare("SELECT id FROM competenze WHERE descrizione = '".$secondaComp."'");
    $stmtScComp->execute();
    $result2 = $stmtScComp->fetchAll();
    foreach($result2 as $row2)$comp2=$row2["id"];
    
    $stmtComp = $conn->prepare("UPDATE progetti_competenze
                               SET fk_competenze =".$comp1."
                               WHERE fk_progetti=".$idPrj."
                               AND fk_competenze !=".$comp2);
    $stmtComp->execute();
}
else{
	$stmtNumComp = $conn->prepare("SELECT id FROM competenze WHERE descrizione = '".$competenza."'");
    $stmtNumComp->execute();
    $result = $stmtNumComp->fetchAll();
    foreach($result as $row)$comp1=$row["id"];
    
	$stmtComp = $conn->prepare("UPDATE progetti_competenze
                               SET fk_competenze =".$comp1."
                               WHERE fk_progetti=".$idPrj);
    $stmtComp->execute();

}
 
echo json_encode(array('success' => true));


?>