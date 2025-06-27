<?php
require_once __DIR__ . '/librerias/dompdf/autoload.inc.php';
$dompdf = new Dompdf\Dompdf();
$dompdf->loadHtml('<h1>Hola mundo</h1>');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("test.pdf", ["Attachment" => false]);
