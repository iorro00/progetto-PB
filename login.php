<?php
    ini_set('session.gc_maxlifetime', 7200);
    session_set_cookie_params(7200);

    // Distruggo un'eventuale sessione precedente e ne creo una nuova
    session_start();
    session_unset();
    session_destroy();
    session_start();

    // Controllo se l'utente ha effettuato l'accesso con Google
    if (isset($_GET['access_token'])) {
        $accessToken = $_GET['access_token'];
        $userInfoEndpoint = 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $accessToken;
        $userData = json_decode(file_get_contents($userInfoEndpoint), true);

        if ($userData && isset($userData['email'])) {
            $_SESSION['user_email'] = $userData['email'];
            header("Location: ins_visua_project.php");
            exit();
        } else {
            echo "Errore durante il recupero delle informazioni dell'utente.";
        }
    }

    // Modifica: Aggiunto il parametro 'prompt=consent' per forzare il login
    $loginUrl = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query([
        'response_type' => 'token',
        'client_id' => '529378518049-94dm7aej1qsjtu84natojb905i23h38e.apps.googleusercontent.com',
        'redirect_uri' => 'https://ptofbuilder.altervista.org/login.php',
        'scope' => 'email',
        'prompt' => 'consent' // Forza la richiesta di login
    ]);
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Orbitron">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Orbitron', sans-serif;
            margin: 0;
        }
        .login-container {
            padding: 50px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        .login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 15px;
            background-color: #4285F4;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .login-btn:hover {
            background-color: #3867D6;
        }
        #logo {
            width: 200px;
            display: block;
            margin: 0 auto 30px;
        }
        h1 {
            text-align: center;
            color: #00245d;
            font-size: 32px;
            margin-bottom: 40px;
        }
    </style>
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
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-container text-center">
            <img src="img/logo.png" id="logo" alt="Logo PTOF Builder">
            <h1>PTOF BUILDER</h1>
            <?php
                echo "<a href='$loginUrl' class='login-btn'>
                        <img src='img/trasferimento.png' alt='Login con Google' style='height: 24px; margin-right: 10px;'>
                        Accedi con Google
                      </a>";
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
