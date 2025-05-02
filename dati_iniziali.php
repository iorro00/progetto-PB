<?php
    ini_set('session.gc_maxlifetime', 7200);
    session_set_cookie_params(7200);
    session_start();
    // Verifico se l'utente è loggato, altrimenti reindirizzo alla pagina di accesso
    if (!isset($_SESSION['user_email'])) {
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Inserimento dati</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>
                #page-title {
                    font-family: 'Playfair Display', serif;
                    font-size: 1.7rem;
                    color: #2c3e50;
                    font-weight: 700;
                    margin-bottom: 30px;
                    letter-spacing: -0.5px;
                }
                .top-bar {
                background-color: #00245d;
                color: white;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 80px; /* Altezza aumentata */
                z-index: 1000;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .top-bar button {
                font-size: 1.8rem; /* Testo del pulsante più grande */
                background: none;
                border: none;
                color: white;
                cursor: pointer;
            }

            .top-bar .logo {
                width: 60px; /* Logo più grande */
                height: 50px;
                
                object-fit: cover;
                margin-left: auto; /* Sposta il logo sulla destra */
            }

            .top-bar p {
                font-size: 1.5rem; /* Testo del titolo più grande */
                font-weight: bold;
                margin: 0;
                text-align: center;
                flex-grow: 1;
            }

            input.form-control, select.form-select {
                height: 38px; /* Stessa altezza per entrambi */
                padding: 6px 12px; /* Uniforma il padding */
                font-size: 16px; /* Assicura stessa dimensione del testo */
            }

            .card-header {
                background-color: #00245d;
            }

            .is-invalid { border-color: #dc3545; }
        </style>
    </head>
<body>
    <!-- < ?php $code = -->
    <div id="top-bar" class="top-bar d-flex align-items-center p-3">
            <button id="back-btn" class="btn btn-light me-3" onclick="tornaIndietro()">←</button>
            <p id="page-title" class="m-0 mx-auto text-white">INSERIMENTO DI UN PROGETTO</p>
            <img src="img/logo.png" alt="Logo" class="logo">
        </div>
        <br><br><br><br>
        <form method="post" action="add.php" id="mainForm" class="container mt-5">
    <div class="card mb-4 shadow-sm" id="colonna">
        <div class="card-header text-white">
            <h5 class="mb-0">DATI INIZIALI PROGETTO</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label fw-bold">*Titolo:</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="dip" class="form-label fw-bold">*Dipartimento:</label>
                    <select id="dip" name="dip" class="form-select" required>
                        <option value="" disabled selected>Seleziona un dipartimento</option>
                        <option value="1">Scienze</option>
                        <option value="2">Informatica</option>
                        <option value="3">Lingue Straniere</option>
                        <option value="4">Disc Artistiche</option>
                        <option value="5">Matematica</option>
                        <option value="6">Diritto ed Economia</option>
                        <option value="7">Scienze Motorie</option>
                        <option value="8">Religione</option>
                        <option value="9">Disc umanistiche</option>
                        <option value="10">Nessuno</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm" id="colonna2" style="display:none;">
        <div class="card-header text-white">
            <h5 class="mb-0">CLASSI COINVOLTE</h5>
        </div>
        <div class="card-body">
            <div id="indirizzo" class="mb-4">
                <h6 class="fw-bold mb-3">Indirizzo</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="indirizzo" value="Informatico" id="informatico" onchange="mostraClassi()">
                            </div>
                            <input type="text" class="form-control bg-light" value="Informatico" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="indirizzo" value="Relazioni Internazionali" id="Relazioni_internazionali" onchange="mostraClassi()">
                            </div>
                            <input type="text" class="form-control bg-light" value="Relazioni Internazionali" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="indirizzo" value="Grafico" id="Grafico" onchange="mostraClassi()">
                            </div>
                            <input type="text" class="form-control bg-light" value="Grafico" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="indirizzo" value="Scienze Applicate" id="Scienze_applicate" onchange="mostraClassi()">
                            </div>
                            <input type="text" class="form-control bg-light" value="Scienze Applicate" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div id="annata" class="mb-4">
                <h6 class="fw-bold mb-3">Annata</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="annata" value="1" id="annata1" onchange="mostraClassi()">
                            </div>
                            <input type="text" class="form-control bg-light" value="Prima" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="annata" value="2" id="annata2" onchange="mostraClassi()">
                            </div>
                            <input type="text" class="form-control bg-light" value="Seconda" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="annata" value="3" id="annata3" onchange="mostraClassi()">
                            </div>
                            <input type="text" class="form-control bg-light" value="Terza" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="annata" value="4" id="annata4" onchange="mostraClassi()">
                            </div>
                            <input type="text" class="form-control bg-light" value="Quarta" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="annata" value="5" id="annata5" onchange="mostraClassi()">
                            </div>
                            <input type="text" class="form-control bg-light" value="Quinta" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div id="oreMatPom" class="mb-4">
                <h6 class="fw-bold mb-3">Ore svolte per ogni classe o gruppo</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="oreMat" class="form-label">Ore svolte durante la mattina:</label>
                        <input type="number" id="oreMat" name="oreMat" min="0" value="0" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="orePom" class="form-label">Ore svolte durante il pomeriggio:</label>
                        <input type="number" id="orePom" name="orePom" min="0" value="0" class="form-control">
                    </div>
                </div>
            </div>

            <div id="origineProg" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <label for="originePrj" class="form-label">È un progetto:</label>
                        <select id="originePrj" name="originePrj" class="form-select">
                            <option value="Su base volontaria">Su base volontaria</option>
                            <option value="Rivolto alla classe">Rivolto alla classe</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold mb-3">Classi filtrate</h6>
                <div id="classi-selezionate" class="mb-3">
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" id="selectAll" onclick="selezionaCheckbox()">Seleziona tutti</button>
                    <button type="button" class="btn btn-outline-primary" id="deselectAll" onclick="deselezionaCheckbox()" style="display:none">Deseleziona tutti</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Keep similar card structure for other columns (3-7) -->
	<div class="card mb-4 shadow-sm" id="colonna3" style="display:none;">
        <div class="card-header text-white">
            <h5 class="mb-0">Competenze</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tbComp">
                    <tbody>
                        <tr>
                            <td class="col-1">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="comp1" name="comp1" onchange="handleCheckboxChange2(this)" value="1">
                                </div>
                            </td>
                            <td><label class="form-check-label" for="comp1">Competenza alfabetica funzionale</label></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="comp2" name="comp2" onchange="handleCheckboxChange2(this)" value="2">
                                </div>
                            </td>
                            <td><label class="form-check-label" for="comp2">Competenza multilinguistica</label></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="comp3" name="comp3" onchange="handleCheckboxChange2(this)" value="3">
                                </div>
                            </td>
                            <td><label class="form-check-label" for="comp3">Competenza matematica e competenza in scienze, tecnologie e ingegneria</label></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="comp4" name="comp4" onchange="handleCheckboxChange2(this)" value="4">
                                </div>
                            </td>
                            <td><label class="form-check-label" for="comp4">Competenza digitale</label></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="comp5" name="comp5" onchange="handleCheckboxChange2(this)" value="5">
                                </div>
                            </td>
                            <td><label class="form-check-label" for="comp5">Competenza personale, sociale e capacità di imparare a imparare</label></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="comp6" name="comp6" onchange="handleCheckboxChange2(this)" value="6">
                                </div>
                            </td>
                            <td><label class="form-check-label" for="comp6">Competenza in materia di cittadinanza</label></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="comp7" name="comp7" onchange="handleCheckboxChange2(this)" value="7">
                                </div>
                            </td>
                            <td><label class="form-check-label" for="comp7">Competenza imprenditoriale</label></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="comp8" name="comp8" onchange="handleCheckboxChange2(this)" value="8">
                                </div>
                            </td>
                            <td><label class="form-check-label" for="comp8">Competenza in materia di consapevolezza ed espressione culturali</label></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm" id="colonna4" style="display:none;">
        <div class="card-header text-white">
            <h5 class="mb-0">Riferimenti al PTOF</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="strutt" class="form-label fw-bold">*Strutturale:</label>
                    <select id="strutt" name="strutt" class="form-select">
                        <option value="" disabled selected></option>
                        <option value="Si">Sì</option>
                        <option value="No">No</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="percorsi" class="form-label fw-bold">*Fa parte del percorso di PCTO:</label>
                    <select id="percorsi" name="percorsi" class="form-select">
                        <option value="" disabled selected></option>
                        <option value="Si">Sì</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="orient" class="form-label fw-bold">*Rientra nelle ore di orientamento:</label>
                    <select id="orient" name="orient" class="form-select">
                        <option value="" disabled selected></option>
                        <option value="Si">Sì</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm" id="colonna5" style="display:none;">
        <div class="card-header text-white">
            <h5 class="mb-0">Aspetti didattici del progetto</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="contesto" class="form-label fw-bold">*Breve analisi del contesto in cui si intende operare e dei bisogni rilevati:</label>
                <textarea name="contesto" id="contesto" class="form-control" rows="3" placeholder="Scrivi qui..."></textarea>
            </div>

            <div class="mb-3">
                <label for="obb" class="form-label fw-bold">*Obiettivi attesi:</label>
                <textarea name="obb" id="obb" class="form-control" rows="3" placeholder="Scrivi qui..."></textarea>
            </div>

            <div class="mb-3">
                <label for="attiv" class="form-label fw-bold">*Attività previste (descrizione accurata, ma sintetica, delle attività che ci si propone di svolgere):</label>
                <textarea name="attiv" id="attiv" class="form-control" rows="3" placeholder="Scrivi qui..."></textarea>
            </div>

            <div class="mb-3">
                <label for="metodi" class="form-label fw-bold">*Metodologia e strumenti:</label>
                <textarea name="metodi" id="metodi" class="form-control" rows="3" placeholder="Scrivi qui..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">*Tempi di svolgimento:</label>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tbMesi">
                        <tr>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Settembre" name="mese[]" id="gen">
                                <label class="form-check-label" for="gen">Settembre</label>
                            </div></td>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Ottobre" name="mese[]" id="feb">
                                <label class="form-check-label" for="feb">Ottobre</label>
                            </div></td>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Novembre" name="mese[]" id="mar">
                                <label class="form-check-label" for="mar">Novembre</label>
                            </div></td>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Dicembre" name="mese[]" id="apr">
                                <label class="form-check-label" for="apr">Dicembre</label>
                            </div></td>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Gennaio" name="mese[]" id="mag">
                                <label class="form-check-label" for="mag">Gennaio</label>
                            </div></td>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Febbraio" name="mese[]" id="giu">
                                <label class="form-check-label" for="giu">Febbraio</label>
                            </div></td>
                        </tr>
                        <tr>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Marzo" name="mese[]" id="lug">
                                <label class="form-check-label" for="lug">Marzo</label>
                            </div></td>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Aprile" name="mese[]" id="ago">
                                <label class="form-check-label" for="ago">Aprile</label>
                            </div></td>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Maggio" name="mese[]" id="set">
                                <label class="form-check-label" for="set">Maggio</label>
                            </div></td>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Giugno" name="mese[]" id="ott">
                                <label class="form-check-label" for="ott">Giugno</label>
                            </div></td>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Luglio" name="mese[]" id="nov">
                                <label class="form-check-label" for="nov">Luglio</label>
                            </div></td>
                            <td><div class="form-check">
                                <input type="checkbox" class="form-check-input" value="Agosto" name="mese[]" id="dic">
                                <label class="form-check-label" for="dic">Agosto</label>
                            </div></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mb-3">
                <label for="luog" class="form-label fw-bold">*Luoghi di svolgimento:</label>
                <textarea name="luog" id="luog" class="form-control" rows="3" placeholder="Scrivi qui..."></textarea>
            </div>

            <div class="mb-3">
                <label for="verifica" class="form-label fw-bold">*Modalità di verifica in itinere e finale:</label>
                <textarea name="verifica" id="verifica" class="form-control" rows="3" placeholder="Scrivi qui..."></textarea>
            </div>

            <div class="mb-3">
                <label for="document" class="form-label fw-bold">*Documentazione prevista:</label>
                <textarea name="document" id="document" class="form-control" rows="3" placeholder="Scrivi qui..."></textarea>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm" id="colonna6" style="display:none;">
        <div class="card-header text-white">
            <h5 class="mb-0">Risorse Interne</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive mb-4">
                <table class="table table-bordered" id="tbAreaComp">
                    <thead class="table-light">
                        <tr>
                            <th></th>
                            <th>Progettazione</th>
                            <th>Docenza</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Docente Potenziamento</td>
                            <td class="text-center"><input type="checkbox" class="form-check-input" name="progettazione_potenziamento" value="progettazione"></td>
                            <td class="text-center"><input type="checkbox" class="form-check-input" name="docenza_potenziamento" value="docenza"></td>
                        </tr>
                        <tr>
                            <td>Referente PCTO</td>
                            <td class="text-center"><input type="checkbox" class="form-check-input" name="progettazione_pcto" value="progettazione"></td>
                            <td class="text-center"><input type="checkbox" class="form-check-input" name="docenza_pcto" value="docenza"></td>
                        </tr>
                        <tr>
                            <td>Docente Interno</td>
                            <td class="text-center"><input type="checkbox" class="form-check-input" name="progettazione_interno" value="progettazione"></td>
                            <td class="text-center"><input type="checkbox" class="form-check-input" name="docenza_interno" value="docenza"></td>
                        </tr>
                        <tr>
                            <td>Docente Esterno</td>
                            <td class="text-center"><input type="checkbox" class="form-check-input" name="progettazione_esterno" value="progettazione"></td>
                            <td class="text-center"><input type="checkbox" class="form-check-input" name="docenza_esterno" value="docenza"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
<div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="numRisorse" class="form-label fw-bold">Quante risorse interne vuoi aggiungere?</label>
                    <input type="number" id="numRisorse" name="numRisorse" min="1" value="1" class="form-control">
                </div>
                <div class="col-md-6">
                    <button type="button" id="ButtGeneraRis" onclick="generaRisorse()" class="btn btn-primary w-100">
                        Genera Risorse
                    </button>
                </div>
            </div>
            
                    <div id="risorse" class="row g-3 mt-4">
                <!-- Contenuto generato dinamicamente -->
                    </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm" id="colonna7" style="display:none;">
        <div class="card-header text-white">
            <h5 class="mb-0">Risorse Esterne</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="numRisorseExt" class="form-label fw-bold">Quante risorse esterne vuoi aggiungere?</label>
                    <input type="number" id="numRisorseExt" name="numRisorseExt" min="0" value="0" class="form-control">
                </div>
                <div class="col-md-6">
                    <button type="button" id="ButtGeneraRisExt" onclick="generaRisorseExt()" class="btn btn-primary w-100">
                        Genera Risorse
                    </button>
                </div>
            </div>

            <div id="risorseExt" class="mt-4">
                <!-- Contenuto generato dinamicamente -->
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4 mb-5">
        <button type="button" id="indietro" class="btn btn-secondary" style="display:none">
            <i class="bi bi-arrow-left"></i> Indietro
        </button>
        <button type="button" id="avanti" class="btn btn-primary" >
            Avanti <i class="bi bi-arrow-right"></i>
        </button>
        <button type="submit" id="submit" class="btn btn-success" style="display:none">
            <i class="bi bi-check-circle"></i> Invia
        </button>
    </div>
</form>  
</body>
<script src="script.js">
</script>
<script>
    function tornaIndietro() {
        window.location.href = "ins_visua_project.php";
    }

</script>
</html>