<?php
  $data = json_decode(file_get_contents("assets/json/base.json"), true);
  $home = json_decode(file_get_contents("assets/json/enfermedades-de-la-prostata-en-iztapalapa-cdmx.json"), true);
  require 'componentes/funciones.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
<title><?php echo $home["keyword"] ?> en
        <?php echo $data["informacion_general"]["ubicacion"] ?> - <?php echo $data["informacion_general"]["nombre"] ?>
    </title>
    <meta name="description" content="<?php echo $home["banner"]["texto"]; ?>">
    <meta name="keywords"
        content="<?php echo $home["keyword"] ?> en <?php echo $data["informacion_general"]["ubicacion"] ?>, <?php echo $data["informacion_general"]["nombre"] ?>">
    <meta property="og:title" content="<?php echo $home["keyword"] ?> en
        <?php echo $data["informacion_general"]["ubicacion"] ?> - <?php echo $data["informacion_general"]["nombre"] ?>">
    <meta property="og:description" content="<?php echo $home["banner"]["texto"]; ?>">
    <?php require 'componentes/head-metas.php'; ?>
    <?php
    require 'componentes/keyword-home.php';
    ?>
   

</head>
<body class="custom-cursor">
    <?php require 'componentes/botones.php';?>
    <div class="page-wrapper">
        <?php require 'componentes/header2.php';?>
        <?php require 'componentes/seo/banner2.php';?>
        <?php require 'componentes/seo/biografia2.php';?>
        <?php require 'componentes/seo/beneficios2.php';?>
        <?php require 'componentes/seo/testimonios2.php';?>
        <?php require 'componentes/seo/preguntas2.php';?>

        <?php require 'componentes/footer.php';?>
    </div>
    <?php require 'componentes/js.php';?>
</body>
</html>