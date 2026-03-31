<section class="testimonial-two">
    <div class="container">
        <div class="section-title-two text-center sec-title-animation animation-style1">
            <h3 class="section-title-two__title title-animation"><?php echo $home["testimonios"]["titulo"]; ?></h3>
            <h6 class="mt-3 section-title-two__tagline"><?php echo $home["testimonios"]["subtitulo"]; ?></h6>
        </div>
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <div class="testimonial-two__left">
                    <div class="testimonial-two__carousel owl-theme owl-carousel">
                        <?php foreach ($home['testimonios']['testimonios'] as $testimonio): ?>
                        <!--Testimonial Two Single Start -->
                        <div class="item">
                            <div class="testimonial-two__single">
                                <div class="testimonial-two__client-info">
                                    <h4 class="testimonial-two__client-name">
                                        <?php echo htmlspecialchars($testimonio['nombre']); ?>
                                    </h4>
                                    <p class="testimonial-two__client-sub-title">Paciente</p>
                                </div>
                                <div class="testimonial-two__rating">
                                    <span class="icon-star"></span>
                                    <span class="icon-star"></span>
                                    <span class="icon-star"></span>
                                    <span class="icon-star"></span>
                                    <span class="icon-star icon-star-color"></span>
                                </div>
                                <p class="testimonial-two__text">
                                    <?php echo htmlspecialchars($testimonio['texto']); ?>
                                </p>
                            </div>
                        </div>
                        <!--Testimonial Two Single End -->
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="testimonial-two__right">
                    <div class="testimonial-two__img">
                        <img src="assets/images/resources/testimonios/<?php echo htmlspecialchars($home["testimonios"]['imagen']); ?>" alt="<?php echo htmlspecialchars($home['testimonios']['alt']); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


