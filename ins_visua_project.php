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
    
    if($_SESSION['user_email'] == "progettiptof@iispascal.it") {
        $nome = "Amministratore";
        $_SESSION["nominativo"] = $nome;
        header("Location: pagina_amm.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTOF Builder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            text-align: center;
        }
        .logo {
            width: 140px;
            margin: 0 auto 30px;
            transition: transform 0.3s ease;
        }
        .logo:hover {
            transform: scale(1.05);
        }
        .title {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }
        .btn-custom {
            background-color: #00245d;
            color: white;
            border: none;
            margin: 12px 0;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: 'Source Sans Pro', sans-serif;
            transition: all 0.3s ease-in-out;
            width: 100%;
            letter-spacing: 0.3px;
        }
        .btn-custom:hover {
            background-color: #0b5ed7;
            box-shadow: 0 8px 15px rgba(13, 110, 253, 0.2);
            transform: translateY(-2px);
        }
        .logout-link {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            margin-top: 80px;
            color: #2c3e50;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            position: relative;
            transition: color 0.3s ease;
        }
        .logout-link:after {
            content: '';
            position: absolute;
            width: 100%;
            transform: scaleX(0);
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #0d6efd;
            transform-origin: bottom right;
            transition: transform 0.3s ease-out;
        }
        .logout-link:hover {
            color: #0d6efd;
        }
        .logout-link:hover:after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }
        @media (max-width: 576px) {
            .container {
                padding: 25px;
                margin: 15px;
            }
            .title {
                font-size: 2rem;
            }
            .btn-custom {
                font-size: 1rem;
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="img/logo.png" alt="Logo PTOF Builder" class="logo">
        <h1 class="title">Bentornato/a <?php echo $nome; ?></h1>
        <button id="ButtIns" class="btn-custom">Inserire progetto</button>
        <button id="ButtVisua" class="btn-custom">Visualizzare progetti</button>
        <button id="ButtRendi" class="btn-custom">Visualizzare rendicontazione</button>
        <?php
            if ($_SESSION["nominativo"] == "Amministratore") {
                echo "<button id='ButtAmm' class='btn-custom'>Funzioni Amministratore</button>";
            }
        ?>
        <a href="login.php" id="exit-link" class="logout-link" onclick="logout()">Ritorna all'accesso</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>