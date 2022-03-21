<?php

function actualizarVcard()
{
    global $wpdb;
    $id_tarje = $_POST['identificador'];
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
        if (!empty($_FILES["foto"]["tmp_name"])) {

            $tamano = $_FILES['foto']['size'];
            $tipo = $_FILES['foto']['type'];
            $nombre_img = $_FILES['foto']['name'];
            $photo = $_FILES["foto"]["tmp_name"];
            $imgSubida = $path_directory."/$carpeta_user/$id_tarje-$nombre_img";
            $imgCarpeta =  "wp-photos/$carpeta_user/$id_tarje-$nombre_img";
            move_uploaded_file($photo,$imgSubida);
            echo $imgSubida;
            // $binarioImagen = fread($imgSubida, $tamano);
            $ar['photo'] = $imgCarpeta;
            //array($ar,$ar2['photo']);
        }

        $wpdb->update(
            $wpdb->prefix . 'vcards',
            $ar,
            array('id_vcard' => $id_tarje)
        );
    }
    wp_redirect(get_home_url() . '/mi-cuenta/card-edit/?id=' . $id_tarje);
}

// Con esto permitimos que esta vista sea visible para usuarios sin cuentas
// add_action('admin_post_nopriv_contactform', 'actualizarVcard');
// Con esto permitimos que esta vista sea visible para usuarios logueados
add_action('admin_post_updateVcard', 'actualizarVcard');
