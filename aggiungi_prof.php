<?php
    require_once("db.php");

    if (isset($_POST['nominativo'])) {
        $nominativo = $_POST['nominativo'];

        $stm = $conn->prepare("INSERT INTO docenteReferente (nominativo) VALUES (:nominativo)");
        $stm->bindParam(':nominativo', $nominativo);
        $stm->execute();
        echo json_encode(array('success' => true));

    }
    else echo json_encode(array('success' => false));
?>