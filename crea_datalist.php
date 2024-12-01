<?php
  require_once("db.php");
  $stmt = $conn->prepare("SELECT nominativo FROM docenteReferente");
  $stmt->execute();
  $result = $stmt->fetchAll();

  $data = '<input type="text" id="doc" name="doc" list="docenti">
            <datalist id="docenti">';

  foreach($result as $row) {
    $data .= '<option value="'.$row["nominativo"].'">';
  }
  $data .='</datalist>';
   echo json_encode(array('success' => true, 'risposta' => $data));

?>