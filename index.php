<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Upload Template Certificado</title>

    <style>
        body{
            font-family:Arial;
            background:#f2f2f2;
            padding:40px;
        }

        .box{
            background:#fff;
            padding:30px;
            border-radius:10px;
            max-width:500px;
            margin:auto;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        input[type=file]{
            width:100%;
            padding:10px;
        }

        button{
            margin-top:20px;
            padding:12px 20px;
            background:#007bff;
            border:none;
            color:#fff;
            border-radius:5px;
            cursor:pointer;
        }

        button:hover{
            background:#0056b3;
        }
    </style>
</head>
<body>

<div class="box">

    <h2>Upload Template do Certificado</h2>

    <form action="processar.php" method="POST" enctype="multipart/form-data">

        <label>Fundo</label>

        <input type="file" name="imagem" accept="image/*" required>

        <button type="submit">
            Enviar
        </button>

    </form>

</div>

</body>
</html>