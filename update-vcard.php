<?php

function actualizarVcard(){
    global $wpdb;
        $imgContent="";
        echo $_FILES["foto"]["tmp_name"];
        if(isset($_POST)){
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        var_dump($check);
        if($check !== false){
            $image = $_FILES['foto']['tmp_name'];
            $imgContent = addslashes(file_get_contents($image));
        }
        $wpdb->update( $wpdb->prefix.'vcards', 
        array( 
            'names' => $_POST['nombres'],
            'last_names' => $_POST['apellidos'],
            'pseudonym' => $_POST['seudonimo'],
            'birthday' => $_POST['cumpleanios'],
            'photo' => $imgContent,
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
        ), array( 'id_vcard' => $_POST['identificador'] ));
        }
        // wp_redirect(get_home_url().'/mi-cuenta/card-edit/?id='.$_POST['identificador']);
}

// Con esto permitimos que esta vista sea visible para usuarios sin cuentas
// add_action('admin_post_nopriv_contactform', 'actualizarVcard');
// Con esto permitimos que esta vista sea visible para usuarios logueados
add_action('admin_post_updateVcard', 'actualizarVcard');