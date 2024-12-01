<?php
    require_once("db.php");

    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $stm = $conn->prepare("DELETE FROM docenteReferente WHERE id = ".$id);
        $stm->execute();
        echo json_encode(array('success' => true, 'risposta' => $id));

    }
    else echo json_encode(array('success' => false, 'risposta' => $id));

?>