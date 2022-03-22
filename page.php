<?php
require 'error.php';

function page_vcard()
{
    ?><div class="wrap"><?php
    if(isset($_GET['token']) && !empty($_GET['token'])):
        global $wpdb;
        $token = sanitize_text_field($_GET['token']);
        $table_name = $wpdb->prefix . "vcards";
        $sql = "SELECT * FROM $table_name WHERE token='$token'";
        $wpdb->query($sql);
        $result = $wpdb->last_result;
        // Si es una token válido
        if (!empty($result)):
            $foto =$result[0]->photo;
            $nombres = $result[0]->names;
            $apellidos = $result[0]->last_names;
            $cell_phone = $result[0]->company_cell_phone;
            $correo = $result[0]->personal_email;
            $company_name = $result[0]->company_name;
            $company_charge = $result[0]->company_charge;
            $company_mail = $result[0]->company_mail;
            $instagram = $result[0]->url_instagram;
            $linkedin = $result[0]->url_linkedin;
            $twitter = $result[0]->url_twitter;
            $tiktok = $result[0]->url_tiktok;
            $token = $result[0]->token;
            $href = home_url() . "/wp-vcards/$token.vcf";
            $short_code_url = home_url() . "/wp-vcards/?token=$token";
        ?>        
            <div class="text-center">
            <?php if(empty($foto)): ?>    
                <img width="70px" src="<?=plugins_url(basename(__DIR__) . '/assets/img/' . $foto)?>"/>
            <?php else: ?>
                <img width="70px" src="<?=get_home_url() . $foto?>"/>
            <?php endif; ?>
                <div><?= $nombres ?></div>
                <div><?= $apellidos ?></div>
                <div><?= $correo ?></div>
                <div><?= $company_name ?></div>
                <div><?= $company_charge ?></div>
                <div><?= $company_mail ?></div>
                <div><?= $instagram ?></div>
                <div><?= $linkedin ?></div>
                <div><?= $twitter ?></div>
                <div><?= $tiktok ?></div>
                <a href="<?=$href?>"><img width="50px" src="<?=plugins_url(basename(__DIR__) . '/assets/img/user.png')?>"/></a>
                <a href="tel:<?=$cell_phone?>"><img width="50px" src="<?=plugins_url(basename(__DIR__) . '/assets/img/phone.png')?>"/></a>
            </div>
            <div>
                <?=do_shortcode("[kaya_qrcode content=$short_code_url]");?>
            </div>
    </div>
<?php
        else:
            $atts = [
                'message' => 'Link caído',
                'bye' => 'Consulte con su proveedor →',
                'href' => 'Volver a página principal'
            ];
            page_error($atts);
        endif;
    else:
        $atts = [
            'message' => 'No se encontraron resultados',
            'bye' => 'Vuelve a',
            'href' => 'casa campeón!'
        ];
        page_error($atts);
    endif;
}
    