if (window.location.href.includes('ins_visua_project.php')){

    const createButt = document.getElementById('ButtIns');
    const createButt2 = document.getElementById('ButtVisua');
    const createButt3 = document.getElementById('ButtRendi');
    const createButt4 = document.getElementById('ButtAmm');
     
    createButt.addEventListener('click', function() {
        setTimeout(function() {
            window.location.href = 'dati_iniziali.php';
        }, 400);    
    });
     
    createButt2.addEventListener('click', function() {
        setTimeout(function() {
            window.location.href = 'visualizzazione.php';
        }, 400);    
    }); 

    createButt3.addEventListener('click', function() {
        setTimeout(function() {
            window.location.href = 'rendicontazione.php';
        }, 400);    
    }); 

    createButt4.addEventListener('click', function() {
        setTimeout(function() {
            window.location.href = 'pagina_amm.php';
        }, 400);    
    });
}
 
function generaRisorse() {
event.preventDefault();
    // Ottieni il numero di risorse dall'input
    var numRisorse = parseInt(document.getElementById('numRisorse').value);
    var datalist = "";
    // Genera i form dinamicamente
    var ris = document.getElementById('risorse');
    ris.innerHTML = ''; // Pulisci il contenuto precedente

	jQuery.ajax({
        type: 'POST',
        url: "crea_datalist2.php",
        dataType: 'html',
        data:{
        	'numRis':numRisorse,
        },
        success: function(response) {
       	 	// Aggiungi direttamente la risposta HTML al tuo elemento
        	ris.innerHTML += response;
    	},
        error: function(xhr, status, error) {
               console.error("Errore AJAX: " + status + " - " + error);
        }
    });
}

 
function generaRisorseExt() {
            // Ottieni il numero di risorse dall'input
            var numRisorseExt = parseInt(document.getElementById('numRisorseExt').value);

            // Genera i form dinamicamente
            var risExt = document.getElementById('risorseExt');
            risExt.innerHTML = ''; // Pulisci il contenuto precedente

            for (var i = 0; i < numRisorseExt; i++) {
                var formHtml = `
                        <p>Risorsa esterna ${i + 1}</p>
                        <label for="risorseExt${i}nome">Nome Docente${i + 1}:</label>
                        <input type="text" id="risorseExt${i}nome" name="risorseExt${i}nome">

                        <label for="risorseExt${i}ore">Numero Ore di docenza:</label>
                        <input type="number" id="risorseExt${i}ore" name="risorseExt${i}ore" min="1" value="1"> 
                        
                        <label for="risorseExt${i}ore">Costo Previsto (Euro):</label>
                        <input type="number" id="risorseExt${i}costo" name="risorseExt${i}costo" min="1" value="1">
                        
                        <label for="risorseExt${i}ore">Eventuali Costi Aggiuntivi:</label>
                        <textarea id="risorseExt${i}eventualicosti" name="risorseExt${i}eventualicosti" ></textarea>
                        `;
                risExt.innerHTML += formHtml;
            }
            event.preventDefault();
}
 
    let checkedCount = 0;

    // Funzione per gestire il cambiamento di stato di una checkbox
    function handleCheckboxChange2(checkbox) {
        if (checkbox.checked) {
            checkedCount++;
        } else {
            checkedCount--;
        }

        // Disabilita ulteriori selezioni se si supera il limite massimo consentito
        if (checkedCount > 2) {
            checkbox.checked = false;
            checkedCount--;
        }
    }
     
function selezionaCheckbox(){
	var container = document.getElementById('classi-selezionate');
    var checkboxes = container.querySelectorAll('input[type="checkbox"]');
    
    // Seleziona tutte le checkbox
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = true;
    });
    
    document.getElementById('selectAll').style.display="none";
    document.getElementById('deselectAll').style.display="inline-block";
}

function deselezionaCheckbox(){
	var container = document.getElementById('classi-selezionate');
    var checkboxes = container.querySelectorAll('input[type="checkbox"]');
    
    // Deseleziona tutte le checkbox
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = false;
    });
    
    document.getElementById('deselectAll').style.display="none";
    document.getElementById('selectAll').style.display="inline-block";
}

