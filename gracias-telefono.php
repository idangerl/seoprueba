<?php
$data = json_decode(file_get_contents("assets/json/base.json"), true);
$home = json_decode(file_get_contents("assets/json/home.json"), true);
require 'componentes/funciones.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title><?php echo $data["informacion_general"]["especialidad"] ?> en
        <?php echo $data["informacion_general"]["ubicacion"] ?> - <?php echo $data["informacion_general"]["nombre"] ?>
    </title>
    <meta name="description" content="<?php echo $data["informacion_general"]["descripcion"] ?>">
    <meta name="keywords"
        content="<?php echo $data["informacion_general"]["especialidad"] ?> en <?php echo $data["informacion_general"]["ubicacion"] ?>. <?php echo $data["informacion_general"]["nombre"] ?>">
    <meta property="og:title" content="<?php echo $data["informacion_general"]["especialidad"] ?> en
        <?php echo $data["informacion_general"]["ubicacion"] ?> - <?php echo $data["informacion_general"]["nombre"] ?>">
    <meta property="og:description" content="<?php echo $data["informacion_general"]["descripcion"] ?>">
    <?php
    require 'componentes/head-metas.php';
    ?>
</head>

<body>

    <style>
        #gracias-telefono .container-fluid {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-content: center;
            align-items: center;
        }

        .card {
            background-color: #fffffa;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            padding: 20px;
            text-align: center;
            /* Asegúrate de que el texto y las imágenes estén centrados */
        }

        .card h1 {
            font-size: 4em;
            font-weight: 600;
            color: var(--color1);
            margin: 5px;
            -webkit-text-stroke: 0.5px rgb(199 231 243);
        }

        .card p {
            color: #122c4b;
            margin: 5px;
        }

        .social-icons a {
            color: var(--color1);
            font-size: 24px;
            margin: 0 10px;
        }

        #gracias-telefono .btn {
            background: var(--color1);
            border-radius: 100px;
            color: #fff;
            border-color: #77c0ff;
        }

        .social-icons a:hover {
            color: #0056b3;
        }

        .bg-primary {
            background-color: var(--color1) !important;
        }

        .logopsico-1 {
            max-width: 200px;
            display: block;
            margin: 0 auto;
        }

        .logopsico {
            max-width: 150px;
            display: block;
            /* Cambia a block para mejorar el centrado */
            margin: 0 auto;
            /* Agrega margen automático para centrar la imagen */
        }

        @media only screen and (min-width: 320px) and (max-width: 767px) {
            .card h1 {
                font-size: 3.2em;
                margin-top: 20px;
                margin-bottom: 20px;

            }
        }
    </style>

    <div class="page-wrapper">
        <section id="gracias-telefono">
            <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100 bg-primary">
                <div class="card p-5">
                    <!--<img src="assets/images/logos/diagnostico-y-tratamiento-urologico-en-cdmx.png" alt="Logotipo" class="mb-3 img-fluid">-->
                    <img src="assets/images/logos/diagnostico-y-tratamiento-urologico-en-cdmx.png" alt="Logotipo" class="mb-3 img-fluid logopsico">
                    <h1>¡Gracias!</h1>
                    <p><strong>Tu llamada ha sido recibida. ¡Hablamos en breve!</strong></p>
                    <!-- <p>Recuerda seguirnos en nuestras redes sociales:</p>
            <div class="social-icons mb-4">
                <a href="https://www.facebook.com/analitikultrasonidos" target="_blank" class="fullwidth-1">
                    <i class="fa-brands fa-facebook"></i>
                </a>
               
            </div>  -->
                    <br>
                    <a class="thm-btn" href="index.php" class="btn btn-primary">Regresar al sitio</a>
                </div>
            </div>
        </section>



    </div>
    <?php require 'componentes/js.php'; ?>
</body>

</html>