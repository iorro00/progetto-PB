if (window.location.href.includes('ins_visua_project.php')) {
    const createButt = document.getElementById('ButtIns');
    const createButt2 = document.getElementById('ButtVisua');
    const createButt3 = document.getElementById('ButtRendi');
    const createButt4 = document.getElementById('ButtAmm');

    if (createButt) {
        createButt.addEventListener('click', function() {
            setTimeout(function() {
                window.location.href = 'dati_iniziali.php';
            }, 400);
        });
    }

    if (createButt2) {
        createButt2.addEventListener('click', function() {
            setTimeout(function() {
                window.location.href = 'visualizzazione.php';
            }, 400);
        });
    }

    if (createButt3) {
        createButt3.addEventListener('click', function() {
            setTimeout(function() {
                window.location.href = 'rendicontazione.php';
            }, 400);
        });
    }

    if (createButt4) {
        createButt4.addEventListener('click', function() {
            setTimeout(function() {
                window.location.href = 'pagina_amm.php';
            }, 400);
        });
    }


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

    // Seleziona il contenitore delle risorse esterne
    var risExt = document.getElementById('risorseExt');
    risExt.innerHTML = ''; // Pulisci il contenuto precedente

    for (var i = 0; i < numRisorseExt; i++) {
        var formHtml = `
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">Risorsa esterna ${i + 1}</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        
                        <!-- Nome Docente -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="risorseExt${i}nome">Nome Docente ${i + 1}:</label>
                            <input type="text" class="form-control" id="risorseExt${i}nome" name="risorseExt${i}nome">
                        </div>

                        <!-- Numero Ore di Docenza -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="risorseExt${i}ore">Numero Ore di Docenza:</label>
                            <input type="number" class="form-control" id="risorseExt${i}ore" name="risorseExt${i}ore" min="1" value="1">
                        </div>

                        <!-- Costo Previsto -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="risorseExt${i}costo">Costo Previsto (Euro):</label>
                            <input type="number" class="form-control" id="risorseExt${i}costo" name="risorseExt${i}costo" min="1" value="1">
                        </div>

                        <!-- Eventuali Costi Aggiuntivi -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="risorseExt${i}eventualicosti">Eventuali Costi Aggiuntivi:</label>
                            <textarea class="form-control" id="risorseExt${i}eventualicosti" name="risorseExt${i}eventualicosti"></textarea>
                        </div>
                    </div>
                </div>
            </div>
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

function mostraClassi() {
    const anno = document.querySelectorAll('input[name="annata"]:checked');
    const addr = document.querySelectorAll('input[name="indirizzo"]:checked');
    let classiDaMostrare = new Set();

    // Se non ci sono filtri selezionati, pulisci e esci
    if (anno.length === 0 && addr.length === 0) {
        document.getElementById('classi-selezionate').innerHTML = '';
        return;
    }

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
        }
    };

    // Logica di filtro
    if (addr.length > 0) {
        // Filtro per indirizzo
        addr.forEach(indirizzo => {
            const indirizzoClassi = opzioniPerIndirizzo[indirizzo.value];
            if (anno.length > 0) {
                // Filtro combinato indirizzo + anno
                anno.forEach(a => {
                    const classiAnno = indirizzoClassi[a.value] || [];
                    classiAnno.forEach(classe => classiDaMostrare.add(classe));
                });
            } else {
                // Solo indirizzo
                Object.values(indirizzoClassi).forEach(classi => {
                    classi.forEach(classe => classiDaMostrare.add(classe));
                });
            }
        });
    } else if (anno.length > 0) {
        // Solo filtro per anno
        anno.forEach(a => {
            Object.values(opzioniPerIndirizzo).forEach(indirizzo => {
                const classiAnno = indirizzo[a.value] || [];
                classiAnno.forEach(classe => classiDaMostrare.add(classe));
            });
        });
    }

    // Genera la tabella HTML
    const addClass = document.getElementById('classi-selezionate');
    let tableHTML = '<table id="TabClassi"><tr>';
    let rowCount = 0;
    
    Array.from(classiDaMostrare).sort().forEach(classe => {
        if (rowCount % 4 === 0 && rowCount > 0) {
            tableHTML += '</tr><tr>';
        }
        tableHTML += `
            <div class="divisore">
                <td><label for="${classe}">${classe}</label></td>
                <td><input type="checkbox" class="classe" name="${classe}" value="${classe}"></td>
            </div>`;
        rowCount++;
    });

    // Chiudi l'ultima riga se necessario
    if (rowCount > 0) {
        tableHTML += '</tr>';
    }
    tableHTML += '</table>';

    addClass.innerHTML = tableHTML;
}
      
 
 
// ——— script.js ———

// TUTTO il tuo codice AJAX, generaRisorse(), logout(), mostraClassi(), ecc.
// (lascia inalterate le altre funzioni che già funzionano)

// Ora la parte di “dati_iniziali.php”:
// RIMUOVI qualunque altro blocco if(window.location.href…) precedente!
// Questo è l’unico che deve gestire pagina e validazione.
if (window.location.href.includes('dati_iniziali.php')) {
    let page = 1;
  
    // Riferimenti ai bottoni
    let nextBtn   = document.getElementById('avanti');
    const backBtn   = document.getElementById('indietro');
    const submitBtn = document.getElementById('submit');
  
    // Colonne
    const pageEls = {
      1: document.getElementById('colonna'),
      2: document.getElementById('colonna2'),
      3: document.getElementById('colonna3'),
      4: document.getElementById('colonna4'),
      5: document.getElementById('colonna5'),
      6: document.getElementById('colonna6'),
      7: document.getElementById('colonna7')
    };
    const maxPage = Object.keys(pageEls).length; // 7
  
    // Rimuovo eventuali listener precedenti e cloni il button
    nextBtn.replaceWith(nextBtn.cloneNode(true));
    nextBtn = document.getElementById('avanti');
  
    // Funzione che mostra/nasconde le colonne e i bottoni
    function renderPage(p) {
      console.log("renderPage → page =", p);
      for (let i = 1; i <= maxPage; i++) {
        pageEls[i].style.display = i === p
          ? (i === 1 ? 'flex' : 'block')
          : 'none';
      }
      backBtn.style.display   = p > 1        ? 'inline-block' : 'none';
      nextBtn.style.display   = p < maxPage  ? 'inline-block' : 'none';
      submitBtn.style.display = p === maxPage ? 'inline-block' : 'none';
  
      // in più, forzo la classe d-none su nextBtn se ultima pagina
      nextBtn.classList.toggle('d-none', p === maxPage);
  
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  
    // Funzione di validazione per ogni pagina
    function validatePage(p) {
      let valid = true;
      const col = pageEls[p];
  
      // ripulisco precedenti .is-invalid
      col.querySelectorAll('.is-invalid')
         .forEach(el => el.classList.remove('is-invalid'));
  
      switch (p) {
        case 1: {
          const title = col.querySelector('#title');
          const dip   = col.querySelector('#dip');
          if (!title.value.trim()) { title.classList.add('is-invalid'); valid = false; }
          if (!dip.value)           { dip.classList.add('is-invalid');   valid = false; }
        } break;
  
        case 2: {
          const indirizzi = col.querySelectorAll('#indirizzo .form-check-input');
          if (![...indirizzi].some(i => i.checked)) {
            indirizzi.forEach(i => i.classList.add('is-invalid'));
            valid = false;
          }
  
          const classi = col.querySelectorAll('#classi-selezionate input[type="checkbox"]');
          if (classi.length === 0 || ![...classi].some(c => c.checked)) {
            classi.forEach(c => c.classList.add('is-invalid'));
            valid = false;
          }
        } break;
  
        case 3: {
          const competenze = col.querySelectorAll('#tbComp .form-check-input');
          if (![...competenze].some(c => c.checked)) {
            competenze.forEach(c => c.classList.add('is-invalid'));
            valid = false;
          }
        } break;
  
        case 4: {
          ['strutt','percorsi','orient'].forEach(id => {
            const sel = col.querySelector('#' + id);
            if (!sel.value) { sel.classList.add('is-invalid'); valid = false; }
          });
        } break;
  
        case 5: {
          ['contesto','obb','attiv','metodi','luog','verifica','document']
            .forEach(id => {
              const ta = col.querySelector('#' + id);
              if (!ta.value.trim()) {
                ta.classList.add('is-invalid');
                valid = false;
              }
            });
  
          const months = col.querySelectorAll('#tbMesi input[type="checkbox"]');
          if (![...months].some(m => m.checked)) {
            months.forEach(m => m.classList.add('is-invalid'));
            valid = false;
          }
        } break;
  
        // case 6: nessuna validazione
      }
  
      return valid;
    }
  
    // Listener “Avanti”
    nextBtn.addEventListener('click', function(e) {
      if (!validatePage(page)) {
        e.preventDefault();
        pageEls[page].scrollIntoView({ behavior: 'smooth', block: 'start' });
        return;
      }
      page++;
      renderPage(page);
    });
  
    // Listener “Indietro”
    backBtn.addEventListener('click', function() {
      page--;
      renderPage(page);
    });
  
    // Mostra iniziale
    renderPage(page);
  }
    

function logout() {
    // URL di logout di Google
    var googleLogoutUrl = 'https://accounts.google.com/Logout';

    // Funzione per reindirizzare dopo il logout da Google
    var redirectAfterLogout = function() {
        window.location.href = 'login.php';
    };

    // Creare un iframe per eseguire il logout di Google
    var iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = googleLogoutUrl;
    iframe.onload = redirectAfterLogout;
    document.body.appendChild(iframe);
}