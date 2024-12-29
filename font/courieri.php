<?php
// Tipo di font e nome
$type = 'Core';
$name = 'Courier-Oblique';

// Parametri di posizionamento del testo
$up = -100; // Spostamento verticale
$ut = 50;   // Altezza del testo

// Definizione della larghezza predefinita per ogni carattere (600 unità)
$cw = [];
for ($i = 0; $i <= 255; $i++) {
    $cw[chr($i)] = 600;
}

// Codifica dei caratteri
$enc = 'cp1252';

// Mappa Unicode dei caratteri speciali
$uv = [
    0   => [0, 128],
    128 => 8364,    // Simbolo Euro (€)
    130 => 8218,    // Singola virgoletta bassa (‘)
    131 => 402,     // Segno florin (ƒ)
    132 => 8222,    // Doppia virgoletta bassa (“)
    133 => 8230,    // Punti di sospensione (…)
    134 => [8224, 2], // Daga († e ‡)
    136 => 710,     // Circonflesso (ˆ)
    137 => 8240,    // Per mille (‰)
    138 => 352,     // S maiuscola con caron (Š)
    139 => 8249,    // Virgoletta angolare sinistra (‹)
    140 => 338,     // OE maiuscolo (Œ)
    142 => 381,     // Z maiuscola con caron (Ž)
    145 => [8216, 2], // Apostrofi singoli inclinati (‘ e ’)
    147 => [8220, 2], // Apostrofi doppi inclinati (“ e ”)
    149 => 8226,    // Punto elenco (•)
    150 => [8211, 2], // Lineetta (– e —)
    152 => 732,     // Tilde (~)
    153 => 8482,    // Marchio registrato (™)
    154 => 353,     // S minuscola con caron (š)
    155 => 8250,    // Virgoletta angolare destra (›)
    156 => 339,     // OE minuscolo (œ)
    158 => 382,     // Z minuscola con caron (ž)
    159 => 376,     // Y con dieresi (Ÿ)
    160 => [160, 96], // Spazi unificati
];
?>
