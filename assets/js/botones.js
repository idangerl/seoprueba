// DEFINE AQUÍ EL ID DEL GTM
const GTM_ID = 'GTM-5ZFPSPQG'; 
$(document).ready(function() {
    if (GTM_ID && GTM_ID.trim() !== "") {
        const GTM_HEAD = `
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','${GTM_ID}');</script>
        <!-- End Google Tag Manager -->
        `;
        const GTM_BODY = `
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=${GTM_ID}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        `;
        $('head').append(GTM_HEAD);
        $('body').prepend(GTM_BODY);
    } else {
        console.log("GTM ID no definido. El código de GTM no fue añadido.");
    }
});

let urls = {};

$.ajax({
    url: 'assets/json/base.json',
    dataType: 'json',
    success: function(data) {
        
        for (let key in data.informacion_contacto) {
            if (data.informacion_contacto.hasOwnProperty(key)) {
                let contactoInfo = data.informacion_contacto[key];

                // Caso especial para whatsapp
                if (key === "whatsapp") {
                    contactoInfo.forEach((whatsapp, index) => {
                        let urlKey = (index === 0) ? "whatsapp" : "whatsapp" + (index + 1);
                        urls[urlKey] = "https://wa.me/" + whatsapp.numero + "/?text=" + encodeURIComponent(whatsapp.mensaje);
                    });
                } 
                else if (key === "ubicacion") {
                    contactoInfo.forEach((ubicacion, index) => {
                        let urlKey = (index === 0) ? "ubicacion" : "ubicacion" + (index + 1);
                        urls[urlKey] = ubicacion.url;
                    });
                } else if (key === "telefono") {
                    contactoInfo.forEach((telefono, index) => {
                        let urlKey = (index === 0) ? "telefono" : "telefono" + (index + 1);
                        urls[urlKey] = "tel:" + telefono;
                    });
                } else if (key === "correo") {
                    contactoInfo.forEach((correo, index) => {
                        let urlKey = (index === 0) ? "correo" : "correo" + (index + 1);
                        urls[urlKey] = "mailto:" + correo;
                    });
                } else if (key === "redes_sociales") {  // Caso especial para redes_sociales
                    for (let redSocial in contactoInfo) {
                        urls[redSocial] = contactoInfo[redSocial];
                    }
                } else {
                    urls[key] = contactoInfo; // Para el resto, simplemente asignamos el valor
                }
            }
        }

        // Inicializar los botones de acción con la información de contacto
        initActionCta();
    },
    error: function(err) {
        console.error("Error al obtener el JSON:", err);
    }
});

function initActionCta() {
    $("[data-type]").each(function() {
        const $this = $(this);
        const type = $this.data('type');

        // Asegúrate de que las URLs ya están configuradas
        if (urls[type]) {
            let title;
            let ariaLabel;

            switch (type) {
                case "correo":
                    title = "Enviar correo";
                    ariaLabel = "Enviar correo";
                    break;
                case "whatsapp":
                    title = "Enviar WhatsApp";
                    ariaLabel = "Enviar WhatsApp";
                    break;
                case "telefono":
                    title = "Llamar por teléfono";
                    ariaLabel = "Llamar por teléfono";
                    break;
                case "ubicacion":
                    title = "Ubicación en Google Maps";
                    ariaLabel = "Ubicación en Google Maps";
                    break;
                default:
                    // Si no es ninguno de los anteriores, asumimos que es una red social
                    title = `Perfil de ${capitalizeFirstLetter(type)}`;
                    ariaLabel = `Perfil de ${capitalizeFirstLetter(type)}`;
            }

            $this.attr({
                'href': urls[type],
                'title': title,
                'aria-label': ariaLabel,
                'role': 'button'
            });

            // Aquí agregamos el evento 'click' que abrirá la URL en una nueva ventana y redirigirá la actual
            $this.on('click', function(event) {
                event.preventDefault(); // Evita que el enlace se siga inmediatamente
                window.open(urls[type], '_blank'); // Abre la URL en una nueva pestaña
                redirectToThankYouPage(type); // Redirige la pestaña actual
            });
        }
    });
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function redirectToThankYouPage(type) {
    let redirectUrl;

    if (/whatsapp/.test(type)) {
        redirectUrl = 'gracias-whatsapp.php';
    } else if (/telefono/.test(type)) {
        redirectUrl = 'gracias-telefono.php';
    } else {
        console.log("Unknown CTA, no redirection performed.");
        return;
    }

    window.location.href = redirectUrl;
}
