<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>404 - Rayo Verde</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            padding: 0;
            margin: 0;
            background-color: #fcfcf9;
        }

        #notfound {
            position: relative;
            height: 100vh;
            background-color: #1c382b; 
        }

        #notfound .notfound {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .notfound {
            max-width: 520px;
            width: 100%;
            text-align: center;
            line-height: 1.6;
            padding: 0 20px;
        }

        .notfound .notfound-404 {
            height: 160px;
            line-height: 160px;
            margin-bottom: 20px;
        }

        .notfound .notfound-404 h1 {
            font-family: 'Playfair Display', Georgia, serif;
            color: #ffffff;
            font-size: 160px;
            margin: 0;
            font-weight: 700;
            letter-spacing: -2px;
        }

        .notfound .notfound-404 h1>span {
            color: #83c635; 
            text-shadow: 0 0 10px rgba(131, 198, 53, 0.3);
        }

        .notfound p {
            font-family: 'Montserrat', sans-serif;
            color: #d1e0d8; 
            font-size: 16px;
            font-weight: 400;
            margin-top: 0;
            margin-bottom: 35px;
        }

        .notfound a {
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: #83c635;
            color: #1c382b;
            display: inline-block;
            padding: 14px 32px;
            font-weight: 600;
            border-radius: 4px;
            -webkit-transition: .3s all;
            transition: .3s all;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .notfound a:hover {
            background: #ffffff;
            color: #1c382b;
            transform: translateY(-2px);
        }

        @media only screen and (max-width:480px) {
            .notfound .notfound-404 {
                height: 100px;
                line-height: 100px;
            }

            .notfound .notfound-404 h1 {
                font-size: 100px;
            }
            
            .notfound p {
                font-size: 14px;
            }
        }
    </style>

    <meta name="robots" content="noindex, follow">
</head>

<body>
    <div id="notfound">
        <div class="notfound">
            <div class="notfound-404">
                <h1>4<span>0</span>4</h1>
            </div>
            <p>La página que busca no existe, ha cambiado de nombre o no está disponible temporalmente.</p>
            <a href="./home">Volver al inicio</a>
        </div>
    </div>
</body>

</html>