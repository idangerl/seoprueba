<section class="services-two">
    <div class="container">
        <div class="section-title-two text-center sec-title-animation animation-style1">
            
            <h2 class="section-title-two__title title-animation">
                <?php echo $home["beneficios"]["titulo"]; ?>
            </h2>
            <h3 class="mt-3 section-title-two__tagline">
                <?php echo $home["beneficios"]["subtitulo"]; ?>
            </h3>
        </div>

        <div class="services-two__inner">
            <ul class="row justify-content-center list-unstyled">
                <?php foreach ($home["beneficios"]["beneficios"] as $index => $beneficio): ?>
                <!--Services Two Single Start-->
                <li class="col-xl-4 col-lg-4 col-xl-6 wow fadeInUp" data-wow-delay="<?php echo 100 + ($index * 100); ?>ms">
                    <div class="services-two__single">
                        <h3 class="services-two__title">
                            <?php echo htmlspecialchars($beneficio["titulo"]); ?>
                        </h3>
                        <div class="services-two__icon">
                            <img src="assets/images/iconos/beneficios/<?php echo htmlspecialchars($beneficio["imagen"]); ?>" 
                                 alt="<?php echo htmlspecialchars($beneficio["titulo"]); ?>" 
                                 style="max-width: 50px;">
                        </div>
                        <p class="services-two__text">
                            <?php echo htmlspecialchars($beneficio["texto"]); ?>
                        </p>
                    </div>
                </li>
                <!--Services Two Single End-->
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
