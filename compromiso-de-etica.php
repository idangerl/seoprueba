<?php
  $data = json_decode(file_get_contents("assets/json/base.json"), true);
  $home = json_decode(file_get_contents("assets/json/home.json"), true);
  require 'componentes/funciones.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>COMPROMISO DE ÉTICA MÉDICA - <?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>
    </title>
    <meta name="description"
        content="COMPROMISO DE ÉTICA MÉDICA - <?php echo $data["informacion_general"]["descripcion"]?>">
    <meta name="keywords"
        content="COMPROMISO DE ÉTICA MÉDICA, <?php echo $data["informacion_general"]["especialidad"]?> en <?php echo $data["informacion_general"]["ubicacion"]?>. <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:title" content="COMPROMISO DE ÉTICA MÉDICA - <?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:description"
        content="COMPROMISO DE ÉTICA MÉDICA - <?php echo $data["informacion_general"]["descripcion"]?>">
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
                    <h1>COMPROMISO DE ÉTICA MÉDICA</h1>
                </div>
            </div>
        </section>
        <section class="service-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 col-lg-7">
                    <div class="service-details__left">
    <h2>COMPROMISO DE ÉTICA MÉDICA - <?php echo $data["informacion_general"]["nombre"] ?> en <?php echo $data["informacion_general"]["ubicacion"] ?></h2>

    <h2>1. Atención Personalizada y Respeto</h2>
    <p>1.1. Cada paciente es único y merece un trato individualizado, respetuoso y empático.</p>
    <p>1.2. Escuchamos activamente tus preocupaciones y necesidades para diseñar planes de tratamiento adecuados a tu situación.</p>

    <h2>2. Transparencia en la Información</h2>
    <p>2.1. Nos comprometemos a ofrecer información clara, veraz y completa sobre diagnósticos, opciones de tratamiento, riesgos y beneficios.</p>
    <p>2.2. Todos los costos asociados a los servicios serán explicados previamente para evitar sorpresas o malentendidos.</p>

    <h2>3. Consentimiento Informado</h2>
    <p>3.1. Ningún procedimiento será realizado sin tu consentimiento previo, libre y plenamente informado.</p>
    <p>3.2. Nos aseguramos de que comprendas todos los aspectos del tratamiento antes de proceder.</p>

    <h2>4. Confidencialidad y Privacidad</h2>
    <p>4.1. Respetamos y protegemos la privacidad de tus datos personales y médicos conforme a las leyes aplicables.</p>
    <p>4.2. Tu información será utilizada exclusivamente para fines médicos y nunca será compartida sin tu autorización.</p>

    <h2>5. Compromiso con la Actualización Médica</h2>
    <p>5.1. Nos mantenemos al día con los avances científicos y tecnológicos para ofrecerte tratamientos basados en la evidencia más actualizada.</p>
    <p>5.2. Promovemos la formación continua y la excelencia profesional para garantizar la calidad de nuestros servicios.</p>

    <h2>6. No Discriminación</h2>
    <p>6.1. Brindamos atención médica sin distinción de género, edad, raza, religión, orientación sexual o condición socioeconómica.</p>
    <p>6.2. Todos los pacientes reciben un trato equitativo y justo en todo momento.</p>

    <h2>7. Responsabilidad Profesional</h2>
    <p>7.1. Reconocemos la importancia de actuar con honestidad, integridad y profesionalismo en todos los aspectos de la atención médica.</p>

    <h2>Contacto</h2>
    <p>Si tienes preguntas sobre nuestros principios o prácticas, te invitamos a contactarnos directamente a través de los siguientes medios:</p>
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