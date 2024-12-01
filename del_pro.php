<?php

require_once("db.php");
$id = $_POST["id"];

$stmDel1= $conn->prepare("DELETE r FROM risorseInterne r, progetti_risorse pr WHERE pr.fk_risorsaInterna=r.id AND pr.fk_progetti = ".$id);
$stmDel1->execute();

$stmDel2= $conn->prepare("DELETE r FROM risorseEsterne r, progetti_risorse pr WHERE pr.fk_risorsaEsterna=r.id AND pr.fk_progetti = ".$id);
$stmDel2->execute();

$stmDel3= $conn->prepare("DELETE FROM progetti WHERE id=".$id);
$stmDel3->execute();

$stmDel4= $conn->prepare("DELETE FROM progetti_classi WHERE fk_progetto=".$id);
$stmDel4->execute();

$stmDel5= $conn->prepare("DELETE FROM progetti_competenze WHERE fk_progetti=".$id);
$stmDel5->execute();

$stmDel6= $conn->prepare("DELETE FROM progetti_risorse WHERE fk_progetti=".$id);
$stmDel6->execute();

   echo json_encode(array('success' => true));


?>