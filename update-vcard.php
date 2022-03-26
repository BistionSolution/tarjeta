<?php

function actualizarVcard()
{
    global $wpdb;
    $href = dirname(dirname($_SERVER["HTTP_REFERER"]));
    $id_tarje = $_POST['identificador'];
    $token = $wpdb->get_var("SELECT token FROM {$wpdb->prefix}vcards where id_vcard='$id_tarje'");
    $file_photo = $wpdb->get_var("SELECT photo FROM {$wpdb->prefix}vcards where id_vcard='$id_tarje'");
    // $url_photo = get_home_url().'/'.$photo;
    
    $names = sanitize_text_field($_POST['nombres']);
    $last_names = sanitize_text_field($_POST['apellidos']);
    $pseudonym = sanitize_text_field($_POST['seudonimo']);
    $birthday = sanitize_text_field($_POST['cumpleanios']);
    $personal_web = sanitize_text_field($_POST['paginaWebPersonal']);
    $personal_email = sanitize_text_field($_POST['emailPrincipal']);
    $personal_cell_phone = sanitize_text_field($_POST['celular']);
    $personal_telephone = sanitize_text_field($_POST['telefonoFijo']);
    $personal_address = sanitize_text_field($_POST['direccion']);
    $personal_department = sanitize_text_field($_POST['departamento']);
    $personal_country = sanitize_text_field($_POST['pais']);
    // $personal_information = sanitize_text_field($_POST['mi_informacion']);
    $company_name = sanitize_text_field($_POST['empresa']);
    $company_charge = sanitize_text_field($_POST['cargo']);
    $company_web = sanitize_text_field($_POST['paginaWeb']);
    $company_mail = sanitize_text_field($_POST['emailCorporativo']);
    $company_cell_phone = sanitize_text_field($_POST['telefonoTrabajo']);
    $company_address = sanitize_text_field($_POST['direccionTrabajo']);
    $company_department = sanitize_text_field($_POST['departamentoTrabajo']);
    $company_country = sanitize_text_field($_POST['paisTrabajo']);
    $url_facebook = sanitize_text_field($_POST['facebook']);
    $url_instagram = sanitize_text_field($_POST['instagram']);
    $url_linkedin = sanitize_text_field($_POST['linkedin']);
    $url_twitter = sanitize_text_field($_POST['twitter']);
    $url_tiktok = sanitize_text_field($_POST['tiktok']);

    $carpeta_user = get_current_user_id();
    $path_directory = realpath(dirname(__FILE__) . '/../../..') . '/wp-photos';
    $path_directory2 = $path_directory."/".$carpeta_user;
    if (!file_exists($path_directory)) {
        mkdir($path_directory, 0777);
    } 
    if (!file_exists($path_directory2)){
         mkdir($path_directory2, 0777);
    }
    // $tipo = $_FILES['foto']['type'];
    // $nombre_img = $_FILES['foto']['name'];
    // $tamano = $_FILES['foto']['size'];
    // $imgSubida = fopen($_FILES["foto"]["tmp_name"],'r');
    // $binarioImagen = fread($imgSubida,$tamano);



    // $permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png");
    // $limite_kb = 16384;

    // if (in_array($_FILES['foto']['type'], $permitidos) && $_FILES['foto']['size'] <= $limite_kb * 1024) {}

    // $imgContent = "";
    //echo $_FILES["foto"]["tmp_name"];
    if (isset($_POST)) {
        // $check = getimagesize($_FILES["foto"]["tmp_name"]);
        // //var_dump($check);
        // if ($check !== false) {
        //     $image = $_FILES['foto']['tmp_name'];
        //     $imgContent = addslashes(file_get_contents($image));
        // }
        $ar = array(
            'names' => $_POST['nombres'],
            'last_names' => $_POST['apellidos'],
            'pseudonym' => $_POST['seudonimo'],
            'birthday' => $_POST['cumpleanios'],
            'personal_web' => $_POST['paginaWebPersonal'],
            'personal_email' => $_POST['emailPrincipal'],
            'personal_cell_phone' => $_POST['celular'],
            'personal_telephone' => $_POST['telefonoFijo'],
            'personal_address' => $_POST['direccion'],
            'personal_department' => $_POST['departamento'],
            'personal_country' => $_POST['pais'],
            'personal_information' => $_POST['mi_informacion'],
            'company_name' => $_POST['empresa'],
            'company_charge' => $_POST['cargo'],
            'company_web' => $_POST['paginaWeb'],
            'company_mail' => $_POST['emailCorporativo'],
            'company_cell_phone' => $_POST['telefonoTrabajo'],
            'company_address' => $_POST['direccionTrabajo'],
            'company_department' => $_POST['departamentoTrabajo'],
            'company_country' => $_POST['paisTrabajo'],
            'url_facebook' => $_POST['facebook'],
            'url_instagram' => $_POST['instagram'],
            'url_linkedin' => $_POST['linkedin'],
            'url_twitter' => $_POST['twitter'],
            'url_tiktok' => $_POST['tiktok']
        );
        $content = "BEGIN:VCARD\r\n";
        $content .= "VERSION:3.0\r\n";
        $content .= "CLASS:PUBLIC\r\n";
        // Se ha insertado un archivo
        if (!empty($_FILES["foto"]["tmp_name"])) 
        {
            // Si ya hay foto previa almacenada en la BD y se está actualizando la foto
            if(!empty($file_photo)){
                // Borramos la foto anterior
                $path_directory_photo = realpath(dirname(__FILE__) . '/../../..');
                unlink($path_directory_photo . '/' . $file_photo);
            }
            $nombre_img = $_FILES['foto']['name'];
            $photo = $_FILES["foto"]["tmp_name"];
            $imgSubida = $path_directory."/$carpeta_user/$id_tarje-$nombre_img";
            $imgCarpeta =  "wp-photos/$carpeta_user/$id_tarje-$nombre_img";
            move_uploaded_file($photo,$imgSubida);
            // $binarioImagen = fread($imgSubida, $tamano);
            $ar['photo'] = $imgCarpeta;
            $url_photo = get_home_url() . '/' . $imgCarpeta;
            
            $contenidoBinario = file_get_contents($url_photo);
            $imagenComoBase64 = base64_encode($contenidoBinario);
            $content .= "PHOTO;ENCODING=b;TYPE:$imagenComoBase64\r\n";            
        // No se ha insertado un archivo
        }else{
            // El campo de url de la foto en la BD no está vacía
            if(!empty($file_photo)){
                $path = get_home_url() . '/' . $file_photo;
                $contentBinary = file_get_contents($path);
                $imageBase64 = base64_encode($contentBinary);
                $content .= "PHOTO;ENCODING=b;TYPE:$imageBase64\r\n";
            }
        }
        
        $content .= "N:$last_names;$names;;;\r\n";
        $content .= "FN:$names $last_names\r\n";
        $content .= "TITLE;CHARSET=utf-8:$company_charge\r\n";
        $content .= "ORG;CHARSET=utf-8:$company_name\r\n";
        $content .= "BDAY;value=date:$birthday\r\n";
        $content .= "ADR;TYPE=WORK;CHARSET=utf-8:;$company_address;;$company_department;$company_country;\r\n";
        $content .= "ADR;TYPE=HOME;CHARSET=utf-8:;$personal_address;;$personal_department;$personal_country;\r\n";
        $content .= "EMAIL;TYPE=INTERNET,PREF:$personal_email\r\n";
        $content .= "EMAIL;TYPE=INTERNET,WORK:$company_mail\r\n";
        $content .= "TEL;PREF;CELL:$company_cell_phone\r\n";
        $content .= "TEL;TYPE=HOME:$personal_telephone\r\n";
        $content .= "TEL;TYPE=WORK:$personal_cell_phone\r\n";
        $content .= "URL:$personal_web\r\n";
        $content .= "URL;WORK:$company_web\r\n";
        $content .= "URL;TYPE=Facebook;CHARSET=UTF-8:$url_facebook\r\n";
        $content .= "URL;TYPE=Instagram;CHARSET=UTF-8:$url_instagram\r\n";
        $content .= "URL;TYPE=LinkedIn;CHARSET=UTF-8:$url_linkedin\r\n";
        $content .= "URL;TYPE=Twitter;CHARSET=UTF-8:$url_twitter\r\n";
        $content .= "URL;TYPE=Tiktok;CHARSET=UTF-8:$url_tiktok\r\n";
        $content .= "END:VCARD\r\n";

        $path_directory = realpath(dirname(__FILE__) . '/../../..') . '/wp-vcards';
        $file = fopen($path_directory . "/$token.vcf", 'w');
        fwrite($file, $content);
        fclose($file);

        $wpdb->update(
            $wpdb->prefix . 'vcards',
            $ar,
            array('id_vcard' => $id_tarje)
        );
    }
    wp_redirect($href . '/card-edit/?id=' . $id_tarje);
}

// Con esto permitimos que esta vista sea visible para usuarios sin cuentas
// add_action('admin_post_nopriv_contactform', 'actualizarVcard');
// Con esto permitimos que esta vista sea visible para usuarios logueados
add_action('admin_post_updateVcard', 'actualizarVcard');
