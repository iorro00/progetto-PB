<?php
require_once("db.php");

$classi = $_POST["classi"];
$nominativo = $_POST["nominativo"];
$progetti =[];

if (!empty($classi)){
  foreach ($classi as $classe) {

      $anno = (int) $classe[0];
      $sezione = substr($classe, 1);

      $stmt = $conn->prepare("SELECT p.id
                              FROM progetti p
                              JOIN progetti_classi pc ON p.id = pc.fk_progetto
                              JOIN classi c ON pc.fk_classe = c.id
                              JOIN docenteReferente d ON p.fk_docenteReferente = d.id
                              AND d.nominativo = '".$nominativo."'
                              AND c.anno_classe = ".$anno."
                              AND c.sezione = '".$sezione."'");
      $stmt->execute();
      $result = $stmt->fetchAll();

      foreach ($result as $row) {
          $progetti[] = $row["id"]; // Aggiungi ogni riga del risultato come elemento dell'array $progetti
      }
  }
}
else{

	  $stmt = $conn->prepare("SELECT p.id
                              FROM progetti p
                              JOIN progetti_classi pc ON p.id = pc.fk_progetto
                              JOIN classi c ON pc.fk_classe = c.id
                              JOIN docenteReferente d ON p.fk_docenteReferente = d.id
                              AND d.nominativo = '".$nominativo."'");
      $stmt->execute();
      $result = $stmt->fetchAll();

      foreach ($result as $row) {
          $progetti[] = $row["id"]; // Aggiungi ogni riga del risultato come elemento dell'array $progetti
      }

}
$ArrPrj = array_unique($progetti);

// Reindicizza l'array per mantenere gli indici sequenziali
$ArrPrj = array_values($ArrPrj);
$table = "<table id='tbRendi' class='tbFiltered'>
                    <tr>
                    	<th style= display:none;></th>
                        <th>Titolo Progetto</th>
                        <th>Dipartimento</th>
                        <th>Docente Referente</th>
                        <th></th>
                        <th></th>
                    </tr>";
          
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
            $table .= "<tr'>
            			<td style= display:none; >" . $row["id"] . "</td>
                        <td onclick='eventi(" . $row["id"] . ")';>" . $row["titolo"] . "</td>
                        <td onclick='eventi(" . $row["id"] . ")';>" . $varDip . "</td>
                        <td onclick='eventi(" . $row["id"] . ")';>" . $varRef . "</td>
                        <td class='icon-mod'><div prendi-id2='". $row["id"]."' style='display:none;' class='extra-option-left' id='extraOptionLeft-".$row["id"]."' onclick='modifica(" . $row["id"] . ")'><img src='img/modifica.png' class='modIcon'></div></td>
        				<td class='icon-del'><div prendi-id='" . $row["id"] . "' style='display:none;' class='extra-option' id='extraOption-".$row["id"]."' onclick='elimina(" . $row["id"] . ")'><img src='img/delete.png' class='deleteIcon'></div></td>
                       </tr>";
        }
}
$table .= "</table>";

echo json_encode(array('success' => true, 'risposta' => $table));

?>