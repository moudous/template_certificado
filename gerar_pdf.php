<?php

require 'vendor/autoload.php';

use TCPDF;

$dados =
    json_decode(
        file_get_contents('php://input'),
        true
    );

$pdf = new TCPDF(
    'L',
    'pt',
    'A4'
);

$pdf->SetMargins(0,0,0);

$pdf->AddPage();

$imagem = $dados['imagem'];

$pdf->Image(
    $imagem,
    0,
    0,
    $pdf->getPageWidth(),
    $pdf->getPageHeight()
);