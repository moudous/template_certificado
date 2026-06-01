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