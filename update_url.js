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
    jQuery("#btn-text").text("Guardando...");
    jQuery("#spinner").show();

    // Limpia mensajes de error previos
    jQuery(".error-message").text("").hide();
    
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
        console.log("data  es  :", response);
        console.log("errors  :", response.errors);

        if (typeof response === "string") {
          try {
            response = JSON.parse(response); // Intenta parsear si es string
          } catch (e) {
            console.error("Error al parsear la respuesta JSON:", e);
            return;
          }
        }
        
        jQuery("#spinner").hide();
        jQuery("#btn-text").text("Guardar cambios");
        // if (response.success) {
        //   console.log("data:", data);

        //   alert("Actualización exitosa");
        //   // Aquí puedes redirigir o hacer lo que quieras en caso de éxito
        // }

        if (response.errors) {
          for (const [field, message] of Object.entries(response.errors)) {
            const input = jQuery(`[name="${field}"]`);
            if (input.length) {
              const errorContainer = input.siblings(".error-message");
              errorContainer.text(message).show(); // Muestra el mensaje de error
            }
          }
          // Aquí puedes manejar el error, mostrando un mensaje al usuario
          Toastify({
            text: "Corrige algunos campos antes de continuar",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            style: {
              background: "linear-gradient(to right, #ff5f6d, #ffc371)",
            },
          }).showToast();
          return;
        }

        // Configurar y mostrar el modal de éxito
        Toastify({
          text: "Guardado exitoso",
          duration: 3000, // dura 3 segundos
          close: true, // permite cerrar la notificación
          gravity: "top", // `top` o `bottom`
          position: "right", // `left`, `center` o `right`
          style: {
            background: "linear-gradient(to right, #7f10d1, #17002a)",
          },
        }).showToast();
      },
      error: function (xhr, status, error) {
        console.error("Error se:", status, error);
        jQuery("#spinner").hide();
        jQuery("#btn-text").text("Guardar cambios");
        // Aquí puedes manejar el error, mostrando un mensaje al usuario
        Toastify({
          text: "Error al procesar la solicitud",
          duration: 3000,
          close: true,
          gravity: "top",
          position: "right",
          style: {
            background: "linear-gradient(to right, #ff5f6d, #ffc371)",
          },
        }).showToast();
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
