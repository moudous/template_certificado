<?php

if(!isset($_FILES['imagem'])){
    die('Nenhuma imagem enviada');
}

$dir = 'uploads';

if(!is_dir($dir)){
    mkdir($dir);
}

$nome = time().'_'.$_FILES['imagem']['name'];

$caminho = $dir.'/'.$nome;

move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editor Certificado</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>

        body{
            font-family:Arial;
            background:#f2f2f2;
            padding:20px;
        }

        .topo{
            margin-bottom:20px;
        }

        .container{
            display:flex;
            gap:20px;
        }

        .painel{
            width:300px;
            background:#fff;
            padding:20px;
            border-radius:10px;
        }

        canvas{
            border:2px solid #000;
            cursor:crosshair;
            background:#fff;
        }

        input{
            width:100%;
            padding:10px;
            margin-top:10px;
        }

        button{
            margin-top:15px;
            padding:12px;
            width:100%;
            border:none;
            background:#007bff;
            color:#fff;
            border-radius:5px;
            cursor:pointer;
        }

        button:hover{
            background:#0056b3;
        }

        .info{
            margin-top:10px;
            color:#555;
        }
        select{
            width:100%;
            padding:10px;
            margin-top:10px;
        }

        hr{
            margin-top:20px;
            margin-bottom:20px;
        }

        canvas{
            max-width:100%;
            height:auto;
        }   

        /* fontes*/
        @font-face{
            font-family:'Roboto';
            src:url('fontes/Roboto.ttf');
        }

        @font-face{
            font-family:'OpenSans';
            src:url('fontes/OpenSans.ttf');
        }

        @font-face{
            font-family:'GreatVibes';
            src:url('fontes/GreatVibes.ttf');
        }




    </style>
</head>
<body>

<h2>Escolha a posição do texto</h2>

<div class="container">

    <div>

        <canvas id="canvas"></canvas>

    </div>

   <div class="painel">

        <label>Nome</label>
        <input type="text" id="nome" value="Nome do Participante">
        <hr>

        <label>Carga Horária</label>
        <input type="text" id="cargaHoraria" value="40 horas">
        <hr>


        <label>Texto 1:</label>
        <textarea id="texto1" rows="5">Este certificado comprova a participação Este certificado comprova a participação Este certificado comprova a participação Este certificado comprova a participação Este certificado comprova a participaçãoEste certificado comprova a participação ...</textarea>

        <hr>

        <label>Largura Assinatura</label>
        <input type="number" id="assinatura1Largura" value="300">

              
        <hr>
        <label>Altura Assinatura</label>
        <input type="number" id="assinatura1Altura" value="300">

        <hr>

        <label>Tamanho da Fonte</label> 
        <input type="number" id="fonte" value="40">

        <hr>

        <label>Fonte</label>

        <select id="fonteFamilia">

            <option value="Arial">Arial</option>
            <option value="Roboto">Roboto</option>
            <option value="OpenSans">OpenSans</option>
            <option value="GreatVibes">GreatVibes</option>

        </select>
         


        <label>Campo selecionado</label>

        <select id="campoSelecionado">
            <option value="nome">Nome</option>
            <option value="carga">Carga Horária</option>
            <option value="texto1">Texto 1</option>
            <option value="assinatura1">Assinatura 1</option>
        </select>

        <div class="info">
            Escolha o campo acima e clique na imagem para posicionar.
        </div>

        <button onclick="gerarPDF()">
            Processar PDF
        </button>

    </div>

</div>


<script>

const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');


let familiaNome = 'Arial';
let familiaCarga = 'Arial';
let familiaTexto1 = 'Arial';

let fonteSelecionadaAtual = 'Arial';

const imagem = new Image();

imagem.src = '<?= $caminho ?>';

// ==========================
// POSIÇÕES
// ==========================

let posNomeX = 100;
let posNomeY = 100;

let posAss1X = 100;
let posAss1Y = 500;

//assinatura
let imgAss1 = new Image();

imgAss1.onload = function(){

    console.log('assinatura carregada');

    desenhar();

};

imgAss1.onerror = function(){

    console.log('erro ao carregar assinatura');

};

imgAss1.src = 'imagens/assinatura1.png';


let posCargaX = 100;
let posCargaY = 200;

let posTexto1X = 100;
let posTexto1Y = 300;

// ==========================
// FONTES MEMORIZADAS
// ==========================

let fonteNome = 40;
let fonteCarga = 28;
let fonteTexto1 = 24;

// ==========================
// CARREGAMENTO
// ==========================

imagem.onload = function(){

    const larguraOriginal = imagem.width;
    const alturaOriginal = imagem.height;

    const maxLargura = window.innerWidth - 380;

    let escala = 1;

    if(larguraOriginal > maxLargura){
        escala = maxLargura / larguraOriginal;
    }

    canvas.width = larguraOriginal * escala;
    canvas.height = alturaOriginal * escala;

    canvas.dataset.escala = escala;

    desenhar();

};

// ==========================
// DESENHAR TEXTO MULTILINHA
// ==========================

function desenharTextoMultilinha(
    texto,
    x,
    y,
    larguraMaxima,
    alturaLinha
){

    const palavras = texto.split(' ');

    let linha = '';

    let yAtual = y;

    for(let n = 0; n < palavras.length; n++){

        const testeLinha =
            linha + palavras[n] + ' ';

        const medidas =
            ctx.measureText(testeLinha);

        const larguraTeste =
            medidas.width;

        if(
            larguraTeste > larguraMaxima
            &&
            n > 0
        ){

            ctx.fillText(linha,x,yAtual);

            linha = palavras[n] + ' ';

            yAtual += alturaLinha;

        }else{

            linha = testeLinha;

        }

    }

    ctx.fillText(linha,x,yAtual);

}




