<?php
require_once("db.php");
$classi = $_POST["classi"];
$progetti =[];
$oreCurr = 0;
$oreExtraCurr = 0;
$oreSorv = 0;
$oreProg = 0;

$oreMat = 0;
$orePom = 0;


if (!empty($classi)){
  foreach ($classi as $classe) {
      $anno = (int) $classe[0];
      $sezione = substr($classe, 1);

      $stmt = $conn->prepare("SELECT p.id
                              FROM progetti p
                              JOIN progetti_classi pc ON p.id = pc.fk_progetto
                              JOIN classi c ON pc.fk_classe = c.id
                              AND c.anno_classe = ".$anno."
                              AND c.sezione = '".$sezione."'");
      $stmt->execute();
      $result = $stmt->fetchAll();

      foreach ($result as $row) {
          // Aggiungo ogni riga del risultato come elemento dell'array $progetti
          $progetti[] = $row["id"]; 
      }
  }
}
else{
      $stmt = $conn->prepare("SELECT p.id
                              FROM progetti p
                              JOIN progetti_classi pc ON p.id = pc.fk_progetto
                              JOIN classi c ON pc.fk_classe = c.id");
      $stmt->execute();
      $result = $stmt->fetchAll();

      foreach ($result as $row) {
          // Aggiungo ogni riga del risultato come elemento dell'array $progetti
          $progetti[] = $row["id"]; 
      }

}
$ArrPrj = array_unique($progetti);

// Reindicizzo l'array per mantenere gli indici sequenziali
$ArrPrj = array_values($ArrPrj);


$table = "<table class='tbVisua' id='firstTb'>
                    <tr>
                    	<th style= display:none;></th>
                        <th>Titolo Progetto</th>
                        <th>Dipartimento</th>
                        <th>Docente Referente</th>
                    </tr>";
$tableOre = "<table class='tbVisua' id='tbOre'>
                        <tr>
                            <th>Ore totali Progettazione</th>
                            <th>Ore totali Curricolari</th>
                            <th>Ore totali Extracurricolari</th>
                            <th>Ore totali Sorveglianza</th>
                        </tr>
                        <tr>";   
$tableMatPom = "<table class='tbVisua' id='tbMatPom'>
                        <tr>
                            <th>Ore totali Mattino</th>
                            <th>Ore totali Pomeriggio</th>
                        </tr>
                        <tr>";                   
foreach ($ArrPrj as $progetto) {
    $stm = $conn->prepare("SELECT id, titolo, fk_dipartimento, fk_docenteReferente FROM progetti WHERE id = ".$progetto);
    $stm->execute();
    $pr = $stm->fetchAll();
    foreach($pr as $row) {
            $varDip = '';
            if($row["fk_dipartimento"]){
                $dip = $conn->prepare("SELECT nome FROM dipartimento WHERE id = ".$row["fk_dipartimento"]);
                $dip->execute();
                $resultDip = $dip->fetch();
                $varDip = $resultDip["nome"];
            }
            $varRef = '';
            if($row["fk_docenteReferente"]){
                $ref = $conn->prepare("SELECT nominativo FROM docenteReferente WHERE id = ".$row["fk_docenteReferente"]);
                $ref->execute();
                $resultRef = $ref->fetch();
                $varRef = $resultRef["nominativo"];
            }
            $table .= "<tr>
            			<td style= display:none; >" . $row["id"] . "</td>
                        <td onclick='evento(".$row["id"].")'>" . $row["titolo"] . "</td>
                        <td onclick='evento(".$row["id"].")'>" . $varDip . "</td>
                        <td onclick='evento(".$row["id"].")'>" . $varRef . "</td>
                       </tr>";
        }
	 $stm2 = $conn->prepare("SELECT DISTINCT SUM(r.oreCurricolari)AS oreCurricolari, SUM(r.oreExtraCurricolari)AS oreExtraCurricolari, SUM(r.oreSorveglianza)AS oreSorveglianza, SUM(r.oreProgettazione)AS oreProgettazione
        					FROM risorseInterne r, progetti_risorse pr
                            WHERE pr.fk_progetti = ".$progetto."
                            AND pr.fk_risorsaInterna = r.id
                            GROUP BY pr.fk_risorsaInterna");
     $stm2->execute();
     $oreTot = $stm2->fetchAll();    
     
     foreach($oreTot as $ore)
     {
     	$oreCurr += $ore['oreCurricolari'];
        $oreExtraCurr += $ore['oreExtraCurricolari'];
        $oreSorv += $ore['oreSorveglianza'];
        $oreProg += $ore['oreProgettazione'];
     }    
     $stm3 = $conn->prepare("SELECT *
                             FROM progetti_classi
                             WHERE fk_progetto = ".$progetto."
                             LIMIT 1");
     $stm3->execute();
     $oreMP = $stm3->fetchAll();
     foreach($oreMP as $omp)
     {
     	$oreMat += $omp["ore_mattina"];
        $orePom += $omp["ore_pomeriggio"];
     }
     
}
	 $tableOre .= "<td>".$oreProg."</td>";
     $tableOre .= "<td>".$oreCurr."</td>";
     $tableOre .= "<td>".$oreExtraCurr."</td>";
     $tableOre .= "<td>".$oreSorv."</td>";
     
     $tableMatPom .= "<td>".$oreMat."</td>";
     $tableMatPom .= "<td>".$orePom."</td>";
     
$table .= "</table>";
$tableOre .= "</tr></table>";
$tableMatPom .= "</tr></table>";

echo json_encode(array('success' => true, 'risposta' => $table, 'risposta2' => $tableOre, 'risposta3' => $tableMatPom, 'risposta4' => $progetti));

?>