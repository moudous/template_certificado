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

    </style>
</head>
<body>

<h2>Escolha a posição do texto</h2>

<div class="container">

    <div>

        <canvas id="canvas"></canvas>

    </div>

    <div class="painel">

        <label>Texto do certificado</label>

        <input type="text" id="texto" value="Nome do Participante">

        <label>Tamanho da fonte</label>

        <input type="number" id="fonte" value="40">

        <div class="info">
            Clique na imagem para posicionar o texto.
        </div>

        <button onclick="gerarPDF()">
            Processar PDF
        </button>

    </div>

</div>

<script>

const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');

const imagem = new Image();

imagem.src = '<?= $caminho ?>';

let posX = 100;
let posY = 100;

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

function desenhar(){

    ctx.clearRect(0,0,canvas.width,canvas.height);

    ctx.drawImage(imagem,0,0);

    let texto = document.getElementById('texto').value;

    let fonte = document.getElementById('fonte').value;

    ctx.font = fonte + 'px Arial';

    ctx.fillStyle = 'black';

    ctx.fillText(texto,posX,posY);

}

canvas.addEventListener('click',function(e){

    const rect = canvas.getBoundingClientRect();

    posX = e.clientX - rect.left;

    posY = e.clientY - rect.top;

    desenhar();

});

document.getElementById('texto').addEventListener('input',desenhar);

document.getElementById('fonte').addEventListener('input',desenhar);

async function gerarPDF(){

    const { jsPDF } = window.jspdf;

    const pdf = new jsPDF({
        orientation:'landscape',
        unit:'px',
        format:[canvas.width,canvas.height]
    });

    const imgData = canvas.toDataURL('image/png');

    pdf.addImage(imgData,'PNG',0,0,canvas.width,canvas.height);

    pdf.save('certificado.pdf');

}

</script>

</body>
</html>