function mostraClassi(){

    const checkboxes = document.querySelectorAll('input[name="annata"]:checked, input[name="indirizzo"]:checked');
    const anno = document.querySelectorAll('input[name="annata"]:checked');
    const addr = document.querySelectorAll('input[name="indirizzo"]:checked');
    var valoriConcatenati = [];

    const opzioniPerIndirizzo = {
        'Informatico': {
            '1': ['1C', '1D', '1E', '1M'],
            '2': ['2C', '2D', '2E', '2M'],
            '3': ['3C', '3D', '3E', '3M'],
            '4': ['4C', '4D', '4E'],
            '5': ['5C', '5D', '5E', '5M']
        },
        'Relazioni Internazionali': {
            '1': ['1F', '1G', '1H'],
            '2': ['2F', '2G', '2H'],
            '3': ['3F', '3G', '3H'],
            '4': ['4F', '4G', '4H'],
            '5': ['5F', '5G', '5H']
        },
        'Scienze Applicate': {
            '1': ['1I', '1L', '1N'],
            '2': ['2I', '2L', '2N'],
            '3': ['3I', '3L', '3N', '3P'],
            '4': ['4I', '4L', '4N'],
            '5': ['5I', '5L']
        },
        'Grafico': {
            '1': ['1A', '1B', '1Q'],
            '2': ['2A', '2B', '2Q'],
            '3': ['3A', '3B'],
            '4': ['4A', '4B'],
            '5': ['5A', '5B']
        },
    };
        
    checkboxes.forEach((checkbox) => {
        if((addr.length > 0)&&(anno.length > 0)){
            for (var ind in opzioniPerIndirizzo) {
                if(checkbox.value == ind){
                    const livelli = opzioniPerIndirizzo[ind];
                    
                    for (var livello in livelli) {
                        anno.forEach((a)=>{
                            if(a.value == livello){
                                var valori = livelli[livello];
        
                                // Concatenare i valori in una stringa separata da virgole
                                var valoriConcatenatiString = valori.join(', ');
        
                                // Aggiungere la stringa concatenata all'array valoriConcatenati
                                valoriConcatenati.push(valoriConcatenatiString);  
                            }
                        });
                    }
                }
            }
        }
        else if(anno.length > 0){
            for (var ind in opzioniPerIndirizzo) {
                const livelli = opzioniPerIndirizzo[ind];
                
                for (var livello in livelli) {
                    anno.forEach((a)=>{
                        if(a.value == livello){
                            var valori = livelli[livello];
        
                            // Concatenare i valori in una stringa separata da virgole
                            var valoriConcatenatiString = valori.join(', ');
        
                            // Aggiungere la stringa concatenata all'array valoriConcatenati
                            valoriConcatenati.push(valoriConcatenatiString);  
                        }
                    });
                }
            }
        }
        else if(addr.length >0)
        { 
            for (var ind in opzioniPerIndirizzo) {
                if(checkbox.value == ind){
                    const livelli = opzioniPerIndirizzo[ind];
                    
                    for (var livello in livelli) {
                        var valori = livelli[livello];

                        // Concatenare i valori in una stringa separata da virgole
                        var valoriConcatenatiString = valori.join(', ');

                        // Aggiungere la stringa concatenata all'array valoriConcatenati
                        valoriConcatenati.push(valoriConcatenatiString);  
                    }
                }
            }

        }
    });

    var addClass = document.getElementById('classi-selezionate');
    addClass.innerHTML = '';
    var tableHTML = '<table id="TabClassi"><tr>';
    var rowCount = 0;
    var columnOpen = false;

    valoriConcatenati.forEach(function(valoreConcatenato, index) {
        const elementiSingoli = valoreConcatenato.split(', ');

        elementiSingoli.forEach(function(elemento) {
            if (rowCount % 4 === 0) {
                if (columnOpen) {
                    tableHTML += '</tr>';
                }
                tableHTML += '</tr><tr>';
                columnOpen = true;
            }

            var labelHtml = `<label for="${elemento}">${elemento}</label>`;
            var inputHtml = `<input type="checkbox" class="classe" name="${elemento}" value="${elemento}">`;
            tableHTML += `<div class="divisore"><td>${labelHtml}</td><td>${inputHtml}</td></div>`;
            rowCount++;
        });
    });

    if (columnOpen) {
        tableHTML += '</tr>';
    }

    tableHTML += '</table>';

    addClass.innerHTML = tableHTML;
    event.preventDefault();
}
      
 
 
 
if(window.location.href.includes('dati_iniziali.php')){
     
     let page=1;
     const check = document.getElementById("nuovo");
     const check2 = document.getElementById("nonNuovo");
     const next = document.getElementById('avanti');
     const submit = document.getElementById('submit');
     const back = document.getElementById('indietro');
     const page1 = document.getElementById('colonna');
     const page2 = document.getElementById('colonna2');
     const page3 = document.getElementById('colonna3');
     const page4 = document.getElementById('colonna4');
     const page5 = document.getElementById('colonna5');
     const page6 = document.getElementById('colonna6');
     const page7 = document.getElementById('colonna7');
     
     next.addEventListener('click', function() {
     page++;
         switch(page){
             case 2:{
                 page1.style.display="none";
                 page2.style.display="flex";
                 back.style.display="flex";
             }break;
             case 3:{
                 page2.style.display="none";
                 page3.style.display="flex";
             }break;
             case 4:{
                 page3.style.display="none";
                 page4.style.display="flex";
             }break;
             case 5:{
                 page4.style.display="none";
                 page5.style.display="flex";
             }break;
             case 6:{
                 page5.style.display="none";
                 page6.style.display="flex";
             }break;
             case 7:{
                 page6.style.display="none";
                 page7.style.display="flex";
                 next.style.display="none";
                 submit.style.display="inline-block";
             }break;
         }
     }); 
     
     back.addEventListener('click', function() {
     page--;
     switch(page){
             case 1:{
                 page2.style.display="none";
                 page1.style.display="flex";
                 back.style.display="none";
             }break;
             case 2:{
                 page2.style.display="flex";
                 page3.style.display="none";
             }break;
             case 3:{
                 page3.style.display="flex";
                 page4.style.display="none";
             }break;
             case 4:{
                 page4.style.display="flex";
                 page5.style.display="none";
             }break;
             case 5:{
                 page5.style.display="flex";
                 page6.style.display="none";
             }break;
             case 6:{
                 page6.style.display="flex";
                 page7.style.display="none";
                 submit.style.display="none";
                 next.style.display="inline-block";
             }break;
         }
     
     }); 
 
     document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('mainForm');
        const requiredFields = ['title', 'dip', 'strutt', 'percorsi', 'orient','oreMat','orePom'];
        const requiredFields2 = ['contesto', 'obb', 'attiv', 'metodi', 'temp', 'verifica', 'document'];
        let errorMessageDisplayed = false; // Flag per tracciare se il messaggio di errore è stato visualizzato
    
        form.addEventListener("submit", function(event) {
            const selectedCheckboxes = document.querySelectorAll('input[class="classe"]:checked');
            let isValid = true;
    
            requiredFields.forEach(fieldName => {
                    const input = document.getElementById(fieldName);
                    if (!input.value.trim()) {
                        input.style.border = "2px solid red";
                        isValid = false;
                    } else {
                        input.style.border = "";
                    }
                }); 
                
            if (check.checked) {
                requiredFields2.forEach(fieldName => {
                    const input = document.getElementById(fieldName);
                    if (!input.value.trim()) {
                        input.style.border = "2px solid red";
                        isValid = false;
                    } else {
                        input.style.border = "";
                    }
                });
            }
            const selectedCheckboxArray = [];

        // Itera su ogni checkbox selezionata e aggiungi alla selectedCheckboxArray
        selectedCheckboxes.forEach(checkbox => {
            selectedCheckboxArray.push(checkbox);
        });
        
        if (selectedCheckboxArray.length == 0)isValid = false;
                
    
            if (!isValid) {
                event.preventDefault(); // Blocca l'invio del modulo se ci sono campi vuoti
    
                if (!errorMessageDisplayed) {
                    const errorMessage = document.createElement("div");
                    errorMessage.textContent = "Inserisci tutti gli elementi obbligatori.";
                    errorMessage.className = "error-message";
                    form.prepend(errorMessage); // Aggiungo il messaggio di errore sopra il modulo
                    errorMessageDisplayed = true; // Imposto il flag su true
                }
            } else {
                // Rimuovo il messaggio di errore se tutti i campi obbligatori sono compilati
                const existingErrorMessage = form.querySelector(".error-message");
                if (existingErrorMessage) {
                    form.removeChild(existingErrorMessage);
                    errorMessageDisplayed = false; // Resetta il flag
                }
            }
            
            //Resetto lo stile del bordo quando l'utente modifica un campo obbligatorio
            requiredFields.forEach(fieldName => {
                const input = document.getElementById(fieldName);
                input.addEventListener("input", function() {
                    if (input.value.trim()) {
                        input.style.border = ""; // Rimuovo il bordo rosso se il campo è compilato
                    }
                });
            });
        });
 });
}

function logout() {
    // URL di logout di Google
    var googleLogoutUrl = 'https://accounts.google.com/Logout';

    // Funzione per reindirizzare dopo il logout da Google
    var redirectAfterLogout = function() {
        window.location.href = 'logout.php';
    };

    // Creare un iframe per eseguire il logout di Google
    var iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = googleLogoutUrl;
    iframe.onload = redirectAfterLogout;
    document.body.appendChild(iframe);
}