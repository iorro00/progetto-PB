<?php
// Tipo di carattere
$type = 'Core';

// Nome del carattere
$name = 'Courier';

// Coordinata verticale di spostamento
$up = -100;

// Altezza del testo
$ut = 50;

// Inizializza array di larghezze caratteri con valore predefinito 600
$cw = [];
for ($i = 0; $i <= 255; $i++) {
    $cw[chr($i)] = 600;
}

// Codifica utilizzata
$enc = 'cp1252';

// Mappa dei valori Unicode per caratteri speciali
$uv = [
    0   => [0, 128],
    128 => 8364,   // Euro
    130 => 8218,   // Singola virgoletta bassa
    131 => 402,    // Segno florin
    132 => 8222,   // Doppia virgoletta bassa
    133 => 8230,   // Punti di sospensione
    134 => [8224, 2], // Daga
    136 => 710,    // Circonflesso
    137 => 8240,   // Per mille
    138 => 352,    // S maiuscola con caron
    139 => 8249,   // Virgoletta angolare sinistra
    140 => 338,    // OE maiuscolo
    142 => 381,    // Z maiuscola con caron
    145 => [8216, 2], // Apostrofo singolo
    147 => [8220, 2], // Apostrofo doppio
    149 => 8226,   // Punto elenco
    150 => [8211, 2], // Lineetta
    152 => 732,    // Tilde
    153 => 8482,   // Marchio di registrazione
    154 => 353,    // S minuscola con caron
    155 => 8250,   // Virgoletta angolare destra
    156 => 339,    // OE minuscolo
    158 => 382,    // Z minuscola con caron
    159 => 376,    // Y con dieresi
    160 => [160, 96], // Spazio unificato
];
?>
