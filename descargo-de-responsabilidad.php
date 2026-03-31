<?php
  $data = json_decode(file_get_contents("assets/json/base.json"), true);
  $home = json_decode(file_get_contents("assets/json/home.json"), true);
  require 'componentes/funciones.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>DESCARGO DE RESPONSABILIDAD - <?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>
    </title>
    <meta name="description"
        content="DESCARGO DE RESPONSABILIDAD - <?php echo $data["informacion_general"]["descripcion"]?>">
    <meta name="keywords"
        content="DESCARGO DE RESPONSABILIDAD, <?php echo $data["informacion_general"]["especialidad"]?> en <?php echo $data["informacion_general"]["ubicacion"]?>. <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:title" content="DESCARGO DE RESPONSABILIDAD - <?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:description"
        content="DESCARGO DE RESPONSABILIDAD - <?php echo $data["informacion_general"]["descripcion"]?>">
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
                    <h1>DESCARGO DE RESPONSABILIDAD</h1>
                </div>
            </div>
        </section>
        <section class="service-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 col-lg-7">
                        <div class="service-details__left">
                            <h2>DESCARGO DE RESPONSABILIDAD - <?php echo $data["informacion_general"]["nombre"] ?> en
                                <?php echo $data["informacion_general"]["ubicacion"] ?></h2>

                            <h2>1. Resultados Variables</h2>
                            <p>1.1. Cada paciente es único, y los resultados de cualquier tratamiento médico pueden
                                variar dependiendo de factores individuales como la condición médica, el estado de salud
                                general y la adherencia al plan de tratamiento.</p>
                            <p>1.2. No garantizamos resultados específicos, ya que la medicina es una ciencia basada en
                                probabilidades, no certezas absolutas.</p>

                            <h2>2. Información General y Educativa</h2>
                            <p>2.1. Los contenidos de este sitio están diseñados únicamente para proporcionar
                                información general sobre condiciones médicas, tratamientos disponibles y servicios
                                ofrecidos.</p>
                            <p>2.2. Esta información no debe interpretarse como un consejo médico personalizado. Siempre
                                recomendamos que consultes a un médico especialista para valorar tu situación
                                particular.</p>

                            <h2>3. Limitación de Responsabilidad</h2>
                            <p>3.1. No nos hacemos responsables de decisiones tomadas por los usuarios basándose
                                exclusivamente en la información de este sitio.</p>
                            <p>3.2. Los enlaces externos que puedan incluirse en esta página son proporcionados para tu
                                conveniencia, pero no garantizamos ni somos responsables de la exactitud o calidad de la
                                información en esos sitios.</p>

                            <h2>4. Procedimientos Médicos</h2>
                            <p>4.1. Cualquier procedimiento, tratamiento o intervención médica mencionado en este sitio
                                requiere una valoración previa por parte de un profesional de la salud calificado.</p>
                            <p>4.2. Las decisiones médicas deben tomarse en un entorno clínico adecuado, tras una
                                evaluación detallada de tu condición.</p>

                            <h2>5. Actualización de Contenidos</h2>
                            <p>5.1. Nos esforzamos por mantener la información actualizada, pero no garantizamos que
                                todo el contenido esté completamente vigente o sea aplicable en todas las situaciones.
                            </p>
                            <p>5.2. La medicina está en constante evolución, y algunos tratamientos o procedimientos
                                mencionados pueden no ser adecuados para todos los pacientes.</p>

                            <h2>6. Consulta Médica Obligatoria</h2>
                            <p>6.1. Te recordamos que siempre debes buscar el consejo de un médico calificado para
                                cualquier duda o problema relacionado con tu salud.</p>
                            <p>6.2. Nunca ignores un consejo médico profesional o retrases la búsqueda de atención
                                médica debido a algo que hayas leído en este sitio.</p>

                            <h2>7. Contacto</h2>
                            <p>Si tienes preguntas o necesitas más información, te invitamos a contactarnos directamente
                                a través de los siguientes medios:</p>
                            <ul>
                                <?php if (!empty($data["informacion_contacto"]["telefono"][0])): ?>
                                <li><strong>Teléfono:</strong>
                                    <?php echo htmlspecialchars($data["informacion_contacto"]["telefono"][0]); ?></li>
                                <?php endif; ?>

                                <?php if (!empty($data["informacion_contacto"]["correo"][0])): ?>
                                <li><strong>Correo Electrónico:</strong> <a
                                        href="mailto:<?php echo htmlspecialchars($data["informacion_contacto"]["correo"][0]); ?>"><?php echo htmlspecialchars($data["informacion_contacto"]["correo"][0]); ?></a>
                                </li>
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