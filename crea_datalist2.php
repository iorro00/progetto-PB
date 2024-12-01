<?php
  require_once("db.php");
  
  $numRis = $_POST["numRis"];
  
  $stmt = $conn->prepare("SELECT nominativo FROM docenteReferente");
  $stmt->execute();
  $result = $stmt->fetchAll();
  
  $data = "";
  
  for($i=0;$i<$numRis;$i++){
  	$data.='<p>Risorsa interna '.($i+1).'</p>
           <label class="labelRis" for="risorse'.$i.'nome">Nome Risorsa'.($i+1).':</label>
           <input type="text" id="risorse'.$i.'nome" name="risorse'.$i.'nome" list="docenti">
            <datalist id="docenti">';
    foreach($result as $row) {
      $data .= '<option value="'.$row["nominativo"].'">';
    }
    $data .='</datalist>';
    $data.='<label class="labelRis" for="ruolo">Ruolo:</label>
                         <select id="risorse'.$i.'ruolo" name="risorse'.$i.'ruolo" value="Docente potenziamento">
                             <option value="" disabled selected></option>
                             <option value="Docente potenziamento">Docente potenziamento</option>
                             <option value="Referente PCTO">Referente PCTO</option>
                             <option value="Docente interno">Docente interno</option>
                         </select> ';
    $data.= '<label class="labelRis" for="progett">Numero Ore di Progettazione relative al docente indicato:</label>
                         <input type="number" id="risorse'.$i.'progett" name="risorse'.$i.'progett" min="0" value="0">
 
                         <label class="labelRis" for="oreCurr">Numero di Ore di Docenza relative al docente indicato (in orario curricolare,
                             cioè ore che non devono essere retribuite perchè fanno parte del proprio orario
                             di lezione):</label>
                         <input type="number" id="risorse'.$i.'oreCurr" name="risorse'.$i.'oreCurr" min="0" value="0">
 
                         <label class="labelRis" for="oreExtraCurr">Numero di ore di Docenza relative al docente indicato (in orario
                             extracurricolare: cioè quelle che devono essere retribuite perché eccedono il
                             proprio orario di lezione):</label>
                         <input type="number" id="risorse'.$i.'oreExtraCurr" name="risorse'.$i.'oreExtraCurr" min="0" value="0">
 
                         <label class="labelRis" for="sorveglianza">Numero di Ore di Sorveglianza relative al docente indicato (in orario
                             extracurricolare, cioè quelle che devono essere retribuite perchè eccedono il
                             proprio orario di lezione):</label>
                         <input type="number" id="risorse'.$i.'sorveglianza" name="risorse'.$i.'sorveglianza" min="0" value="0">';
  }
  
  
   echo $data;

?>
