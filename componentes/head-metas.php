<?php require 'componentes/canonical.php'?>

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png">
<link rel="manifest" href="assets/images/favicons/site.webmanifest">
<link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&amp;display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="assets/css/animate.min.css" />
<link rel="stylesheet" href="assets/css/custom-animate.css" />
<link rel="stylesheet" href="assets/css/swiper.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="assets/css/jarallax.css" />
<link rel="stylesheet" href="assets/css/jquery.magnific-popup.css" />
<link rel="stylesheet" href="assets/css/flaticon.css">
<link rel="stylesheet" href="assets/css/owl.carousel.min.css" />
<link rel="stylesheet" href="assets/css/odometer.min.css" />
<link rel="stylesheet" href="assets/css/owl.theme.default.min.css" />
<link rel="stylesheet" href="assets/css/nice-select.css" />
<link rel="stylesheet" href="assets/css/jquery-ui.css" />
<link rel="stylesheet" href="assets/css/aos.css" />
<link rel="stylesheet" href="assets/css/timePicker.css" />
<link rel="stylesheet" href="assets/css/module-css/slider.css" />
<link rel="stylesheet" href="assets/css/module-css/banner.css" />
<link rel="stylesheet" href="assets/css/module-css/footer.css" />
<link rel="stylesheet" href="assets/css/module-css/feature.css" />
<link rel="stylesheet" href="assets/css/module-css/about.css" />
<link rel="stylesheet" href="assets/css/module-css/brand.css" />
<link rel="stylesheet" href="assets/css/module-css/service.css" />
<link rel="stylesheet" href="assets/css/module-css/project.css" />
<link rel="stylesheet" href="assets/css/module-css/team.css" />
<link rel="stylesheet" href="assets/css/module-css/faq.css" />
<link rel="stylesheet" href="assets/css/module-css/testimonial.css" />
<link rel="stylesheet" href="assets/css/module-css/blog.css" />
<link rel="stylesheet" href="assets/css/module-css/contact.css" />
<link rel="stylesheet" href="assets/css/module-css/page-header.css" />
<link rel="stylesheet" href="assets/css/style.css" />
<link rel="stylesheet" href="assets/css/responsive.css" />
<link rel="stylesheet" href="assets/css/plantilla.css" />
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NT5GRWBJ');</script>
<!-- End Google Tag Manager -->


<?php
$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$host_con_www = preg_replace('/^www\./', '', $host);
$host_con_www = 'www.' . $host_con_www;
$canonical = $protocolo . $host_con_www . $uri;
?>
<link rel="canonical" href="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">