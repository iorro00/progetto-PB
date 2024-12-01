<?php
$file = $_GET['file'];
$filepath = sys_get_temp_dir() . '/' . $file;

if (file_exists($filepath)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Progetti-IISPASCAL.pdf"');
    readfile($filepath);
    unlink($filepath); // elimina il file temporaneo
    exit;
} else {
    http_response_code(404);
    echo "File not found.";
}
?>
