<footer class="site-footer mt-5">
    <div class="site-footer__bg-shape" style="background-image: url(assets/images/shapes/tratamiento-personalizado-en-urologia-en-cdmx.png);">
    </div>
    <div class="site-footer__newsletter">
        <div class="container">
            <div class="site-footer__newsletter-inner justify-content-center">
                <div class="site-footer__newsletter-inner-title-box">
                    <h2 class="site-footer__newsletter-title text-center">¿Buscas un médico especialista en
                        <?php echo $data["informacion_general"]["especialidad"]; ?> en
                        <?php echo $data["informacion_general"]["ubicacion"]; ?>? <br> <strong>No te
                            quedes sin tu cita</strong></h2>
                    <div>
                        <div class="faq-two__btn-box">
                            <a data-type="whatsapp" class="thm-btn btn-wpp">
                                Enviar WhatsApp
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                        <div class="faq-two__btn-box">
                            <a data-type="telefono" class="thm-btn">
                                Llamar por teléfono
                                <i class="icon-call"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="site-footer__top">
        <div class="container">
            <div class="site-footer__top-inner">
                <div class="row">
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                        <div class="footer-widget-two__newsletter-box">
                            <img class="image-logo" src="assets/images/logos/equipo-urologico-especializado-en-cdmx.png" alt="<?php echo $data["informacion_general"]["especialidad"] ?> en
                <?php echo $data["informacion_general"]["ubicacion"] ?> - <?php echo $data["informacion_general"]["nombre"] ?>"><br><br>
                            <p class="footer-widget-two__newsletter-text"> <?php echo $data["informacion_general"]["descripcion"]; ?></p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                        <div class="footer-widget__contact-info">
                            <h4 class="footer-widget__title">Información de contacto</h4>
                            <ul class="footer-widget__contact-list list-unstyled">
                                <?php if (!empty($data['informacion_contacto']['ubicacion'])): ?>
                                    <?php foreach ($data['informacion_contacto']['ubicacion'] as $ubicacion): ?>
                                        <li>
                                            <div class="footer-widget__contact-icon">
                                                <i class="fa-solid fa-map-marker-alt"></i>
                                            </div>
                                            <div class="footer-widget__contact-content">
                                                <span>
                                                    Ubicación
                                                </span>
                                                <p class="footer-widget__contact-text">
                                                    <?php echo htmlspecialchars($ubicacion['completa']); ?></p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if (!empty($data['informacion_contacto']['telefono'])): ?>
                                    <?php foreach ($data['informacion_contacto']['telefono'] as $telefono): ?>
                                        <li>
                                            <div class="footer-widget__contact-icon">
                                                <i class="fa-solid fa-phone"></i>
                                            </div>
                                            <div class="footer-widget__contact-content">
                                                <span>Teléfono</span>
                                                <p class="footer-widget__contact-text">
                                                    <a href="tel:<?php echo preg_replace('/\D+/', '', $telefono); ?>">
                                                        <?php echo htmlspecialchars($telefono); ?>
                                                    </a>
                                                </p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if (!empty($data['informacion_contacto']['whatsapp'])): ?>
                                    <?php foreach ($data['informacion_contacto']['whatsapp'] as $whatsapp): ?>
                                        <li>
                                            <div class="footer-widget__contact-icon">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </div>
                                            <div class="footer-widget__contact-content">
                                                <span>WhatsApp</span>
                                                <p class="footer-widget__contact-text">
                                                    <a href="https://wa.me/<?php echo preg_replace('/\D+/', '', $whatsapp['numero']); ?>"
                                                        target="_blank">
                                                        <?php echo htmlspecialchars($whatsapp['numero']); ?>
                                                    </a>
                                                </p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                            <ul class="footer-widget__contact-list list-unstyled">
                                <?php if (!empty($data['informacion_contacto']['correo'])): ?>
                                    <?php foreach ($data['informacion_contacto']['correo'] as $correo): ?>
                                        <li>
                                            <div class="footer-widget__contact-icon">
                                                <i class="fa-solid fa-envelope"></i>
                                            </div>
                                            <div class="footer-widget__contact-content">
                                                <span>Correo</span>
                                                <p class="footer-widget__contact-text">
                                                    <a href="mailto:<?php echo htmlspecialchars($correo); ?>">
                                                        <?php echo htmlspecialchars($correo); ?>
                                                    </a>
                                                </p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if (!empty($data['informacion_contacto']['horario'])): ?>
                                    <?php foreach ($data['informacion_contacto']['horario'] as $horario): ?>
                                        <li>
                                            <div class="footer-widget__contact-icon">
                                                <i class="fa-solid fa-clock"></i>
                                            </div>
                                            <div class="footer-widget__contact-content">
                                                <span><?php echo htmlspecialchars($horario['dias']); ?></span>
                                                <p class="footer-widget__contact-text">
                                                    <?php echo htmlspecialchars($horario['horas']); ?></p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul> 
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                        <div class="footer-widget__page-link">
                            
                            
                       
                            <h4 class="footer-widget__title">Enlaces de interés</h4>
                            <ul class="footer-widget__services-link-list list-unstyled">
                                <?php
                                $data3 = json_decode(file_get_contents("assets/json/seo.json"), true);
                                foreach ($data3['seo'] as $consulta):
                                    $slug = createSlug($consulta);
                                    $slugUrl = urlencode($slug);
                                ?>
                                    <li>
                                        <a href="<?php echo $slugUrl; ?>.php">
                                            <?php echo htmlspecialchars($consulta, ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="site-footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-footer__bottom-inner">
                        <div class="site-footer__copyright">
                            <p class="site-footer__copyright-text">Sitio web optimizado por <a
                                    href="https://doctorweb.agency/" class="doctorweb"> <img
                                        src="assets/images/agencia-de-marketing-medico-doctorweb.png" alt="doctorweb"
                                        class="doc1"><img src="assets/images/agencia-de-marketing-medico-doctorweb-2.png"
                                        alt="doctorweb" class="doc2"></a></p>
                        </div>
                        <div class="site-footer__bottom-menu-box">
                            <ul class="list-unstyled site-footer__bottom-menu">
                                <li><a href="aviso-de-privacidad.php">Aviso de privacidad</a></li>
                                <li><a href="terminos-y-condiciones.php">Términos y Condiciones</a></li>
                                <li><a href="descargo-de-responsabilidad.php">Descargo de Responsabilidad</a></li>
                                <li><a href="compromiso-de-etica.php">Compromiso de Ética</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>