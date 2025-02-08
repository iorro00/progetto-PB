<?php
require_once("db.php");

$numRis = $_POST["numRis"];
$stmt = $conn->prepare("SELECT nominativo FROM docenteReferente");
$stmt->execute();
$result = $stmt->fetchAll();

$data = "";

for($i=0; $i<$numRis; $i++) {
    // Card container for each resource
    $data .= '
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0 fw-bold">Risorsa interna ' . ($i+1) . '</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Nome Risorsa -->
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="risorse'.$i.'nome">Nome Risorsa '.($i+1).':</label>
                    <input type="text" class="form-control" id="risorse'.$i.'nome" name="risorse'.$i.'nome" list="docenti">
                    <datalist id="docenti">';
                    
    foreach($result as $row) {
        $data .= '<option value="'.$row["nominativo"].'">';
    }
    
    $data .= '</datalist>
                </div>

                <!-- Ruolo -->
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="risorse'.$i.'ruolo">Ruolo:</label>
                    <select class="form-select" id="risorse'.$i.'ruolo" name="risorse'.$i.'ruolo">
                        <option value="" disabled selected></option>
                        <option value="Docente potenziamento">Docente potenziamento</option>
                        <option value="Referente PCTO">Referente PCTO</option>
                        <option value="Docente interno">Docente interno</option>
                    </select>
                </div>

                <!-- Ore Progettazione -->
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="risorse'.$i.'progett">
                        Numero Ore di Progettazione:
                        <small class="d-block text-muted">Relative al docente indicato</small>
                    </label>
                    <input type="number" class="form-control" id="risorse'.$i.'progett" name="risorse'.$i.'progett" min="0" value="0">
                </div>

                <!-- Ore Curricolari -->
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="risorse'.$i.'oreCurr">
                        Numero di Ore di Docenza (curricolare):
                        <small class="d-block text-muted">Ore che non devono essere retribuite (parte del proprio orario di lezione)</small>
                    </label>
                    <input type="number" class="form-control" id="risorse'.$i.'oreCurr" name="risorse'.$i.'oreCurr" min="0" value="0">
                </div>

                <!-- Ore Extra-curricolari -->
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="risorse'.$i.'oreExtraCurr">
                        Numero di ore di Docenza (extracurricolare):
                        <small class="d-block text-muted">Ore da retribuire (eccedono il proprio orario di lezione)</small>
                    </label>
                    <input type="number" class="form-control" id="risorse'.$i.'oreExtraCurr" name="risorse'.$i.'oreExtraCurr" min="0" value="0">
                </div>

                <!-- Ore Sorveglianza -->
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="risorse'.$i.'sorveglianza">
                        Numero di Ore di Sorveglianza:
                        <small class="d-block text-muted">In orario extracurricolare (da retribuire)</small>
                    </label>
                    <input type="number" class="form-control" id="risorse'.$i.'sorveglianza" name="risorse'.$i.'sorveglianza" min="0" value="0">
                </div>
            </div>
        </div>
    </div>';
}

echo $data;
?>