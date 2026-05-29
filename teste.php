```javascript
let imgAss1 = new Image();

imgAss1.onload = function(){

    console.log('assinatura carregada');

    desenhar();

};

imgAss1.onerror = function(){

    console.log('erro ao carregar assinatura');

};

imgAss1.src = 'imagens/assinatura1.png';
```
