<?php
require_once("db.php");
$id = $_POST["id"];
  $stmt = $conn->prepare("SELECT id, descrizione FROM competenze");
  $stmt->execute();
  $result = $stmt->fetchAll();

  $data = '<select id="newComp" name="newComp"><option value="" disabled selected></option>';

  foreach($result as $row) {
    $data .= '<option value="'.$row["descrizione"].'">"'.$row["descrizione"].'"</option>';
  }
  $data .='</select>';
   echo json_encode(array('success' => true, 'risposta' => $data));

?>