<?php
function page_vcard(){
    ?><div class="wrap"><?php
    if(isset($_GET['token'])):
        if (!empty($_GET['token'])): 
            global $wpdb;
            $token = sanitize_text_field($_GET['token']);
            $table_name = $wpdb->prefix . "vcards";
            $sql = "SELECT * FROM $table_name WHERE token='$token'";
            $wpdb->query($sql);
            // Si es una token válido
            if (!empty($wpdb->last_result)):
                
            $nombre = $wpdb->last_result[0]->company;
            $telefono = $wpdb->last_result[0]->title;
            // $direccion = $wpdb->last_result[0]->address;
            $correo = $wpdb->last_result[0]->email;
            $token = $wpdb->last_result[0]->token;
            $href = "/wp-nfc/wp-vcards/$token.vcf";
            endif;
        endif; 
    endif;?>
    <div>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas earum, quam unde tempore repellat facilis labore illo excepturi sapiente assumenda nostrum distinctio? Ducimus, quod amet ratione cumque voluptatum nihil magni.</p>
    </div>
    <div class="text-center">
        <h1><?= $nombre ?></h1>
        <h4><?= $telefono ?></h4>
        <h4><?= $correo ?></h4>
        <a href="<?=$href?>"><img src="http://localhost/wordpress/images/user.png" alt="" width="40"></a>
    </div>
</div><?php
}
?>

    