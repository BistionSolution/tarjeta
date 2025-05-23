jQuery(document).ready(function () {
  document
    .getElementById("mi_formulario")
    .addEventListener("submit", function (e) {
      e.preventDefault(); // Prevenir el envío normal del formulario
      // capturar los datos del formulario
      var form = document.getElementById("mi_formulario");
      var formData = new FormData(form);
      formData.append("action", "create_contact"); // Añadir la acción para WordPress
      // enviar los datos al servidor con ajax

      jQuery.ajax({
        type: "post",
        url: ajax_object.url,
        data: formData,
        processData: false, // Informar a jQuery que no procese los datos
        contentType: false, // Informar a jQuery que no establezca el tipo de contenido
        success: function (data) {
          console.log({ data });
          jQuery("#modalContact").modal("hide");
          // Vaciar campos
          document.getElementById("mi_formulario").reset();
          // Mostrar mensaje de éxito
          // jQuery("#modalSuccess").modal("show");
          alert("Contacto creado exitosamente.");
        },
        error: function (error) {
          console.log({ error });
          // Mostrar mensaje de error
          // jQuery("#modalError").modal("show");
          alert("Error al crear el contacto.");
        },
      });
    });
});
