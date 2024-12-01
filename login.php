<?php
    ini_set('session.gc_maxlifetime', 7200);
    session_set_cookie_params(7200);

    // Distruggo un eventuale sessione precedente
    session_start();
    session_unset();
    session_destroy();

    // Creo una nuova sessione
    session_start();
    
    // Verifico se l'utente ha effettuato l'accesso con Google
    if (isset($_GET['access_token'])) {
        // Ottengo l'access token dalla query string
        $accessToken = $_GET['access_token'];

        // Ottengo le informazioni dell'utente utilizzando l'access token
        $userInfoEndpoint = 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $accessToken;
        $userData = json_decode(file_get_contents($userInfoEndpoint), true);

        // Se sono state ottenute le informazioni dell'utente
        if ($userData && isset($userData['email'])) {
            // Salvo l'email dell'utente nella sessione
            $_SESSION['user_email'] = $userData['email'];

            //reindirizzo verso la pagina iniziale
            header("Location: ins_visua_project.php");
            exit();
        } else {
            echo "Errore durante il recupero delle informazioni dell'utente.";
        }
    }

    // URL per il login con Google
    $loginUrl = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query(array(
        'response_type' => 'token',
        'client_id' => '529378518049-94dm7aej1qsjtu84natojb905i23h38e.apps.googleusercontent.com',
        'redirect_uri' => 'https://ptofbuilder.altervista.org/login.php', // dopo l'autenticazione, reindirizzo l'utente alla pagina che voglio
        'scope' => 'email',
    ));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Orbitron">
    <link href="style.css" rel="stylesheet" type="text/css">

    <script>
        //login con google # (tolgo #da url e lo sostituisco con ?)
        var hash = window.location.hash.slice(1);
        if(hash) {
            console.log(hash);
            var url = 'login.php' + '?' + hash;
            window.location.href = url;
        }
    </script>
    
</head>
<body>
    <div id="box">
        <img src="img/logo.png">
        <h1 id="titolo">PTOF BUILDER</h1>
    </div>

    <?php
        echo "<a href='$loginUrl'><img src='img/login.png' alt='Login con Google' id='login'></a>";
    ?>

</body>
</html>
