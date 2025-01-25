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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Orbitron">
        <link href='https://fonts.googleapis.com/css?family=Merriweather Sans' rel='stylesheet'>
        <link href="style.css" rel="stylesheet" type="text/css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
<body>
    <!-- < ?php $code = -->
   <div id="box3">
        <img id="logo" src="img/logo.png" alt="Immagine non trovata">
        <h1><a id='pb-link' href='ins_visua_project.php'>PB</a></h1>
    </div>
	
<form method="post" action="add.php" id="mainForm">
    <div class="colonna" id="colonna">
        <p>Dati iniziali progetto</p>

        <div id="cont1">
            <label for="title">*Titolo:</label>
            <input type="text" id="title" name="title">  
        </div>

        <!-- <div id="cont2">
            <label for="doc">*Docente referente:</label>
            <input type="text" id="doc" name="doc" list="docenti">
            <datalist id="docenti">
                <?php
                    /*require_once("db.php");
                    $stmt = $conn->prepare("SELECT nominativo FROM docenteReferente");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    
                    $data = '';

                    foreach($result as $row) {
                        $data = '<option value="'.$row["nominativo"].'">';
                        echo $data;
                    }  */
                ?>
            </datalist>
        </div> -->
        
        <div id="cont3">
            <label for="dip">*Dipartimento:</label>
                <select id="dip" name="dip">
                    <option value="" disabled selected></option>
                    <option value="1">Scienze</option>
                    <option value="2">Informatica</option>
                    <option value="3">Lingue Straniere</option>
                    <option value="4">Disc Artistiche</option>
                    <option value="5">Matematica</option>
                    <option value="6">Diritto ed Economia </option>
                    <option value="7">Scienze Motorie</option>
                    <option value="8"> Religione</option>
                    <option value="9">Disc umanistiche</option>
                    <option value="10">nessuno</option>
                </select> 
        </div>
    </div>
        
    <div class="colonna" id="colonna2" style="display:none;">
        <p>Classi coinvolte</p>
        <div id="indirizzo">
        <p class="subTit">Indirizzo</p>
        	<div class="content">
              <table>
                  <tr>
                      <td><label for="informatico">Informatico:</label></td>
                      <td><input type="checkbox" id="informatico" name="indirizzo" value="Informatico" onchange="mostraClassi()"></td>
                  </tr>
                  <tr>
                      <td><label for="relazioni_internazionali">Relazioni Int:</label></td>
                      <td><input type="checkbox" id="relazioni_internazionali" name="indirizzo" value="Relazioni Internazionali" onchange="mostraClassi()"></td>
                  </tr>
                  <tr>
                      <td><label for="grafico">Grafico:</label></td>
                      <td><input type="checkbox" id="grafico" name="indirizzo" value="Grafico" onchange="mostraClassi()"></td>
                  </tr>
                  <tr>
                      <td><label for="scienze_applicate">Scienze App:</label></td>
                      <td><input type="checkbox" id="scienze_applicate" name="indirizzo" value="Scienze Applicate" onchange="mostraClassi()"></td>
                  </tr>
    			</table>
             </div>
        </div>

    <div id="annata">
    	<p class="subTit">Annata</p>
    	<div class="content">
            <table>
                <tr>
                    <td><label for="annata1">I:</label></td>
                    <td><input type="checkbox" id="annata1" name="annata" value="1" onchange="mostraClassi()"></td>
                </tr>
                <tr>
                    <td><label for="annata2">II:</label></td>
                    <td><input type="checkbox" id="annata2" name="annata" value="2" onchange="mostraClassi()"></td>
                </tr>
                <tr>
                    <td><label for="annata3">III:</label></td>
                    <td><input type="checkbox" id="annata3" name="annata" value="3" onchange="mostraClassi()"></td>
                </tr>
                <tr>
                    <td><label for="annata4">IV:</label></td>
                    <td><input type="checkbox" id="annata4" name="annata" value="4" onchange="mostraClassi()"></td>
                </tr>
                <tr>
                    <td><label for="annat5">V:</label></td>
                    <td><input type="checkbox" id="annat5" name="annata" value="5" onchange="mostraClassi()"></td>
                </tr>
            </table>
        </div>
    </div>

 <div id="oreMatPom">
    	<p class="subTit">Ore svolte per ogni classe o gruppo</p>
    	<div class="content">
          <table>
            <tr>
                <td><label for="oreMat">Ore svolte durante la mattina:</label></td>
                <td><input type="number" id="oreMat" name="oreMat" min="0" value="0"></td>
            </tr>
            <tr>
                <td><label for="orePom">Ore svolte durante il pomeriggio:</label></td>
                <td><input type="number" id="orePom" name="orePom" min="0" value="0"></td>
            </tr>
          </table>
         </div>
  </div>
  
  <div id="origineProg">
    	<div class="content">
          <table>
            <tr>
                <td><label for="originePrj">È un progetto:</label></td>
                <td><select id="originePrj" name="originePrj" value="Su base volontaria">
                        <option value="Su base volontaria">Su base volontaria</option>
                        <option value="Rivolto alla classe">Rivolto alla classe</option>
                    </select>
               </td>
            </tr>
          </table>
         </div>
  </div>
    
    <p class="subTit">Classi filtrate</p>
    <div id="classi-selezionate">
    	
    </div>
	<p id="selectAll" onclick="selezionaCheckbox()" >Seleziona tutti</p>
    <p id="deselectAll" onclick="deselezionaCheckbox()" style="display:none" >Deseleziona tutti</p>
