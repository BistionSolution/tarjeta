<?php
function create_contact()
{
    global $wpdb;
    ob_clean();
    $id_vcard = isset($_POST['id_vcard']) ? sanitize_text_field($_POST['id_vcard']) : '';
    $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';
    $names = isset($_POST['names']) ? sanitize_text_field($_POST['names']) : '';
    $last_names = isset($_POST['last_names']) ? sanitize_text_field($_POST['last_names']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';

    if (isset($_REQUEST)) {
    }
    $vcard_info = array(
        'user_id' => $user_id, 'vcard_id' => $id_vcard, 'names' => $names, 'last_names' => $last_names, 'phone' => $phone, 'email' => $email
    );
    // crear en la tabla de contactos
    $table_name = $wpdb->prefix . "vcards_contacts";
    $wpdb->insert($table_name, $vcard_info);
}

add_action('wp_ajax_create_contact', 'create_contact');

// Actualizar la url de la tarjeta
function update_url()
{
    echo 'hola -.>>>>>>>>>>>>>>>>>>>>>>>>>>>>>';
    global $wpdb;
    ob_clean();
    // Asegurar que la petición es de tipo POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_send_json_error('Invalid request method.', 405); // Método no permitido
        exit;
    }

    // Obtener el valor de profile_url de POST, sanitizarlo y verificar si está vacío
    $profile_url = isset($_POST['profile_url']) ? sanitize_text_field($_POST['profile_url']) : '';
    $id_tarje = isset($_POST['id_tarje']) ? sanitize_text_field($_POST['id_tarje']) : '';

    if (empty($profile_url)) {
        wp_send_json_error('Profile URL is required.', 400); // Error de petición
        exit;
    }

    // Preparar el nombre de la tabla y verificar si el usuario ya existe
    $table_name = $wpdb->prefix . "vcards";
    $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE profile_url = %s", $profile_url));

    // Si el usuario ya existe, enviar un mensaje de error
    if ($exists > 0) {
        wp_send_json_error('User already exists.', 409); // Conflicto
    } else {
        // Si el usuario no existe, insertar el nuevo registro
        $vcard = array(
            'profile_url' => $profile_url
        );
        $inserted = $wpdb->update(
            $wpdb->prefix . 'vcards',
            $vcard,
            array('id_vcard' => $id_tarje)
        );

        // Verificar si la inserción fue exitosa y enviar una respuesta apropiada
        if ($inserted) {
            wp_send_json_success($profile_url);
        } else {
            wp_send_json_error('An error occurred while updating the profile URL.', 500); // Error interno del servidor
        }
    }

    // No olvides siempre terminar con exit en las peticiones AJAX para evitar enviar contenido extra.
    exit;
}

add_action('wp_ajax_update_url', 'update_url');
