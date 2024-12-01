<?php

    try{
        $db_username="ptofbuilder";
        $db_password="";

        $conn = new PDO("mysql:host=127.0.0.1;dbname=my_ptofbuilder", $db_username, $db_password);

    //in caso di errore nella creazione del PDO
    }catch(PDOException $e){ //in caso si presenti un errore viene visualizzato un messaggio e con il die chiudo tutte le connessioni che ho aperto
        echo "errore nel server";
        die();
    }

?>