</div>
    
    

    <div class="colonna" id="colonna3" style="display:none;">
            <p>Competenze</p>
            <div class="check">
                 <table id="tbComp">
                    <tr>
                        <td><input type="checkbox" id="comp1" name="comp1" onchange="handleCheckboxChange2(this)" value="1"></td>
                        <td><label class="comp" for="comp1">Competenza alfabetica funzionale</label></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="comp2" name="comp2" onchange="handleCheckboxChange2(this)" value="2"></td>
                        <td><label class="comp" for="comp2">Competenza multilinguistica</label></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="comp3" name="comp3" onchange="handleCheckboxChange2(this)" value="3"></td>
                        <td><label class="comp" for="comp3">Competenza matematica e competenza in scienze, tecnologie e ingegneria</label></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="comp4" name="comp4" onchange="handleCheckboxChange2(this)" value="4"></td>
                        <td><label class="comp" for="comp4">Competenza digitale</label></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="comp5" name="comp5" onchange="handleCheckboxChange2(this)" value="5"></td>
                        <td><label class="comp" for="comp5">Competenza personale, sociale e capacità di imparare a imparare</label></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="comp6" name="comp6" onchange="handleCheckboxChange2(this)" value="6"></td>
                        <td><label class="comp" for="comp6">Competenza in materia di cittadinanza</label></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="comp7" name="comp7" onchange="handleCheckboxChange2(this)" value="7"></td>
                        <td><label class="comp" for="comp7">Competenza imprenditoriale</label></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="comp8" name="comp8" onchange="handleCheckboxChange2(this)" value="8"></td>
                        <td><label class="comp" for="comp8">Competenza in materia di consapevolezza ed espressione culturali</label></td>
                    </tr>
                </table>
        	</div>
    </div>

    <div class="colonna" id="colonna4" style="display:none;">
        <p>Riferimenti al PTOF</p>
        
        <div id="cont2">
            <label for="strutt">*Strutturale:</label>
            <select id="strutt" name="strutt">
                <option value="" disabled selected></option>
                <option value="Si">Sì</option>
                <option value="No">No</option>
            </select> 
        </div>
        
        <div id="cont3">
            <label for="percorsi">*Fa parte del percorso di PCTO:</label>
            <select id="percorsi" name="percorsi">
                <option value="" disabled selected></option>
                <option value="Si">Sì</option>
                <option value="No">No</option>
            </select> 
        </div>

        <div id="cont4">
            <label for="orient">*Rientra nelle ore di orientamento:</label>
            <select id="orient" name="orient">
                <option value="" disabled selected></option>
                <option value="Si">Sì</option>
                <option value="No">No</option>
            </select> 
        </div>
    </div>
    
    <div class="colonna" id="colonna5" style="display:none;">
        <p>Aspetti didattici del progetto</p>
        
        <label for="contesto">*Breve analisi del contesto in cui si intende operare e dei bisogni rilevati:</label><br>
		<textarea name="contesto" id="contesto" placeholder="Scrivi qui..."></textarea>
        
        <label for="obb">*Obiettivi attesi:</label><br>
		<textarea name="obb" id="obb" placeholder="Scrivi qui..."></textarea>
        
        <label for="attiv">*Attività previste (descrizione accurata, ma sintetica, delle attività che ci si propone di svolgere):</label><br>
		<textarea name="attiv" id="attiv" placeholder="Scrivi qui..."></textarea>
        
        <label for="metodi">*Metodologia e strumenti:</label><br>
		<textarea name="metodi" id="metodi" placeholder="Scrivi qui..."></textarea>

        <label>*Tempi di svolgimento:</label><br>
        <table id="tbMesi">
            <tr>
                <td><label class="checkbox-label"><input type="checkbox" value="Gennaio" name="mese[]"> Gennaio</label></td>
                <td><label class="checkbox-label"><input type="checkbox" value="Febbraio" name="mese[]"> Febbraio</label></td>
                <td><label class="checkbox-label"><input type="checkbox" value="Marzo" name="mese[]"> Marzo</label></td>
                <td><label class="checkbox-label"><input type="checkbox" value="Aprile" name="mese[]"> Aprile</label></td>
                <td><label class="checkbox-label"><input type="checkbox" value="Maggio" name="mese[]"> Maggio</label></td>
                <td><label class="checkbox-label"><input type="checkbox" value="Giugno" name="mese[]"> Giugno</label></td>
            </tr>
            <tr>
                <td><label class="checkbox-label"><input type="checkbox" value="Luglio" name="mese[]"> Luglio</label></td>
                <td><label class="checkbox-label"><input type="checkbox" value="Agosto" name="mese[]"> Agosto</label></td>
                <td><label class="checkbox-label"><input type="checkbox" value="Settembre" name="mese[]"> Settembre</label></td>
                <td><label class="checkbox-label"><input type="checkbox" value="Ottobre" name="mese[]"> Ottobre</label></td>
                <td><label class="checkbox-label"><input type="checkbox" value="Novembre" name="mese[]"> Novembre</label></td>
                <td><label class="checkbox-label"><input type="checkbox" value="Dicembre" name="mese[]"> Dicembre</label></td>
            </tr>
        </table>
        
        
        <label for="luog">*Luoghi di svolgimento:</label><br>
		<textarea name="luog" id="luog" placeholder="Scrivi qui..."></textarea>
        
        <label for="verifica">*Modalità di verifica in itinere e finale (confronto fra obiettivi attesi e obiettivi raggiunti; analisi del processo e del prodotto):</label><br>
		<textarea name="verifica" id="verifica" placeholder="Scrivi qui..."></textarea>
        
        <label for="document">*Documentazione prevista (mostra prodotti realizzati, immagini foto/video, saggio/ rappresentazione finale...):</label><br>
		<textarea name="document" id="document" placeholder="Scrivi qui..."></textarea>
    </div>
    
    <div class="colonna" id="colonna6" style="display:none;">
        <p>Risorse Interne</p>
        
        <div id="areaComp">
        	<table id="tbAreaComp">
            <thead>
                <tr>
                    <th></th>
                    <th>Progettazione</th>
                    <th>Docenza</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Docente Potenziamento</td>
                    <td><input type="checkbox" name="progettazione_potenziamento" value="progettazione"></td>
                    <td><input type="checkbox" name="docenza_potenziamento" value="docenza"></td>
                </tr>
                <tr>
                    <td>Referente PCTO</td>
                    <td><input type="checkbox" name="progettazione_pcto" value="progettazione"></td>
                    <td><input type="checkbox" name="docenza_pcto" value="docenza"></td>
                </tr>
                <tr>
                    <td>Docente Interno (no potenziamento, no referente PCTO)</td>
                    <td><input type="checkbox" name="progettazione_interno" value="progettazione"></td>
                    <td><input type="checkbox" name="docenza_interno" value="docenza"></td>
                </tr>
                <tr>
                    <td>Docente Esterno</td>
                    <td><input type="checkbox" name="progettazione_esterno" value="progettazione"></td>
                    <td><input type="checkbox" name="docenza_esterno" value="docenza"></td>
                </tr>
            </tbody>
        </table>
        </div>
        
       	<label id="lbRis" for="numRisorse">Quante risorse interne vuoi aggiungere?</label>
        <input type="number" id="numRisorse" name="numRisorse" min="1" value="1">
        <button id="ButtGeneraRis" onclick="generaRisorse()">Genera Risorse</button>
        
        <div id="risorse">
        
        </div>

    </div>
    
    <div class="colonna" id="colonna7" style="display:none;">
        <p>Risorse Esterne</p>
        
       	<label for="numRisorseExt">Quante risorse esterne vuoi aggiungere?</label>
        <input type="number" id="numRisorseExt" name="numRisorseExt" min="0" value="0">
        <button id="ButtGeneraRis" onclick="generaRisorseExt()">Genera Risorse</button>
        
        <div id="risorseExt">
        
        </div>

    </div>
    

    
    <div id="buttons">
      <p id="indietro" style="display:none"><b><a>&lt;</a></b></p>
      <p id="avanti"><b><a>></a></b></p>
      <input type="submit" id="submit" value="Invia" style="display:none">
    </div>
</form>  
    <p id='exit-link'><b><a id='exit-link' href='ins_visua_project.php'>Ritorna alla home</a></b></p>
</body>
<script src="script.js">
</script>
</html>