// ==========================
// DESENHAR
// ==========================

function desenhar(){

    const escala =
        parseFloat(canvas.dataset.escala || 1);

    ctx.clearRect(0,0,canvas.width,canvas.height);

    // fundo
    ctx.drawImage(
        imagem,
        0,
        0,
        canvas.width,
        canvas.height
    );

    // =========================
    // NOME
    // =========================

    ctx.font =
        (fonteNome * escala) + 'px ' + familiaNome;

    ctx.fillStyle = 'black';

    ctx.fillText(
        document.getElementById('nome').value,
        posNomeX * escala,
        posNomeY * escala
    );

    // =========================
    // CARGA HORÁRIA
    // =========================

    ctx.font =
        (fonteCarga * escala) + 'px ' + familiaCarga;

    ctx.fillText(
        document.getElementById('cargaHoraria').value,
        posCargaX * escala,
        posCargaY * escala
    );

    // =========================
    // TEXTO1
    // =========================

    ctx.font =
        (fonteTexto1 * escala) + 'px ' + familiaTexto1;

    desenharTextoMultilinha(
        document.getElementById('texto1').value,
        posTexto1X * escala,
        posTexto1Y * escala,
        900 * escala,
        (fonteTexto1 + 10) * escala
    );

    // =========================
    // ASSINATURA
    // =========================

    if(imgAss1.complete){

        const larguraAss =
            parseInt(
                document.getElementById(
                    'assinatura1Largura'
                ).value
            );

        const alturaAss =
            parseInt(
                document.getElementById(
                    'assinatura1Altura'
                ).value
            );

        ctx.drawImage(
            imgAss1,
            posAss1X * escala,
            posAss1Y * escala,
            larguraAss * escala,
            alturaAss * escala
        );

    }

}

// ==========================
// CLIQUE CANVAS
// ==========================

canvas.addEventListener('click',function(e){

    const rect = canvas.getBoundingClientRect();

    const escala =
        parseFloat(canvas.dataset.escala || 1);

    const x = (e.clientX - rect.left) / escala;

    const y = (e.clientY - rect.top) / escala;

    const campo =
        document.getElementById('campoSelecionado').value;

    if(campo == 'nome'){

        posNomeX = x;
        posNomeY = y;

    }
    else if(campo == 'carga'){

        posCargaX = x;
        posCargaY = y;

    }
    else if(campo == 'texto1'){

        posTexto1X = x;
        posTexto1Y = y;

    }
    else if(campo == 'assinatura1'){

        posAss1X = x;
        posAss1Y = y;

    }

    desenhar();

});




// ==========================
// TROCA CAMPO
// ==========================

document.getElementById('campoSelecionado')
.addEventListener('change',function(){

    const campo = this.value;

    if(campo == 'nome'){

        document.getElementById('fonte').value =
            fonteNome;

    }
    else if(campo == 'carga'){

        document.getElementById('fonte').value =
            fonteCarga;

    }
    else if(campo == 'texto1'){

        document.getElementById('fonte').value =
            fonteTexto1;

    }

});

//e

// ==========================
// ALTERAÇÃO FONTE
// ==========================

document.getElementById('fonte')
.addEventListener('input',function(){

    const valor = parseInt(this.value);

    const campo =
        document.getElementById('campoSelecionado').value;

    if(campo == 'nome'){

        fonteNome = valor;

    }
    else if(campo == 'carga'){

        fonteCarga = valor;

    }
    else if(campo == 'texto1'){

        fonteTexto1 = valor;

    }

    desenhar();

});

// ==========================
// EVENTOS
// ==========================

document.getElementById('nome')
.addEventListener('input',desenhar);

document.getElementById('cargaHoraria')
.addEventListener('input',desenhar);

document.getElementById('texto1')
.addEventListener('input',desenhar);

document.getElementById('assinatura1Largura')
.addEventListener('input',desenhar);

document.getElementById('assinatura1Altura')
.addEventListener('input',desenhar);

//evento campo fonte
document.getElementById('fonteFamilia')
.addEventListener('change',function(){

    const valor = this.value;

    const campo =
        document.getElementById('campoSelecionado').value;

    if(campo == 'nome'){

        familiaNome = valor;

    }
    else if(campo == 'carga'){

        familiaCarga = valor;

    }
    else if(campo == 'texto1'){

        familiaTexto1 = valor;

    }

    desenhar();

});


// ==========================
// FONTE INICIAL
// ==========================

document.getElementById('fonte').value =
    fonteNome;

// ==========================
// PDF
// ==========================








async function gerarPDF(){

    const dados = {

        imagem : '<?= $caminho ?>',

        larguraImagem : imagem.width,
        alturaImagem  : imagem.height,

        nome : document.getElementById('nome').value,

        cargaHoraria :
            document.getElementById('cargaHoraria').value,

        texto1 :
            document.getElementById('texto1').value,

        posNomeX,
        posNomeY,

        posCargaX,
        posCargaY,

        posTexto1X,
        posTexto1Y,

        posAss1X,
        posAss1Y,

        fonteNome,
        fonteCarga,
        fonteTexto1,

        familiaNome,
        familiaCarga,
        familiaTexto1,

        assinatura1Largura :
            document.getElementById('assinatura1Largura').value,

        assinatura1Altura :
            document.getElementById('assinatura1Altura').value

    };

    const resposta =
        await fetch(
            'gerar_pdf.php',
            {
                method:'POST',
                headers:{
                    'Content-Type':'application/json'
                },
                body:JSON.stringify(dados)
            }
        );

    const blob = await resposta.blob();

    const url =
        window.URL.createObjectURL(blob);

    window.open(url);

}

</script>





</body>
</html>