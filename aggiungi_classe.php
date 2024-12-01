<?php
    require_once("db.php");

    if (isset($_POST['anno']) && isset($_POST['sezione']) && isset($_POST['indirizzo'])) {
        $anno = $_POST['anno'];
        $sezione = $_POST['sezione'];
        $indirizzo = $_POST['indirizzo'];

        $stm = $conn->prepare("INSERT INTO classi (anno_classe, sezione, fk_indirizzo) VALUES (:anno, :sezione, :fk_indirizzo)");
        $stm->bindParam(':anno', $anno);
        $stm->bindParam(':sezione', $sezione);
        $stm->bindParam(':fk_indirizzo', $indirizzo);
        $stm->execute();
        echo json_encode(array('success' => true));

    }
    else echo json_encode(array('success' => false));
?>