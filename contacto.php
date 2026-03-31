<?php
  $data = json_decode(file_get_contents("assets/json/base.json"), true);
  $home = json_decode(file_get_contents("assets/json/home.json"), true);
  require 'componentes/funciones.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Contacto -<?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>
    </title>
    <meta name="description" content="Contacto - <?php echo $data["informacion_general"]["descripcion"]?>">
    <meta name="keywords"
        content="<?php echo $data["informacion_general"]["especialidad"]?> en <?php echo $data["informacion_general"]["ubicacion"]?>. <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:title" content="Contacto - <?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:description" content="Contacto - <?php echo $data["informacion_general"]["descripcion"]?>">
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
                    <h1>Contacto - <?php echo $data["informacion_general"]["especialidad"]?> en
                        <?php echo $data["informacion_general"]["ubicacion"]?> -
                        <?php echo $data["informacion_general"]["nombre"]?></h1>
                </div>
            </div>
        </section>
        <section class="contact-page">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-7">
                        <?php
                            require 'componentes/formulario.php';
                        ?>
                    </div>
                    <div class="col-xl-5 col-lg-5">
    <div class="contact-page__right">
        <div class="section-title text-left sec-title-animation animation-style2">
            <h6 class="section-title__tagline">
                <span class="icon-broken-bone"></span>Contáctanos
            </h6>
            <h3 class="section-title__title title-animation">Tu salud es lo más importante</h3>
        </div>
        <p class="contact-page__text">
            Estamos aquí para ti. Ponte en contacto con nosotros por cualquiera de nuestros medios.
        </p>

        <ul class="contact-page__contact-list list-unstyled">
            <?php if (!empty($data['informacion_contacto']['telefono'])): ?>
            <li>
                <div class="icon">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div class="content">
                    <h3>Teléfono</h3>
                    <?php foreach ($data['informacion_contacto']['telefono'] as $tel): ?>
                    <p><a href="tel:<?php echo preg_replace('/\D+/', '', $tel); ?>"><?php echo htmlspecialchars($tel); ?></a></p>
                    <?php endforeach; ?>
                </div>
            </li>
            <?php endif; ?>

            <?php if (!empty($data['informacion_contacto']['correo'])): ?>
            <li>
                <div class="icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="content">
                    <h3>Correo electrónico</h3>
                    <?php foreach ($data['informacion_contacto']['correo'] as $correo): ?>
                    <p><a href="mailto:<?php echo htmlspecialchars($correo); ?>"><?php echo htmlspecialchars($correo); ?></a></p>
                    <?php endforeach; ?>
                </div>
            </li>
            <?php endif; ?>

            <?php if (!empty($data['informacion_contacto']['ubicacion'])): ?>
            <li>
                <div class="icon">
                    <i class="fa-solid fa-map-marker-alt"></i>
                </div>
                <div class="content">
                    <h3>Ubicación</h3>
                    <?php foreach ($data['informacion_contacto']['ubicacion'] as $ubi): ?>
                    <p><?php echo htmlspecialchars($ubi['completa']); ?></p>
                    <?php endforeach; ?>
                </div>
            </li>
            <?php endif; ?>

            <?php if (!empty($data['informacion_contacto']['whatsapp'])): ?>
            <li>
                <div class="icon">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <div class="content">
                    <h3>WhatsApp</h3>
                    <?php foreach ($data['informacion_contacto']['whatsapp'] as $wa): ?>
                    <p><a href="https://wa.me/<?php echo preg_replace('/\D+/', '', $wa['numero']); ?>" target="_blank"><?php echo htmlspecialchars($wa['numero']); ?></a></p>
                    <?php endforeach; ?>
                </div>
            </li>
            <?php endif; ?>

            <?php if (!empty($data['informacion_contacto']['horario'])): ?>
            <li>
                <div class="icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="content">
                    <h3>Horario</h3>
                    <?php foreach ($data['informacion_contacto']['horario'] as $horario): ?>
                    <p><?php echo htmlspecialchars($horario['dias']) . ': ' . htmlspecialchars($horario['horas']); ?></p>
                    <?php endforeach; ?>
                </div>
            </li>
            <?php endif; ?>
        </ul>
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