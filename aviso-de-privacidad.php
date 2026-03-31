<?php
  $data = json_decode(file_get_contents("assets/json/base.json"), true);
  $home = json_decode(file_get_contents("assets/json/home.json"), true);
  require 'componentes/funciones.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Aviso de privacidad - <?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>
    </title>
    <meta name="description" content="Aviso de privacidad - <?php echo $data["informacion_general"]["descripcion"]?>">
    <meta name="keywords"
        content="Aviso de privacidad, <?php echo $data["informacion_general"]["especialidad"]?> en <?php echo $data["informacion_general"]["ubicacion"]?>. <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:title" content="Aviso de privacidad - <?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:description" content="Aviso de privacidad - <?php echo $data["informacion_general"]["descripcion"]?>">
    <?php require 'componentes/head-metas.php';?>
</head>

<body class="custom-cursor">
    <?php require 'componentes/botones.php';?>
    <div class="page-wrapper">
        <?php require 'componentes/header2.php';?>
        <section class="page-header">
            <div class="page-header__bg" style="background-image: url(assets/images/backgrounds/cuidado-integral-salud-urologica-en-cdmx.jpg);">
            </div>
            <div class="container">
                <div class="page-header__inner">
                    <h1>Aviso de privacidad</h1>
                </div>
            </div>
        </section>
        <section class="service-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 col-lg-7">
                        <div class="service-details__left">
                            <h2>AVISO DE PRIVACIDAD PARA
                                <?php echo $data["informacion_general"]["especialidad"]?> en
                                <?php echo $data["informacion_general"]["ubicacion"]?> -
                                <?php echo $data["informacion_general"]["nombre"]?>
                            </h2>
                            <h2>1. Introducción</h2>
                            <p>
                                Este Aviso de Privacidad se aplica a todos los servicios ofrecidos por
                                <?php echo $data["informacion_general"]["nombre"]?>. Nuestro compromiso es
                                proteger la privacidad y la confidencialidad de los datos personales de nuestros
                                pacientes y usuarios.
                            </p>
                            <h2>2. Recopilación de Datos Personales</h2>
                            <p>
                                Recopilamos datos personales para proporcionar y mejorar nuestros servicios
                                médicos y de bienestar. Esto incluye, pero no se limita a, información de
                                contacto, historial médico, preferencias de bienestar, y otros datos relevantes
                                para la prestación de nuestros servicios.
                            </p>
                            <h2>3. Uso de los Datos Personales</h2>
                            <p>
                                Utilizamos los datos recopilados para: <br>
                            </p>
                            <ul>
                                <li>
                                    <i class="fa-solid fa-circle"></i> Ofrecer y mejorar nuestros servicios
                                    médicos y de bienestar.
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle"></i> Administrar y gestionar citas,
                                    tratamientos y seguimientos.
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle"></i> Realizar facturación y procesos
                                    administrativos.
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle"></i> Comunicar información relevante sobre
                                    nuestros servicios.
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle"></i> Cumplir con las obligaciones legales y
                                    reglamentarias.
                                </li>
                            </ul>
                            <h2>4. Confidencialidad y Seguridad</h2>
                            <p>
                                Nos comprometemos a proteger la seguridad de sus datos personales a través de
                                medidas técnicas y organizativas adecuadas, asegurando su confidencialidad y
                                previniendo el acceso no autorizado.
                            </p>
                            <h2>5. Derechos del Titular de los Datos</h2>
                            <p>
                                Como titular de los datos, tiene derecho a acceder, rectificar, cancelar u
                                oponerse al tratamiento de sus datos personales. Para ejercer estos derechos,
                                puede contactarnos a través de <a
                                    href="mailto:<?php echo $data["informacion_contacto"]["correo"][0]?>"><?php echo $data["informacion_contacto"]["correo"][0]?></a>.
                            </p>
                            <h2>6. Cambios al Aviso de Privacidad</h2>
                            <p>
                                Nos reservamos el derecho de modificar este aviso de privacidad en cualquier
                                momento. Los cambios serán publicados en sitio web.
                            </p>
                            <h2>7. Contacto</h2>
                            <p>
                                Para cualquier duda o consulta sobre este aviso de privacidad, contáctenos en <a
                                    href="mailto:<?php echo $data["informacion_contacto"]["correo"][0]?>"><?php echo $data["informacion_contacto"]["correo"][0]?></a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php require 'componentes/footer.php';?>
    </div>
    <?php require 'componentes/js.php';?>
</body>

</html>