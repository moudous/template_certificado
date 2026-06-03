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
    'px',
    [
       
       $dados['larguraImagem'],
       $dados['alturaImagem']
    ]
);

$pdf->setImageScale(1);

//$pdf->setImageScale(96/72);

$pdf->SetMargins(0,0,0);
$pdf->SetAutoPageBreak(false,0);

$pdf->AddPage();



$imagem =
    __DIR__ . '/' . $dados['imagem'];


$pdf->Image(
    $imagem,
    0,
    0,
    $dados['larguraImagem'],
    $dados['alturaImagem'],
    'PNG',
    '',
    '',
    false,
    72,
    '',
    false,
    false,
    0,
    false,
    false,
    false
);




// assinatura
$pdf->Image(
    __DIR__.'/imagens/assinatura1.png',
    $dados['posAss1X'],
    $dados['posAss1Y'],
    $dados['assinatura1Largura'],
    $dados['assinatura1Altura'],
    'PNG'
);



//Converter fonte TTF
function carregarFonte(
    TCPDF $pdf,
    string $fonte
){

    $arquivo =
        __DIR__ .
        '/fonts/' .
        $fonte .
        '.ttf';

    if(!file_exists($arquivo)){
        return 'helvetica';
    }

    return TCPDF_FONTS::addTTFfont(
        $arquivo,
        'TrueTypeUnicode',
        '',
        96
    );
}


//crregar fonte nome
$fonteNome =
    carregarFonte(
        $pdf,
        $dados['familiaNome']
    );

$pdf->SetFont(
    $fonteNome,
    '',
    $dados['fonteNome']
);

$pdf->Text(
    $dados['posNomeX'],
    $dados['posNomeY'],
    $dados['nome']
);

//fonte carga horária
$fonteCarga =
    carregarFonte(
        $pdf,
        $dados['familiaCarga']
    );

$pdf->SetFont(
    $fonteCarga,
    '',
    $dados['fonteCarga']
);

$pdf->Text(
    $dados['posCargaX'],
    $dados['posCargaY'],
    $dados['cargaHoraria']
);

//fonte texto1
$fonteTexto1 =
    carregarFonte(
        $pdf,
        $dados['familiaTexto1']
    );

$pdf->SetFont(
    $fonteTexto1,
    '',
    $dados['fonteTexto1']
);

$pdf->MultiCell(
    900,
    0,
    $dados['texto1'],
    0,
    'L',
    false,
    1,
    $dados['posTexto1X'],
    $dados['posTexto1Y']
);

$pdf->Output('certificado.pdf', 'I');
exit;