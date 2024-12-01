<?php
require_once("db.php");

$idPrj = $_POST["id"];
$classi = $_POST["classi"];
$oreMat = $_POST["oreMat"];
$orePom = $_POST["orePom"];

$classi = explode("-", $classi);
$classi = array_map('trim', $classi);

$stmDelClassi= $conn->prepare("DELETE FROM progetti_classi WHERE fk_progetto = ".$idPrj);
$stmDelClassi->execute();

foreach ($classi as $classe) {
    $anno = substr($classe, 0, 1);
    $sezione = substr($classe, 1, 1);
    
    $stmClassi = $conn->prepare("SELECT id FROM classi WHERE anno_classe =".$anno." AND sezione = '".$sezione."'");
    $stmClassi->execute();
    $res = $stmClassi->fetchAll();
    foreach($res as $cls)$idCls=$cls["id"];
    
    $stmInsClassi = $conn->prepare("INSERT INTO progetti_classi (ore_pomeriggio, ore_mattina, fk_classe, fk_progetto) VALUES (".$orePom.", ".$oreMat.",".$idCls.",".$idPrj.")");
    $stmInsClassi->execute();
    
}

    
echo json_encode(array('success' => true, 'risp' => $idPrj));
?>