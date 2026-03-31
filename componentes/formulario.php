<style>

</style>
<div id="banner-form">
    <div class="head">
        <div class="profile-image">
            <img src="assets/images/logos/diagnostico-y-tratamiento-urologico-en-cdmx.png" width="100%" alt="">
            <div class="circle"></div>
        </div>
        <div class="profile-info">
            <span class="nombredeldoctor"></span>
            <span><strong class="especialidadoctor"></strong></span>
        </div>
    </div>
    <form class="formulario">
    <div>
            <input type="hidden" name="nombredeldoctor" value="<?php echo $data["informacion_general"]["nombre"]; ?>">
            <input type="hidden" name="numerodeldoctor" value="<?php echo $data["informacion_contacto"]["whatsapp"][0]["numero"]; ?>">
            <input type="hidden" name="especialidadoctor" value="<?php echo $data["informacion_general"]["especialidad"]; ?>">
        </div>
        <div class="input-g text-center">
            <p class="titulo">
                Completa el Formulario a Continuación
            </p>
            <p class="texto">
                Rellena los campos a continuación y el doctor <strong class="especialidadoctor2"></strong> te
                atenderá para darte
                información de tu problema o agendarte una consulta.
            </p>
        </div>
        <div class="input-contenedor">
            <div class="input-g2">
                <input name="paciente" type="text" placeholder="Nombre del paciente" required />
            </div>
            <div class="input-g2">
                <input name="telefonopaciente" type="number" placeholder="Teléfono" minlength="8" required />
            </div>
        </div>
        <div class="input-contenedor">
            <div class="input-g">
                <input name="sintomas" type="text"
                    placeholder="¿Tienes algún síntoma o problema específico que te gustaría discutir?" required />
            </div>
        </div>
        <div class="input-contenedor">
            <div class="input-g">
                <select name="cita" required>
                    <option value="">¿Tu consulta la quieres para las próximas 24 horas?
                    </option>
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                </select>
            </div>
        </div>
        <div class="input-g text-center" required>
            <div class="respuestaformulario">
            </div>
            <button id="formulariosubmit">¡Sí, Tomar Contacto y Obtener
                Información!</button>
        </div>
        <div class="input-g input-g2 text-center">
            <a data-type="telefono"><i class="fa-solid fa-phone"></i> Llamar por Teléfono</a>
            <a data-type="whatsapp"><i class="fa-brands fa-whatsapp"></i> Enviar WhatsApp</a>
            <p><i class="fa-solid fa-circle-info"></i> Tu información está protegida <br> De acuerdo con la protección de
                datos vigente</p>
        </div>
    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const nombreDoctor = getFieldValue("nombredeldoctor");
    const especialidadDoctor = getFieldValue("especialidadoctor");
    const numeroDoctor = getFieldValue("numerodeldoctor");

    setSpanText(".nombredeldoctor", nombreDoctor);
    setSpanText(".especialidadoctor", especialidadDoctor);

    document.getElementById("formulariosubmit").addEventListener("click", function(e) {
        e.preventDefault(); // Evitar el comportamiento predeterminado

        const especialidadPaciente = getFieldValue("especialidadoctor");
        const nombrePaciente = getFieldValue("paciente");
        const telefonoPaciente = getFieldValue("telefonopaciente");
        const sintomas = getFieldValue("sintomas");
        const cita = document.querySelector('select[name="cita"]').value;
        const mensaje = (cita === "No") ? "No busco una cita para las próximas 24 hrs" :
            "Busco una cita para las próximas 24 hrs";

        const respuestaformulario = document.querySelector(".respuestaformulario");
        respuestaformulario.classList.remove("fail");
        respuestaformulario.classList.remove("send");

        const whatsappLink =
            `https://wa.me/${numeroDoctor}?text=${especialidadPaciente}%0AInforme%20de%20cita:%0A%0AHola,%20soy%20${nombrePaciente}%0AMi%20número%20de%20teléfono%20es%20${telefonoPaciente}%0AEl%20motivo%20de%20mi%20consulta%20es:%20${sintomas}%0A${mensaje}`;

        // Validación de campos vacíos
        if (nombrePaciente === "" || telefonoPaciente === "" || sintomas === "" || cita === "") {
            respuestaformulario.classList.add("fail");
            respuestaformulario.innerHTML = "Faltan algunos datos";
            return false;
        }

        // Si los datos son válidos
        respuestaformulario.classList.remove("fail");
        respuestaformulario.classList.add("send");
        respuestaformulario.innerHTML = "Se ha enviado su consulta";

        // Abrir el enlace de WhatsApp en una nueva ventana
        window.open(whatsappLink);

        // Redirigir a la página de agradecimiento después de un pequeño delay
        setTimeout(function() {
            window.location.href = 'gracias-formulario.php';
        }, 500); // Tiempo de espera de 500ms antes de redirigir
    });

    function getFieldValue(fieldName) {
        return document.querySelector(`input[name='${fieldName}']`).value;
    }

    function setSpanText(className, text) {
        const spanElement = document.querySelector(className);
        spanElement.textContent = text;
    }

});
</script>
