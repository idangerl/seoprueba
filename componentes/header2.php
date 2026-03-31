<header class="main-header-two">
    <div class="main-header-two__wrapper">
        <nav class="main-menu main-menu-two">
            <div class="main-menu-two__wrapper">
                <div class="container">
                    <div class="main-menu-two__wrapper-inner">
                        <div class="main-menu-two__left">
                            <div class="main-menu-two__logo">
                                <a href="index.php">
                                    <img src="assets/images/logos/tecnologia-urologica-avanzada-en-cdmx.png"
                                        alt="<?php echo $data["informacion_general"]["especialidad"]; ?> en <?php echo $data["informacion_general"]["ubicacion"]; ?> - <?php echo $data["informacion_general"]["nombre"]; ?>">
                                </a>
                            </div>
                        </div>
                        <div class="main-menu-two__main-menu-box">
                            <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                            <ul class="main-menu__list">
                                <li><a href="index.php">Inicio</a></li>
                                <li>
                                    <a href="sobre-mi.php?nombre=<?php echo urlencode(createSlug($data["informacion_general"]["nombre"])); ?>-<?php echo urlencode(createSlug($data["informacion_general"]["especialidad"])); ?>-<?php echo urlencode(createSlug($data["informacion_general"]["ubicacion"])); ?>">Sobre mí</a>
                                </li><?php
/* SEO-NAV-START */
// Inyectar enlaces SEO desde mapa-seo.json
$_seoNavFile = dirname(__DIR__) . '/seo-engine/seo-nav-items.php';
if (is_file($_seoNavFile)) {
    require_once $_seoNavFile;
    echo seo_generate_nav_items(
        dirname(__DIR__) . '/seo-engine/mapa-seo.json',
        'php-isolated',
        'seo'
    );
}
/* SEO-NAV-END */
?>

                                <?php
                                $servicios_por_categoria = [];

                                foreach ($data['servicios'] as $servicio) {
                                    $categoria = $servicio['categoria'] ?? 'Servicios';
                                    $servicios_por_categoria[$categoria][] = $servicio;
                                }

                                foreach ($servicios_por_categoria as $categoria => $servicios): ?>
                                    <li class="dropdown">
                                        <a href="#"><?php echo htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8'); ?></a>
                                        <ul>
                                            <?php foreach ($servicios as $servicio): ?>
                                                <li>
                                                    <a href="<?php echo urlencode(createSlug($servicio['slug'])); ?>">
                                                        <?php echo htmlspecialchars($servicio['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endforeach; ?>

                                <li><a href="contacto.php">Contacto</a></li>
                            </ul>
                        </div>
                        <div class="main-menu-two__right">
                            <div class="main-menu-two__btn">
                                <a data-type="whatsapp" class="thm-btn">Consulta <span class="icon-plus"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
/* SEO-NAV-ASSETS-START */
$_seoNavFile = dirname(__DIR__) . '/seo-engine/seo-nav-items.php';
if (is_file($_seoNavFile)) {
    require_once $_seoNavFile;
    echo seo_nav_assets_markup();
}
/* SEO-NAV-ASSETS-END */
?></nav>
    </div>
</header>

<div class="stricky-header stricked-menu main-menu">
    <div class="sticky-header__content"></div>
</div>
<div class="mobile-nav__wrapper">
    <div class="mobile-nav__overlay mobile-nav__toggler"></div>
    <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>
        <div class="logo-box">
            <a href="index.php" aria-label="logo image"><img src="assets/images/logos/tecnologia-urologica-avanzada-en-cdmx.png" width="135"
                    alt="" /></a>
        </div>
        <div class="mobile-nav__container"></div>
        <ul class="mobile-nav__contact list-unstyled">
            <?php
if (isset($data['informacion_contacto'])):
    $info = $data['informacion_contacto'];
?>

            <?php if (!empty($info['ubicacion'])): ?>
            <?php foreach ($info['ubicacion'] as $index => $ubicacion): ?>
            <li>
                <i class="fa-solid fa-map-marker-alt"></i>
                <span><?php echo htmlspecialchars($ubicacion['completa']); ?></span>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($info['telefono'])): ?>
            <?php foreach ($info['telefono'] as $telefono): ?>
            <li>
                <i class="fa-solid fa-phone"></i>
                <a href="tel:<?php echo preg_replace('/\D+/', '', $telefono); ?>">
                    <?php echo htmlspecialchars($telefono); ?>
                </a>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($info['whatsapp'])): ?>
            <?php foreach ($info['whatsapp'] as $wa): ?>
            <li>
                <i class="fa-brands fa-whatsapp"></i>
                <a href="https://wa.me/<?php echo preg_replace('/\D+/', '', $wa['numero']); ?>" target="_blank">
                    <?php echo htmlspecialchars($wa['numero']); ?>
                </a>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($info['correo'])): ?>
            <?php foreach ($info['correo'] as $correo): ?>
            <li>
                <i class="fa-solid fa-envelope"></i>
                <a href="mailto:<?php echo htmlspecialchars($correo); ?>">
                    <?php echo htmlspecialchars($correo); ?>
                </a>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($info['horario'])): ?>
            <?php foreach ($info['horario'] as $horario): ?>
            <li>
                <i class="fa-solid fa-clock"></i>
                <span><?php echo htmlspecialchars($horario['dias']) . ': ' . htmlspecialchars($horario['horas']); ?></span>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php endif; ?>

        </ul>
        <?php if (isset($informacion_contacto['redes_sociales'])): ?>
        <div class="mobile-nav__top">
            <div class="mobile-nav__social">
                <?php foreach ($informacion_contacto['redes_sociales'] as $nombre_red => $url):
                $icono = getSocialMediaIcon($nombre_red); // Esta función debe devolver solo el nombre del ícono, ej: "fa-twitter"
            ?>
                <a href="<?php echo htmlspecialchars($url); ?>" class="fab <?php echo htmlspecialchars($icono); ?>"
                    target="_blank" data-type="<?php echo htmlspecialchars($nombre_red); ?>"></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>