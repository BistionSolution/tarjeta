<?php

function actualizarVcard()
{
    global $wpdb;

    // Iniciar el array de respuesta
    $response = array('success' => false, 'message' => '');

    $href = dirname(dirname($_SERVER["HTTP_REFERER"]));
    $id_tarje = $_POST['identificador'];
    $token = $wpdb->get_var("SELECT token FROM {$wpdb->prefix}vcards where id_vcard='$id_tarje'");
    $file_photo = $wpdb->get_var("SELECT photo FROM {$wpdb->prefix}vcards where id_vcard='$id_tarje'");
    $file_photo_business = $wpdb->get_var("SELECT photo_business FROM {$wpdb->prefix}vcards where id_vcard='$id_tarje'");
    // $url_photo = get_home_url().'/'.$photo;
    $imgvacio_bus = $_POST["imgvacio_bus"];
    $imgvacio = $_POST["imgvacio"];
    $names = sanitize_text_field($_POST['nombres']);
    $last_names = sanitize_text_field($_POST['apellidos']);
    $pseudonym = sanitize_text_field($_POST['pseudonym']);
    $birthday = sanitize_text_field($_POST['cumpleanios']);
    $personal_web = sanitize_text_field($_POST['paginaWebPersonal']);
    $personal_email = sanitize_text_field($_POST['emailPrincipal']);
    $personal_cell_phone = str_replace(" ", "", sanitize_text_field($_POST['celular']));
    $personal_telephone = str_replace(" ", "", sanitize_text_field($_POST['telefonoFijo']));
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
    $url_youtube = sanitize_text_field($_POST['youtube']);
    $url_instagram = sanitize_text_field($_POST['instagram']);
    $url_linkedin = sanitize_text_field($_POST['linkedin']);
    $url_twitter = sanitize_text_field($_POST['twitter']);
    $url_tiktok = sanitize_text_field($_POST['tiktok']);
    $url_spotify = sanitize_text_field($_POST['spotify']);
    $url_apple_music = sanitize_text_field($_POST['apple_music']);
    $calendly = sanitize_text_field($_POST['calendly']);
    $opensea = sanitize_text_field($_POST['opensea']);
    $metamask = sanitize_text_field($_POST['metamask']);

    $carpeta_user = get_current_user_id();
    $path_directory = realpath(dirname(__FILE__) . '/../../..') . '/wp-photos';
    $path_directory2 = $path_directory . "/" . $carpeta_user;
    if (!file_exists($path_directory)) {
        mkdir($path_directory, 0777);
    }
    if (!file_exists($path_directory2)) {
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

        // Validar email
        if (!is_email($personal_email)) {
            wp_die('El email personal no es válido.');
        }

        $ar = array(
            'names' => $_POST['nombres'],
            'last_names' => $_POST['apellidos'],
            'pseudonym' => $_POST['pseudonym'],
            'birthday' => $_POST['cumpleanios'],
            'personal_web' => $_POST['paginaWebPersonal'],
            'personal_email' => $_POST['emailPrincipal'],
            'personal_cell_phone' => str_replace(" ", "", $_POST['celular']),
            'personal_telephone' => str_replace(" ", "", $_POST['telefonoFijo']),
            'personal_address' => $_POST['direccion'],
            'personal_department' => $_POST['departamento'],
            'personal_country' => $_POST['pais'],
            'personal_information' => $_POST['mi_informacion'],
            'personal_presentation' => $_POST['personal_presentation'],
            'whatsapp_ms' => $_POST['whatsapp_mensaje'],
            'company_name' => $_POST['empresa'],
            'company_charge' => $_POST['cargo'],
            'company_web' => $_POST['paginaWeb'],
            'company_mail' => $_POST['emailCorporativo'],
            'company_cell_phone' => $_POST['telefonoTrabajo'],
            'company_address' => $_POST['direccionTrabajo'],
            'company_department' => $_POST['departamentoTrabajo'],
            'company_country' => $_POST['paisTrabajo'],
            'calendly' => $_POST['calendly'],
            'opensea' => $_POST['opensea'],
            'metamask' => $_POST['metamask'],
            'url_facebook' => $_POST['facebook'],
            'url_youtube' => $_POST['youtube'],
            'video_youtube' => $_POST['video_youtube'],
            'url_instagram' => $_POST['instagram'],
            'url_linkedin' => $_POST['linkedin'],
            'url_twitter' => $_POST['twitter'],
            'url_tiktok' => $_POST['tiktok'],
            'url_spotify' => $_POST['spotify'],
            'url_apple_music' => $_POST['apple_music'],
            // Personal redes sociales
            'url_behance' => $_POST['url_behance'],
            'url_github' => $_POST['url_github'],
            'url_telegram' => $_POST['url_telegram'],
            'url_wechat' => $_POST['url_wechat'],

            'background_color' => $_POST['background_color'],
            'button_text_color' => $_POST['button_text_color'],
            'button_background_color' => $_POST['button_background_color'],
            'text_title_color' => $_POST['text_title_color'],
            'text_color' => $_POST['text_color'],
            // Redes sociales de la empresa
            'facebook_business' => $_POST['facebook_business'],
            'youtube_business' => $_POST['youtube_business'],
            'instagram_business' => $_POST['instagram_business'],
            'linkedin_business' => $_POST['linkedin_business'],
            'twitter_business' => $_POST['twitter_business'],
            'tiktok_business' => $_POST['tiktok_business'],
        );
        $content = "BEGIN:VCARD\r\n";
        $content .= "VERSION:3.0\r\n";
        $content .= "CLASS:PUBLIC\r\n";


        if ($imgvacio_bus != "si") {
            // Se ha insertado un archivo - FOTO EMPRESA
            if (!empty($_FILES["foto_business"]["tmp_name"])) {
                // Si ya hay foto previa almacenada en la BD y se está actualizando la foto
                if (!empty($file_photo_business)) {
                    // Borramos la foto anterior
                    $path_directory_photo = realpath(dirname(__FILE__) . '/../../..');
                    unlink($path_directory_photo . '/' . $file_photo_business);
                }
                $nombre_img_business = $_FILES['foto_business']['name'];
                $photo_business = $_FILES["foto_business"]["tmp_name"];
                $imgSubida_business = $path_directory . "/$carpeta_user/$id_tarje-business-$nombre_img_business";
                $imgCarpetaBusiness =  "wp-photos/$carpeta_user/$id_tarje-business-$nombre_img_business";
                move_uploaded_file($photo_business, $imgSubida_business);
                // $binarioImagen = fread($imgSubida, $tamano);
                $ar['photo_business'] = $imgCarpetaBusiness;
            } else {
                $path_directory_photo = realpath(dirname(__FILE__) . '/../../..');
                unlink($path_directory_photo . '/' . $file_photo_business);
                $ar['photo_business'] = NULL;
            }
        }

        if ($imgvacio != "si") {
            // Se ha insertado un archivo FOTO PERFIL
            if (!empty($_FILES["foto"]["tmp_name"])) {
                // Si ya hay foto previa almacenada en la BD y se está actualizando la foto
                if (!empty($file_photo)) {
                    // Borramos la foto anterior
                    $path_directory_photo = realpath(dirname(__FILE__) . '/../../..');
                    unlink($path_directory_photo . '/' . $file_photo);
                }
                $nombre_img = $_FILES['foto']['name'];
                $photo = $_FILES["foto"]["tmp_name"];
                $imgSubida = $path_directory . "/$carpeta_user/$id_tarje-$nombre_img";
                $imgCarpeta =  "wp-photos/$carpeta_user/$id_tarje-$nombre_img";
                move_uploaded_file($photo, $imgSubida);
                // $binarioImagen = fread($imgSubida, $tamano);
                $ar['photo'] = $imgCarpeta;
                $url_photo = get_home_url() . '/' . $imgCarpeta;

                $contenidoBinario = file_get_contents($url_photo);
                $imagenComoBase64 = base64_encode($contenidoBinario);
                $content .= "PHOTO;ENCODING=b;TYPE:$imagenComoBase64\r\n";
                // No se ha insertado un archivo
            } else {
                // El campo de url de la foto en la BD no está vacía
                // if(!empty($file_photo)){
                //     $path = get_home_url() . '/' . $file_photo;
                //     $contentBinary = file_get_contents($path);
                //     $imageBase64 = base64_encode($contentBinary);
                //     $content .= "PHOTO;ENCODING=b;TYPE:$imageBase64\r\n";
                // }
                $path_directory_photo = realpath(dirname(__FILE__) . '/../../..');
                unlink($path_directory_photo . '/' . $file_photo);
                $ar['photo'] = NULL;
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
        $content .= "URL;TYPE=Youtube;CHARSET=UTF-8:$url_youtube\r\n";
        $content .= "URL;TYPE=Instagram;CHARSET=UTF-8:$url_instagram\r\n";
        $content .= "URL;TYPE=LinkedIn;CHARSET=UTF-8:$url_linkedin\r\n";
        $content .= "URL;TYPE=Twitter;CHARSET=UTF-8:$url_twitter\r\n";
        $content .= "URL;TYPE=Tiktok;CHARSET=UTF-8:$url_tiktok\r\n";
        $content .= "URL;TYPE=Spotify;CHARSET=UTF-8:$url_spotify\r\n";
        $content .= "URL;TYPE=Apple Music;CHARSET=UTF-8:$url_apple_music\r\n";
        $content .= "URL;TYPE=Calendly;CHARSET=UTF-8:$calendly\r\n";
        $content .= "URL;TYPE=Opensea;CHARSET=UTF-8:$opensea\r\n";
        $content .= "URL;TYPE=Metamask;CHARSET=UTF-8:$metamask\r\n";
        $content .= "END:VCARD\r\n";

        $path_directory = realpath(dirname(__FILE__) . '/../../..') . '/wp-vcards';
        $file = fopen($path_directory . "/$token.vcf", 'w');
        fwrite($file, $content);
        fclose($file);
        var_dump($ar);

        $wpdb->update(
            $wpdb->prefix . 'vcards',
            $ar,
            array('id_vcard' => $id_tarje)
        );

        if ($wpdb->last_error !== '') {
            wp_send_json_error('Error en la base de datos: ' . $wpdb->last_error);
        }
        
        wp_send_json_success('Actualización exitosa.');
    }
}

// Con esto permitimos que esta vista sea visible para usuarios sin cuentas
// add_action('admin_post_nopriv_contactform', 'actualizarVcard');
// Con esto permitimos que esta vista sea visible para usuarios logueados
// add_action('admin_post_updateVcard', 'actualizarVcard');

add_action('wp_ajax_updateVcard', 'actualizarVcard');
