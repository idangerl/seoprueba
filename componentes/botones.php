<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NT5GRWBJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="custom-cursor__cursor"></div>
    <div class="custom-cursor__cursor-two"></div>

    <div class="preloader">
        <div class="preloader__image"></div>
    </div>
    <?php
$informacion_contacto = $data['informacion_contacto'];
?>
<div class="contacto">
    <!--------------------Start Buttons Only Mobile-------------------->
    <a data-type="whatsapp" class="thm-btn btn-wpp gap-2 btn-mobile mobile-on">
        <i class="fab fa-whatsapp"></i>
        WhatsApp 
    </a>

    <a data-type="telefono" class="thm-btn gap-2 btn-mobile mobile-on">
        <i class="icon-call"></i>
        Teléfono
    </a>
    <!--------------------End Buttons Only Mobile-------------------->

    <!-- Teléfono -->
    <?php if (!empty($informacion_contacto['telefono'])): ?>
    <a class="mobile-disable" data-type="telefono">
        <i class="fa-solid fa-phone"></i><span>Llamar por teléfono</span>
    </a>
    <?php endif; ?>

    <!-- Correo -->
    <?php if (!empty($informacion_contacto['correo'])): ?>
    <a class="mobile-disable" data-type="correo">
        <i class="fa-solid fa-envelope"></i><span> Enviar Correo</span>
    </a>
    <?php endif; ?>

    <!-- Ubicaciónes -->
    
    

     
    


    <?php $counter = 0; foreach ($informacion_contacto['ubicacion'] as $ubicacion): ?>
        <?php if (!empty($ubicacion)): ?>
            <a class="mobile-disable" data-type="ubicacion<?php echo ($counter > 0) ? $counter + 1 : ''; ?>">
                <i class="fa-solid fa-map-marker-alt"></i>
                <span> 
                    <?php if (count($informacion_contacto['ubicacion'])>=2): ?>
                        <?php echo $ubicacion['simplificada']; ?>
                    <?php endif; ?>

                    <?php if (count($informacion_contacto['ubicacion'])==1): ?>
                        Ubicación en Google Maps
                    <?php endif; ?>
                </span>
            </a>
        <?php endif; ?>
    <?php $counter++; endforeach; ?>

    <!-- Redes Sociales -->
    <?php if (!empty($informacion_contacto['redes_sociales'])): ?>
    <?php foreach ($informacion_contacto['redes_sociales'] as $red => $url): ?>
        <?php if (!empty($url)): ?>
            <a class="mobile-disable" href="<?php echo htmlspecialchars($url); ?>" target="_blank" data-type="<?php echo htmlspecialchars($red); ?>">
                <i class="fa-brands fa-<?php echo htmlspecialchars($red); ?>"></i><span>Perfil de <?php echo ucfirst($red); ?></span>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<a data-type="whatsapp" class="fullwidth">
    <div class="fab2 btn-wpp">
        <i class="fa-brands fa-whatsapp"></i>
    </div>
</a>

