<?php
  define('BASE_PATH', __DIR__ . '/');
  $data = json_decode(file_get_contents(BASE_PATH . "assets/json/base.json"), true);
  $servicio = preg_replace('/[^a-z0-9_-]/i', '', $_GET['servicio'] ?? 'default');
  $jsonFile = BASE_PATH . "assets/json/{$servicio}.json";
  if (file_exists($jsonFile)) {
    $data2 = json_decode(file_get_contents($jsonFile), true);
  } else {
    exit("El servicio solicitado no existe.");
  }
  require BASE_PATH . 'componentes/funciones.php';
  $servicioSlug = createSlug($servicio);
?>
<?php
// Asegúrate de que esta parte del código se coloca justo después de la etiqueta <head> en tu archivo PHP/HTML.

// Carga el JSON desde un archivo
$jsonData = file_get_contents(BASE_PATH . "assets/json/{$servicio}.json");
// Convierte la cadena JSON en una estructura de array PHP
$dataArray = json_decode($jsonData, true);

// Verifica si 'schemafaq' existe y no está vacío
if (!empty($dataArray['schemafaq'])) {
    $schemaData = $dataArray['schemafaq'];

    // Convierte la sección 'schemafaq' de vuelta a una cadena JSON,
    // utilizando JSON_UNESCAPED_UNICODE para mantener los caracteres especiales
    $schemaJsonString = json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
?>

<?php
      require 'componentes/keyword.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title><?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>
    </title>
    <meta name="description" content="<?php echo $data["informacion_general"]["descripcion"]?>">
    <meta name="keywords"
        content="<?php echo $data["informacion_general"]["especialidad"]?> en <?php echo $data["informacion_general"]["ubicacion"]?>. <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:title" content="<?php echo $data["informacion_general"]["especialidad"]?> en
        <?php echo $data["informacion_general"]["ubicacion"]?> - <?php echo $data["informacion_general"]["nombre"]?>">
    <meta property="og:description" content="<?php echo $data["informacion_general"]["descripcion"]?>">
    <?php require 'componentes/head-metas.php';?>

    <?php
    if (empty($schemaJsonString)) {
        echo "
            <script type='application/ld+json'>
                'schemafaq no disponible'
            </script>
        ";
    } elseif (!empty($schemaJsonString)) {

        echo "
            <script type='application/ld+json'>
            " . $schemaJsonString . " 
            </script>
        ";
    }
    ?>
</head>

<body class="custom-cursor">
    <?php require 'componentes/botones.php';?>
    <div class="page-wrapper">
        <?php require 'componentes/header2.php';?>
        <section class="page-header">
            <div class="page-header__bg" style="background-image: url(assets/images/backgrounds/especialistas-en-urologia-en-cdmx.jpg);">
            </div>
            <div class="container">
                <div class="page-header__inner">
                    <h1><?php echo htmlspecialchars($data2['article']['title']); ?></h1>
                </div>
            </div>
        </section>
        <section class="service-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="service-details__left">
                            <?php
                    $imagePath = getImagePath('assets/images/resources/servicios/', htmlspecialchars($servicioSlug));
                    if (!empty($imagePath)) {
                        echo '<div class="service-details__img">';
                        echo '<img src="assets/images/resources/servicios/' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($data2['article']['title']) . '">';
                        echo '</div>';
                    }
                    ?>

                            <div class="service-details__content">
                                <h1 class="service-details__title-1">
                                    <?php echo htmlspecialchars($data2['article']['title']); ?>
                                </h1>

                                <p class="service-details__text-1">
                                    <?php echo nl2br(highlightKeywordAndInterlinks(htmlspecialchars($data2['article']['content'][0]['content']), $keyword, $interlinks)); ?>
                                </p>

                                <?php if (!empty($data2['article']['video'])): ?>
                                <div class="service-details__video mb-4">
                                    <iframe
                                        src="<?php echo nl2br(highlightKeywordAndInterlinks(htmlspecialchars($data2['article']['video']), $keyword, $interlinks)); ?>"
                                        width="100%" height="400px" frameborder="0"></iframe>
                                </div>
                                <?php endif; ?>

                                <?php
                        for ($i = 1; $i <= 20; $i++) {
                            if (!empty($data2['article']['content'][$i]['title']) && !empty($data2['article']['content'][$i]['content'])) {
                                $headerLevel = ($i + 1 > 6) ? 6 : $i + 1;
                                $title = htmlspecialchars($data2['article']['content'][$i]['title']);
                                $content = $data2['article']['content'][$i]['content'];

                                echo "<h{$headerLevel} class='service__title mb-15'>{$title}</h{$headerLevel}>";

                                if (is_array($content)) {
                                    foreach ($content as $faq) {
                                        $question = htmlspecialchars($faq['question']);
                                        $answer = htmlspecialchars($faq['answer']);
                                        echo "<div class='faq'>";
                                        echo "<h3 class='faq__question'>{$question}</h3>";
                                        echo "<p class='faq__answer'>{$answer}</p>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<p class='mb-25'>" . nl2br(highlightKeywordAndInterlinks($content, $keyword, $interlinks)) . "</p>";
                                }
                            }
                        }
                        ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-5">
                        <div class="service-details__right">
                            <div class="service-details__services-box">
                                <h3 class="service-details__service-title">Otros servicios</h3>
                                <ul class="service-details__service-list list-unstyled">
                                    <?php foreach ($data['servicios'] as $servicio): ?>
                                    <li>
                                        <a
                                            href="<?php echo urlencode(createSlug($servicio['slug'])); ?>">
                                            <span class="icon-left-arrows"></span>
                                            <?php echo htmlspecialchars($servicio['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="service-details__need-help-inner">
                                <div class="service-details__need-help">
                                    <div class="service-details__need-help-bg"
                                        style="background-image: url(assets/images/backgrounds/centro-urologico-masculino-en-cdmx.jpg);">
                                    </div>
                                    <h3 class="service-details__need-help-title">Contáctanos</h3>

                                    <!-- Teléfonos -->
                                    <?php if (!empty($data['informacion_contacto']['telefono'])): ?>
                                    <div class="service-details__need-help-icon">
                                        <span class="icon-call"></span>
                                    </div>
                                    <?php foreach ($data['informacion_contacto']['telefono'] as $index => $telefono): ?>
                                    <div class="service-details__need-help-call">
                                        <a data-type="telefono<?php echo ($index > 0) ? $index+1 : ''; ?>"
                                            href="tel:<?php echo $telefono; ?>">
                                            <?php echo $telefono; ?>
                                        </a>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>

                                    <!-- WhatsApp -->
                                    <?php if (!empty($data['informacion_contacto']['whatsapp'])): ?>
                                    <div class="service-details__need-help-icon">
                                        <span class="fab fa-whatsapp"></span>
                                    </div>
                                    <?php foreach ($data['informacion_contacto']['whatsapp'] as $index => $whatsapp): ?>
                                    <div class="service-details__need-help-call">
                                        <a data-type="whatsapp<?php echo ($index > 0) ? $index+1 : ''; ?>" href="#">
                                            <?php echo $whatsapp['numero']; ?>
                                        </a>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>

                                    <!-- Correos -->
                                    <?php if (!empty($data['informacion_contacto']['correo'])): ?>
                                    <div class="service-details__need-help-icon">
                                        <span class="icon-envolope"></span>
                                    </div>
                                    <?php foreach ($data['informacion_contacto']['correo'] as $index => $correo): ?>
                                    <div class="service-details__need-help-call">
                                        <a data-type="correo<?php echo ($index > 0) ? $index+1 : ''; ?>"
                                            href="mailto:<?php echo $correo; ?>">
                                            <?php echo $correo; ?>
                                        </a>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>

                                    <!-- Ubicaciones -->
                                    <?php if (!empty($data['informacion_contacto']['ubicacion'])): ?>
                                    <div class="service-details__need-help-icon">
                                        <span class="fa fa-map"></span>
                                    </div>
                                    <?php foreach ($data['informacion_contacto']['ubicacion'] as $index => $ubicacion): ?>
                                    <div class="service-details__need-help-call">
                                        <a data-type="ubicacion<?php echo ($index > 0) ? $index+1 : ''; ?>">
                                            <?php echo $ubicacion['completa']; ?>
                                        </a>
                                    </div>
                                    <br>
                                    <?php endforeach; ?>
                                    <?php endif; ?>

                                    <!-- Horarios -->
                                    <?php if (!empty($data['informacion_contacto']['horario'])): ?>
                                    <div class="service-details__need-help-icon">
                                        <span class="fa fa-clock"></span>
                                    </div>
                                    <?php foreach ($data['informacion_contacto']['horario'] as $horario): ?>
                                    <div class="service-details__need-help-call">
                                        <span><?php echo $horario['dias']; ?>: <?php echo $horario['horas']; ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

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
