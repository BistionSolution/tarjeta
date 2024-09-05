jQuery(document).ready(function ($) {
  // Escucha el evento submit en el formulario
  jQuery("#editurl").submit(function (e) {
    e.preventDefault(); // Previene el envío normal del formulario

    // Recoge los datos del formulario
    var formData = new FormData(this);
    formData.append("action", "update_url"); // Añade la acción para WordPress

    // Envía los datos al servidor con AJAX
    jQuery.ajax({
      type: "POST",
      url: ajax_object.url, // Debes asegurarte de que ajax_object.url está definido en alguna parte
      data: formData,
      processData: false, // Informa a jQuery que no procese los datos
      contentType: false, // Informa a jQuery que no establezca el tipo de contenido
      success: function (data) {
        console.log("Success:", data);
        console.log("data:", data.data);
        $("#modalUpdateUrl").modal("hide");
        // Actualizar texto de mi span
        jQuery("#my_url").text(data.data);
      },
      error: function (xhr, status, error) {
        console.error("Error se:", status, error);
        // Aquí puedes manejar el error, mostrando un mensaje al usuario
        if (xhr.status === 409) {
          alert("Error: El usuario ya existe.");
        } else {
          alert("Error al procesar la solicitud.");
        }
      },
    });
  });

  jQuery("#perfil-qr-form").submit(function (e) {
    e.preventDefault(); // Previene el envío normal del formulario

    // Recoge los datos del formulario
    var formData = new FormData(this);
    formData.append("action", "updateVcard"); // Añade la acción para WordPress

    // Envía los datos al servidor con AJAX
    jQuery.ajax({
      type: "POST",
      url: ajax_object.url, // Debes asegurarte de que ajax_object.url está definido en alguna parte
      data: formData,
      processData: false, // Informa a jQuery que no procese los datos
      contentType: false, // Informa a jQuery que no establezca el tipo de contenido
      success: function (response) {
        console.log("Success:", response);
        var data = JSON.parse(response);

        if (data.success) {
          console.log("data:", data.data);

          alert("Actualización exitosa");
          // Aquí puedes redirigir o hacer lo que quieras en caso de éxito
        } else {
          alert("Error: " + data.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error se:", status, error);
        // Aquí puedes manejar el error, mostrando un mensaje al usuario
        if (xhr.status === 409) {
          alert("Error: El usuario ya existe.");
        } else {
          alert("Error al procesar la solicitud. now");
        }
      },
    });
  });

  // Escuchar click boton
  jQuery("#button-edit").click(function () {
    console.log("click");
    // Mostrar modal
    jQuery("#modalUpdateUrl").modal("show");
  });
});
