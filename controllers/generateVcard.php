<?php
/**
 * Genera y envía la vCard de forma dinámica.
 *
 * Este código consulta la información de la vCard desde la base de datos
 * y construye el contenido del archivo .vcf que se envía directamente al navegador.
 */
function generarVcardOnClick() {
    global $wpdb;

    // Recibir y sanitizar el identificador (por ejemplo, vía POST)
    $id_tarje = isset($_REQUEST['identificador']) ? intval($_REQUEST['identificador']) : 0;
    if ( !$id_tarje ) {
        wp_send_json_error(array('message' => 'Identificador no válido.'));
        exit();
    }

    // Consultar la información de la vCard en la base de datos
    $row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}vcards WHERE id_vcard = %d", $id_tarje) );
    if ( !$row ) {
        wp_send_json_error(array('message' => 'vCard no encontrada.'));
        exit();
    }

    // Construir la vCard (versión 3.0)
    $vcard  = "BEGIN:VCARD\r\n";
    $vcard .= "VERSION:3.0\r\n";
    $vcard .= "CLASS:PUBLIC\r\n";

    // Si se dispone de una foto, se incluye en formato Base64
    if (!empty($row->photo)) {
        $photo_path = ABSPATH . $row->photo; // Se asume que 'photo' es una ruta relativa
        if ( file_exists($photo_path) ) {
            $contenidoFoto = file_get_contents($photo_path);
            $fotoBase64 = base64_encode($contenidoFoto);
            // Se asume que la imagen es JPEG; de ser otro formato, ajustar el TYPE
            $vcard .= "PHOTO;ENCODING=b;TYPE=JPEG:$fotoBase64\r\n";
        }
    }

    // Campos básicos: N (Apellido;Nombre), FN (nombre completo)
    $names      = !empty($row->names) ? $row->names : '';
    $last_names = !empty($row->last_names) ? $row->last_names : '';
    $vcard .= "N:$last_names;$names;;;\r\n";
    $vcard .= "FN:$names $last_names\r\n";

    // Otros datos (ajusta o agrega según los campos que tengas en la BD)
    $company_charge = !empty($row->company_charge) ? $row->company_charge : '';
    $company_name   = !empty($row->company_name) ? $row->company_name : '';
    $birthday       = !empty($row->birthday) ? $row->birthday : '';
    $vcard .= "TITLE;CHARSET=utf-8:$company_charge\r\n";
    $vcard .= "ORG;CHARSET=utf-8:$company_name\r\n";
    if ($birthday) {
        $vcard .= "BDAY;value=date:$birthday\r\n";
    }

    // Dirección de trabajo y personal
    $company_address    = !empty($row->company_address) ? $row->company_address : '';
    $company_department = !empty($row->company_department) ? $row->company_department : '';
    $company_country    = !empty($row->company_country) ? $row->company_country : '';
    $personal_address    = !empty($row->personal_address) ? $row->personal_address : '';
    $personal_department = !empty($row->personal_department) ? $row->personal_department : '';
    $personal_country    = !empty($row->personal_country) ? $row->personal_country : '';

    $vcard .= "ADR;TYPE=WORK;CHARSET=utf-8:;;$company_address;;$company_department;$company_country\r\n";
    $vcard .= "ADR;TYPE=HOME;CHARSET=utf-8:;;$personal_address;;$personal_department;$personal_country\r\n";

    // Emails
    $personal_email = !empty($row->personal_email) ? $row->personal_email : '';
    $company_mail   = !empty($row->company_mail) ? $row->company_mail : '';
    $vcard .= "EMAIL;TYPE=INTERNET,PREF:$personal_email\r\n";
    $vcard .= "EMAIL;TYPE=INTERNET,WORK:$company_mail\r\n";

    // Teléfonos
    $personal_cell_phone  = !empty($row->personal_cell_phone) ? $row->personal_cell_phone : '';
    $personal_telephone   = !empty($row->personal_telephone) ? $row->personal_telephone : '';
    $company_cell_phone   = !empty($row->company_cell_phone) ? $row->company_cell_phone : '';
    $vcard .= "TEL;PREF;CELL:$company_cell_phone\r\n";
    $vcard .= "TEL;TYPE=HOME:$personal_telephone\r\n";
    $vcard .= "TEL;TYPE=WORK:$personal_cell_phone\r\n";

    // URL personales y de empresa
    $personal_web = !empty($row->personal_web) ? $row->personal_web : '';
    $company_web  = !empty($row->company_web) ? $row->company_web : '';
    $vcard .= "URL:$personal_web\r\n";
    $vcard .= "URL;WORK:$company_web\r\n";

    // Redes sociales (agrega las que necesites)
    $sociales = array(
        'url_facebook'    => 'Facebook',
        'url_youtube'     => 'Youtube',
        'url_instagram'   => 'Instagram',
        'url_linkedin'    => 'LinkedIn',
        'url_twitter'     => 'Twitter',
        'url_tiktok'      => 'Tiktok',
        'url_spotify'     => 'Spotify',
        'url_apple_music' => 'Apple Music'
    );
    foreach ($sociales as $campo => $etiqueta) {
        if ( !empty($row->$campo) ) {
            $vcard .= "URL;TYPE=$etiqueta;CHARSET=UTF-8:" . $row->$campo . "\r\n";
        }
    }

    $vcard .= "END:VCARD\r\n";

    // Enviar las cabeceras para la descarga de la vCard
    header("Content-Type: text/x-vcard");
    header("Content-Disposition: attachment; filename=\"vcard_{$id_tarje}.vcf\"");
    echo $vcard;
    exit();
}

// Registro de la acción para usuarios conectados y no conectados
add_action('wp_ajax_generarVcard', 'generarVcardOnClick');
add_action('wp_ajax_nopriv_generarVcard', 'generarVcardOnClick');
