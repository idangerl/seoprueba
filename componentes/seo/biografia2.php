<section class="about-two">
    <div class="container">
        <div class="about-two__inner">
            <div class="row">
                <!-- Imagen principal -->
                <div class="col-xl-6">
                    <div class="about-two__left">
                        <div class="about-two__img">
                            <img src="assets/images/seo/<?php echo $home["biografia"]["imagen"]; ?>" alt="<?php echo $home["banner"]["titulo"]; ?>">
                        </div>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="col-xl-6">
                    <div class="about-two__right">
                        <div class="section-title-two text-center text-lg-start sec-title-animation animation-style2">
                            <h3 class="section-title-two__title title-animation">
                                <?php echo $home["biografia"]["titulo"]; ?>
                            </h3>
                            <h6 class="mt-3 section-title-two__tagline">
                                <?php echo $home["biografia"]["subtitulo"]; ?>
                            </h6>
                            <p class="about-two__text-1">
                                <?php echo highlightKeywordAndInterlinks($home["biografia"]["texto"], $home['keyword'], $interlinks); ?>
                            </p>
                        </div>
                        <!-- Bloque de experiencia -->
                        <ul class="about-two__points-list list-unstyled">
                            <?php foreach ($data["informacion_profesional"]["formacion_profesional"] as $formacion): ?>
                            <li>
                                <div class="icon">
                                    <span class="icon-medicine-2-2"></span>
                                </div>
                                <div class="content">
                                    <h3>Formación Profesional</h3>
                                    <p><?php echo htmlspecialchars($formacion); ?></p>
                                </div>
                            </li>
                            <?php endforeach; ?>
                            <?php foreach ($data["informacion_profesional"]["cedulas_profesionales"] as $cedula): ?>
                            <li>
                                <div class="icon">
                                    <span class="icon-plaster"></span>
                                </div>
                                <div class="content">
                                    <h3>Cédula Profesional</h3>
                                    <p><?php echo htmlspecialchars($cedula); ?></p>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="about-two__img-2">
                            <img src="assets/images/resources/biografia/especialistas-en-urologia-en-cdmx.jpg" alt="Centro especializado en urología en CDMX">
                        </div>
                        <div class="about-three__awards-box">
                            <div class="about-three__awards-icon">
                                <img src="assets/images/iconos/biografia/awards-icon-1.png" alt="">
                            </div>
                            <div class="about-three__awards-content">
                                <h4>Miembro de <br><span>asociaciones</span> <br> oficiales</h4>
                            </div>
                        </div>
                        <!-- Asociaciones y Certificaciones -->
                        <div class="about-two__points-box">
                            <?php
                                $asociaciones = $data["informacion_profesional"]["asociaciones"];
                                $certificaciones = $data["informacion_profesional"]["certificaciones"];
                                $total = array_merge($asociaciones, $certificaciones);
                                $chunks = array_chunk($total, ceil(count($total) / 2));
                            ?>
                            <?php foreach ($chunks as $grupo): ?>
                            <ul class="about-two__points-2 list-unstyled">
                                <?php foreach ($grupo as $item): ?>
                                <li>
                                    <div class="icon">
                                        <span class="icon-left-arrows"></span>
                                    </div>
                                    <p><?php echo htmlspecialchars($item["nombre"]); ?></p>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>