```javascript
imagem.onload = function(){

    // tamanho REAL da imagem
    const larguraOriginal = imagem.width;
    const alturaOriginal = imagem.height;

    // largura máxima visual
    const maxLargura = window.innerWidth - 380;

    let escala = 1;

    // reduz proporcionalmente
    if(larguraOriginal > maxLargura){
        escala = maxLargura / larguraOriginal;
    }

    // canvas VISUAL
    canvas.width = larguraOriginal * escala;
    canvas.height = alturaOriginal * escala;

    // salva proporção
    canvas.dataset.escala = escala;

    desenhar();

};
```
