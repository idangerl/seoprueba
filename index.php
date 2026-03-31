<?php
  $data = json_decode(file_get_contents("assets/json/base.json"), true);
  $home = json_decode(file_get_contents("assets/json/home.json"), true);
  require 'componentes/funciones.php';
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
    if (!$home["schema"]) {
        echo "
            <script type='application/ld+json'>
                'schemafaq no disponible'
            </script>
        ";
    } elseif ($home["schema"]) {
        $jsonSchema = json_encode($home["schema"], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        echo "
            <script type='application/ld+json'>
            " . $jsonSchema . " 
            </script>
        ";
    }
    ?>
</head>
<body class="custom-cursor">
    <?php require 'componentes/botones.php';?>
    <div class="page-wrapper">
        <?php require 'componentes/header2.php';?>
        <?php require 'componentes/banner2.php';?>
        <?php require 'componentes/biografia2.php';?>
        <?php require 'componentes/beneficios2.php';?>
        <?php require 'componentes/servicios2.php';?>
        <?php require 'componentes/testimonios2.php';?>
        <?php require 'componentes/preguntas2.php';?>
        <?php require 'componentes/footer.php';?>
    </div>
    <?php require 'componentes/js.php';?>
</body>
</html>