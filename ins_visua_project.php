<?php
ini_set('session.gc_maxlifetime', 7200);
session_set_cookie_params(7200);
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

// Ottieni la parte locale dell'email (prima del @)
$emailParts = explode('@', $_SESSION['user_email']);
$localPart = $emailParts[0];

// Controllo se c'è un punto nella parte locale
if (strpos($localPart, '.') !== false) {
    list($nome, $cognome) = explode('.', $localPart);
    $nome = ucfirst($nome);
    $cognome = ucfirst($cognome);
    $_SESSION["nominativo"] = $cognome . " " . $nome;
} else {
    $_SESSION["nominativo"] = ucfirst($localPart); // Caso fallback
}

if ($_SESSION['user_email'] == "progettiptof@iispascal.it") {
    $_SESSION["nominativo"] = "Amministratore";
    header("Location: pagina_amm.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <!-- Meta tag viewport per rendere la pagina responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTOF Builder</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            margin: 0;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container-custom {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            text-align: center;
            margin: 20px;
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
            margin-top: 40px;
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
        /* Media queries per dispositivi piccoli */
        @media (max-width: 576px) {
            .container-custom {
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
    <div class="container-custom">
        <img src="img/logo.png" alt="Logo PTOF Builder" class="logo">
        <h1 class="title">Bentornato/a <?php echo htmlspecialchars($nome); ?></h1>
        <button id="ButtIns" class="btn-custom">Inserire progetto</button>
        <button id="ButtVisua" class="btn-custom">Visualizzare progetti</button>
        <button id="altraVisua" class="btn-custom" onclick="torna()">Visualizzazione veloce</button>
        <button id="ButtRendi" class="btn-custom">rendicontazione e modifica progetti</button>
        <?php
            if ($_SESSION["nominativo"] == "Amministratore") {
                echo "<button id='ButtAmm' class='btn-custom'>Funzioni Amministratore</button>";
            }
        ?>
        <a href="login.php" id="exit-link" class="logout-link" onclick="logout()">Ritorna all'accesso</a>
    </div>
    <!-- Bootstrap Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
        <?php
            require_once("db.php");
            $stm0 = $conn->prepare("SELECT id FROM docenteReferente WHERE nominativo = ?");
            $stm0->execute([$_SESSION["nominativo"]]);
            $idDoc = $stm0->fetch(PDO::FETCH_ASSOC);
            
            $isDocente = $idDoc ? 'true' : 'false';
        ?>
        document.addEventListener("DOMContentLoaded", function() {
            if (!<?php echo $isDocente; ?>) {
                document.getElementById("ButtIns").style.display = "none";
                document.getElementById("ButtRendi").style.display = "none";
            }
        });

        function torna(){
            window.location.href = "ulterioreResoconto.php";
        }
    </script>
</body>
</html>
