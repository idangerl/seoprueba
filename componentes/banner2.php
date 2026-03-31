<section class="main-slider m_s-t">
    <div class="swiper-container thm-swiper__slider" data-swiper-options='{
        "slidesPerView": 1,
        "loop": false,
        "effect": "fade",
        "pagination": {
            "el": "#main-slider-pagination",
            "type": "bullets",
            "clickable": true
        },
        "navigation": {
            "nextEl": "#main-slider__swiper-button-next",
            "prevEl": "#main-slider__swiper-button-prev"
        },
        "autoplay": {
            "delay": 8000
        }
    }'>
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="main-slider__shape-1"></div>
                <div class="main-slider__shape-2"></div>
                <div class="main-slider__shape-3"></div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider__content text-center text-lg-start">
                                
                                <h1 class="main-slider__title main-slider__title-two">
                                    <?php echo nl2br($home["banner"]["titulo"]); ?>
                                </h1>
                                <p class="main-slider__sub-title main-slider__sub-title-two">
                                    <?php echo $home["banner"]["subtitulo"]; ?>
                                </p>
                                <p class="main-slider__text pt-4 main-slider__text-two">
                                    <?php echo $home["banner"]["texto"]; ?>
                                </p>

                                <!-- Botones con contacto -->
                                <div class="main-slider__btn-and-video-box align-items-center align-items-lg-start">
                                    <?php if (!empty($home["banner"]["telefono"])): ?>
                                        <div class="main-slider__btn-box">
                                            <a class="thm-btn" data-type="telefono">
                                                <i class="fa-solid fa-phone"></i>
                                                <?php echo htmlspecialchars($home["banner"]["telefono"]); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($home["banner"]["whatsapp"])): ?>
                                        <div class="main-slider__btn-box">
                                            <a class="thm-btn btn-wpp" data-type="whatsapp">
                                                <i class="fa-brands fa-whatsapp"></i>
                                                <?php echo htmlspecialchars($home["banner"]["whatsapp"]); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Imagen -->
                                <div class="main-slider__img-box">
                                    <div class="main-slider__img wow slideInRight" data-wow-delay="100ms" data-wow-duration="2000ms">
                                        <img src="assets/images/resources/banner/<?php echo htmlspecialchars($home["banner"]["imagen"]); ?>" alt="<?php echo htmlspecialchars($home["banner"]["alt"]); ?>">
                                    </div>
                                </div>
                            </div> <!-- /.main-slider__content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

