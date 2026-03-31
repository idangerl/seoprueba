<?php
  $data = json_decode(file_get_contents("assets/json/base.json"), true);
  $home = json_decode(file_get_contents("assets/json/home.json"), true);
  require 'componentes/funciones.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>TÉRMINOS Y CONDICIONES - <?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>
    </title>
    <meta name="description" content="TÉRMINOS Y CONDICIONES - <?php echo $data["informacion_general"]["descripcion"]?>">
    <meta name="keywords"
        content="TÉRMINOS Y CONDICIONES, <?php echo $data["informacion_general"]["especialidad"]?> en <?php echo $data["informacion_general"]["ubicacion"]?>. <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:title" content="TÉRMINOS Y CONDICIONES - <?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:description" content="TÉRMINOS Y CONDICIONES - <?php echo $data["informacion_general"]["descripcion"]?>">
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
                    <h1>TÉRMINOS Y CONDICIONES</h1>
                </div>
            </div>
        </section>
        <section class="service-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 col-lg-7">
                    <div class="service-details__left">
    <h2>TÉRMINOS Y CONDICIONES - <?php echo $data["informacion_general"]["nombre"] ?> en <?php echo $data["informacion_general"]["ubicacion"] ?></h2>

    <h2>1. Uso del Sitio Web</h2>
    <p>1.1. Este sitio proporciona información general sobre servicios médicos y no sustituye la consulta médica profesional.</p>
    <p>1.2. El contenido es exclusivamente informativo y está sujeto a actualizaciones sin previo aviso.</p>
    <p>1.3. El uso de este sitio implica tu aceptación de estos términos. Si no estás de acuerdo, te recomendamos no utilizarlo.</p>

    <h2>2. Servicios Médicos</h2>
    <p>2.1. Los servicios médicos ofrecidos están sujetos a disponibilidad y previa valoración profesional.</p>
    <p>2.2. Los diagnósticos, tratamientos y procedimientos dependen de las necesidades individuales del paciente y son determinados por el médico especialista tras una consulta presencial.</p>
    <p>2.3. Los resultados de los tratamientos pueden variar entre pacientes, y no se garantizan resultados específicos.</p>

    <h2>3. Limitación de Responsabilidad</h2>
    <p>3.1. Este sitio no garantiza la exactitud, integridad o idoneidad de la información proporcionada.</p>
    <p>3.2. No asumimos responsabilidad por decisiones tomadas con base en la información contenida en el sitio sin consultar previamente con un médico.</p>

    <h2>4. Propiedad Intelectual</h2>
    <p>4.1. Queda estrictamente prohibida la reproducción, distribución o uso no autorizado del contenido sin consentimiento previo por escrito.</p>

    <h2>5. Política de Privacidad</h2>
    <p>5.1. La información personal proporcionada por los usuarios se manejará conforme a nuestra Política de Privacidad.</p>
    <p>5.2. Nos comprometemos a proteger tu información y no compartirla con terceros sin tu autorización.</p>

    <h2>6. Cambios en los Términos y Condiciones</h2>
    <p>6.1. Nos reservamos el derecho de modificar estos términos en cualquier momento. Las actualizaciones se publicarán en esta página y entrarán en vigor de inmediato.</p>
    <p>6.2. Recomendamos revisar esta sección periódicamente para estar al tanto de los cambios.</p>

    <h2>7. Contacto</h2>
    <p>Si tienes alguna pregunta o comentario sobre estos Términos y Condiciones, puedes contactarnos a través de los siguientes medios:</p>
    <ul>
        <?php if (!empty($data["informacion_contacto"]["telefono"][0])): ?>
        <li><strong>Teléfono:</strong> <?php echo htmlspecialchars($data["informacion_contacto"]["telefono"][0]); ?></li>
        <?php endif; ?>

        <?php if (!empty($data["informacion_contacto"]["correo"][0])): ?>
        <li><strong>Correo Electrónico:</strong> <a href="mailto:<?php echo htmlspecialchars($data["informacion_contacto"]["correo"][0]); ?>"><?php echo htmlspecialchars($data["informacion_contacto"]["correo"][0]); ?></a></li>
        <?php endif; ?>
    </ul>

    <p>Gracias por confiar en nosotros. Estamos comprometidos con tu salud y bienestar.</p>
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