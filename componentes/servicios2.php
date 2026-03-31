<section class="blog-two">
    <div class="container">
        <div class="blog-two__top justify-content-center">
            <div class="section-title-two text-center sec-title-animation animation-style1">
                <h2 class="section-title-two__title title-animation"><?php echo $home["servicios"]["titulo"]; ?></h2>
                <h3 class="mt-3 section-title-two__tagline"><?php echo $home["servicios"]["subtitulo"]; ?></h3>
            </div>
        </div>

        <div class="row">
            <?php foreach ($data['servicios'] as $index => $servicio): ?>
                <?php if ($index < 6): ?>
                <!--Blog Two Single Start-->
            <div class="col-xl-4 col-lg-4 wow 
                <?php echo $index === 0 ? 'fadeInLeft' : ($index === 1 ? 'fadeInUp' : 'fadeInRight'); ?>" 
                data-wow-delay="<?php echo 100 + ($index * 100); ?>ms">
                <div class="blog-two__single">
                    <div class="blog-two__img-box">
                        <div class="blog-two__img" style="text-align: center; padding: 20px;">
                            <img src="assets/images/resources/servicios/<?php echo htmlspecialchars($servicio['slug']); ?>.jpg" 
                                 alt="<?php echo htmlspecialchars($servicio['nombre']); ?>" 
                                 >
                        </div>
                        <div class="blog-two__date-box">
                            <div class="blog-two__date-icon"><span class="icon-calender"></span></div>
                            <div class="blog-two__date-text"><p>Disponible</p></div>
                        </div>
                        <div class="blog-two__plus">
                            <a href="<?php echo urlencode(createSlug($servicio['slug'])); ?>">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="blog-two__content">
                        <h3 class="blog-two__title">
                            <a href="<?php echo urlencode(createSlug($servicio['slug'])); ?>">
                                <?php echo htmlspecialchars($servicio['nombre']); ?>
                            </a>
                        </h3>
                        <ul class="blog-two__meta list-unstyled">
                            <li>
                                <div class="icon"><span class="icon-user"></span></div>
                                <p>Especialidad</p>
                            </li>
                            <li>
                                <div class="icon"><span class="icon-file"></span></div>
                                <p>Atención</p>
                            </li>
                        </ul>
                        <div class="blog-two__read-more">
                            <a href="<?php echo urlencode(createSlug($servicio['slug'])); ?>">
                                Más información <span class="icon-plus"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!--Blog Two Single End-->
            <?php endforeach; ?>
        </div>
    </div>
</section>
