<?php
// require 'error.php';

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
        ?>        
            <div class="perfil">>
            <?php if(empty($foto)): ?> 
                <div class="profile-img"> 
                    <img class="img-perfil" src="<?=plugins_url(basename(__DIR__) . '/assets/img/avatar.png')?>"/>
                </div>
            <?php else: ?>
                <div class="profile-img">
                    <img class="profile-pic" src="<?=get_home_url() . '/' . $foto?>"/>
				</div>

            <?php endif; ?>
            <div>
                    <h1><?= $nombres.' '.$apellidos?></h1>
                </div>
                <div>
                    <h2>
                        <?= $company_charge ?>
                    </h2>
                </div>
                <div>
                    <h2>
                        <?= $company_name ?>
                    </h2>
                </div>
                <div class="items">
                    <?php if(!empty($correo)): ?> 
                        <div class="img-icon">
                            <a href="mailto:<?= $correo ?>"><img width="50px" src="<?=plugins_url(basename(__DIR__) . '/assets/img/mensaje.svg')?>"/></a>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($href)): ?> 
                        <div class="img-icon">
                            <a href="<?=$href?>"><img width="50px" src="<?=plugins_url(basename(__DIR__) . '/assets/img/user.svg')?>"/></a>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($cell_phone)): ?> 
                        <div class="img-icon">
                            <a href="tel:<?=$cell_phone?>"><img width="50px" src="<?=plugins_url(basename(__DIR__) . '/assets/img/cell.svg')?>"/></a> 
                        </div>
                    <?php endif; ?>                
                </div>
                 
                <?php if(!empty($company_mail)): ?> 
                    <div>
                        <a class="perfil-button" href="<?= $company_mail ?>">
                            Correo corporativo
                        </a>
                    </div>
                <?php endif; ?>
                <?php if(!empty($instagram)): ?> 
                    <div>
                        <a class="perfil-button" href="<?= $instagram ?>">
                            <div>Instagram</div>
                        </a>    
                    </div>
                <?php endif; ?>
                <?php if(!empty($linkedin)): ?> 
                    <div>
                        <a class="perfil-button" href="<?= $linkedin ?>">
                            <div>Linkedin</div>
                        </a>    
                    </div>
                <?php endif; ?>
                <?php if(!empty($twitter)): ?> 
                    <div>
                        <a class="perfil-button" href="<?= $twitter ?>">
                            <div>Twitter</div>
                        </a>    
                    </div>
                <?php endif; ?>
                <?php if(!empty($tiktok)): ?> 
                    <div>
                        <a class="perfil-button" href="<?= $tiktok ?>">
                            <div>TikTok</div>
                        </a>    
                    </div>
                    <?php endif; ?>                
    </div>
<?php
        else:
            // $atts = [
            //     'message' => 'Link caído',
            //     'bye' => 'Consulte con su proveedor →',
            //     'href' => 'Volver a página principal'
            // ];
            // page_error($atts);
            global $wp_query;
            $wp_query->set_404();
            status_header( 404 );
            get_template_part( 404 ); exit();
        endif;
    else:
        // $atts = [
        //     'message' => 'No se encontraron resultados',
        //     'bye' => 'Vuelve a',
        //     'href' => 'casa campeón!'
        // ];
        // page_error($atts);
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 ); exit();
    endif;
}
    