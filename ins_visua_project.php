<?php

    ini_set('session.gc_maxlifetime', 7200);
    session_set_cookie_params(7200);
    session_start();
    
	// Verifico se l'utente ha efettuato l'accesso oppure no
    if (!isset($_SESSION['user_email'])) {
        header("Location: login.php");
        exit;
    }

    // Ottiengo la parte locale dell'email (prima del @)
    $localPart = explode('@', $_SESSION['user_email'])[0];

    // Divido la parte locale in nome e cognome usando '.'
    list($nome, $cognome) = explode('.', $localPart);
    
    //Essendo le due stringhe in minuscolo, converto in maiuscolo la prima lettera
    $nome = ucfirst($nome);
    $cognome = ucfirst($cognome);

    $_SESSION["nominativo"] = $cognome . " " . $nome;
    
    if($_SESSION['user_email'] == "progettiptof@iispascal.it")
    {
        $nome = "Amministratore";
        $_SESSION["nominativo"] = $nome;
        header("Location: pagina_amm.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pagina iniziale</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Orbitron">
        <link href='https://fonts.googleapis.com/css?family=Merriweather Sans' rel='stylesheet'>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
<body>

    <div id='box3'>
        <img src='img/logo.png' alt='Immagine non trovata'>
        <h1><a id='pb-link' href='ins_visua_project.php'>PB</a></h1>
    </div>

    <div id='box2'>
        <p>Bentornato/a <?php echo $nome?></p>
        <button id='ButtIns' class='ButtGenerale'>Inserire progetto</button>
        <button id='ButtVisua' class='ButtGenerale'>Visualizzare progetti</button>
        <button id='ButtRendi' class='ButtGenerale'>Visualizzare rendicontazione</button>

        <?php
            if($_SESSION["nominativo"] == "Amministratore")
            {
                echo"<button id='ButtAmm' class='ButtGenerale'>Funzioni Amministratore</button>";
            }
        ?>

        <p id='exit-link'><b><a id='exit-link' href='login.php' onclick="logout()">Ritorna all'accesso</a></b></p>
    </div>

</body>
<script src="script.js"> </script>
</html>
