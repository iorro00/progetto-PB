<?php
    require_once("db.php");

    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $stm = $conn->prepare("DELETE FROM classi WHERE id = :id");
        $stm->bindParam(':id', $id);
        $stm->execute();
        echo json_encode(array('success' => true));
    }
?>