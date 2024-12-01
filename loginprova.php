<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Orbitron">
        <link href="style.css" rel="stylesheet" type="text/css">

        <style>
            #g_id_onload,
            .g_id_signin {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px; /* Larghezza desiderata */
            height: 200px; /* Altezza desiderata */
            }
        </style>
    </head>
    <body>
        <div id="box">
            <img src="img/logo.png">
            <h1 id="titolo">PTOF BUILDER</h1>
        </div>

        <?php
          echo '<!-- Includo lo script di Google Sign-In (preso dalla guida di google)-->
                <script src="https://accounts.google.com/gsi/client" async defer></script>
            
                <!-- Aggiungo il bottone "Accedi con Google" -->
                <div id="g_id_onload"
                    data-client_id="529378518049-94dm7aej1qsjtu84natojb905i23h38e.apps.googleusercontent.com"
                    data-ux_mode="redirect"
                    data-login_uri="https://ptofbuilder.altervista.org/ins_visua_project.php"
                    >
                </div>
                <div class="g_id_signin" 
                    data-type="standard" 
                    data-size="large"	
                    data-logo_alignment="center" 
                    data-width="300" 
                    data-shape="rectangular" 
                    data-theme="outline">
                </div>'
        ?>
    </body>
</html>
