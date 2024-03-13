<?php
function create_contact()
{
    echo "entro";
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
        'user_id' => $user_id
        , 'vcard_id' => $id_vcard
        , 'names' => $names
        , 'last_names' => $last_names
        , 'phone' => $phone
        , 'email' => $email
    );
    // crear en la tabla de contactos
    $table_name = $wpdb->prefix . "vcards_contacts";
    $wpdb->insert($table_name, $vcard_info);
}

add_action('wp_ajax_create_contact', 'create_contact');
