<section class="faq-two">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-xl-5">
                <div class="faq-two__left">
                    <div class="section-title-two text-center text-lg-start sec-title-animation animation-style1">
                        <h3 class="section-title-two__title title-animation">
                            <?php echo $home["preguntas"]["titulo"]; ?>
                        </h3>

                        <h6 class="mt-3 section-title-two__tagline">
                            <?php echo $home["preguntas"]["subtitulo"]; ?>
                        </h6>
                    </div>
                    <p class="faq-two__text text-center text-lg-start">
                        <?php echo $home["preguntas"]["descripcion"] ?? 'Consulta nuestras preguntas frecuentes y resuelve tus dudas con confianza.'; ?>
                    </p>
                    <div class="faq-two__btn-box">
                        <a href="contacto.php" class="thm-btn">Contáctanos <i class="icon-call"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-7">
                <div class="faq-two__right">
                    <div class="accrodion-grp faq-one-accrodion" data-grp-name="faq-one-accrodion-1">
                        <?php
                        $preguntas = $home['preguntas']['preguntas'];
                        $delay = 100;
                        $index = 0;
                        foreach ($preguntas as $nombre => $texto):
                        ?>
                        <div class="accrodion <?php echo $index === 1 ? 'active' : ''; ?> wow fadeIn<?php echo $index % 2 === 0 ? 'Left' : 'Right'; ?>" data-wow-delay="<?php echo $delay; ?>ms">
                            <div class="accrodion-title">
                                <div class="faq-two-accrodion__count"></div>
                                <h4><?php echo htmlspecialchars($nombre); ?></h4>
                            </div>
                            <div class="accrodion-content">
                                <div class="inner">
                                    <p><?php echo htmlspecialchars($texto); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                            $delay += 100;
                            $index++;
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
