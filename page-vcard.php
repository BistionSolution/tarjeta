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
            $foto_business =$result[0]->photo_business;
            $nombres = $result[0]->names;
            $apellidos = $result[0]->last_names;
            $personal_phone =$result[0]->personal_cell_phone;
            $cell_phone = $result[0]->company_cell_phone;
            $correo = $result[0]->personal_email;
            $company_name = $result[0]->company_name;
            $company_charge = $result[0]->company_charge;
            $company_mail = $result[0]->company_mail;
            $facebook = $result[0]->url_facebook;
            $youtube = $result[0]->url_youtube;
            $instagram = $result[0]->url_instagram;
            $linkedin = $result[0]->url_linkedin;
            $twitter = $result[0]->url_twitter;
            $calendly = $result[0]->calendly;
            $opensea = $result[0]->opensea;
            $metamask = $result[0]->metamask;
            $tiktok = $result[0]->url_tiktok;
            $token = $result[0]->token;
            $per_infor = $result[0]->personal_information ;
            $href = home_url() . "/wp-vcards/$token.vcf";
        ?>        
        <div class="perfil">
            <?php if(empty($foto)): ?> 
                <div class="profile-img"> 
                    <img class="img-perfil" src="<?=plugins_url(basename(__DIR__) . '/assets/img/ZENTOC-perfil.png')?>"/>
                </div>
            <?php else: ?>
                <div class="profile-img">
                    <img class="profile-pic" src="<?=get_home_url() . '/' . $foto?>"/>
				</div>

            <?php endif; ?>

            <?php if(empty($foto_business)): ?> 
                <div class="img-business"> 
                    <img class="img-perfil" src="<?=plugins_url(basename(__DIR__) . '/assets/img/ZENTOC-perfil.png')?>"/>
                </div>
            <?php else: ?>
                <div class="for-border">

                
                    <div class="img-business">
                        <img class="profile-pic" src="<?=get_home_url() . '/' . $foto_business?>"/>
                    </div>
                </div>

            <?php endif; ?>

            <div class="contenido">
                <?php if(empty($nombres) && empty($apellidos)): ?> 
                    <div class="names">
                        <h1>Nombres y apellidos</h1>
                    </div>
                <?php else: ?>
                    <div class="names">
                        <h1><?= $nombres.' '.$apellidos?></h1>
                    </div>
                <?php endif; ?>
            
                <?php if(!empty($company_charge)): ?> 
                    <h2>
                        <?= $company_charge ?> de 
                        <?= $company_name ?>
                    </h2>
                <?php else: ?>
                    <h2>
                        <?= $company_name ?>
                    </h2>
                <?php endif; ?>                
                <div>
                    <?php if(!empty($correo)): ?> 
                        <div class="img-icon mail">
                            <a href="mailto:<?= $correo ?>"><i class="fa fa-envelope"></i> <?= $correo ?></a>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($company_mail)): ?> 
                        <div class="img-icon mail">
                            <a  href="mailto:<?= $company_mail?>"><i class="fa fa-envelope"></i> <?= $company_mail ?>
                            </a>    
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($personal_phone)): ?> 
                        <div class="img-icon cell">
                            <a href="tel:<?=$personal_phone?>"><i class="fa fa-phone"></i><?=$personal_phone?></a> 
                        </div>
                    <?php endif; ?>   
                    <?php if(!empty($cell_phone)): ?> 
                        <div class="img-icon cell">
                            <a href="tel:<?=$cell_phone?>"><i class="fa fa-phone"></i><?=$cell_phone?></a> 
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if(!empty($correo)): ?> 
                        <div class="img-icon mail">
                            <a href="mailto:<?= $correo ?>"><i class="fa fa-envelope"></i> <?= $correo ?></a>
                        </div>
                    <?php endif; ?>
                    

                    <?php if(!empty($personal_phone)): ?> 
                        <div class="img-icon cell">
                            <a href="tel:<?=$personal_phone?>"><i class="fa fa-phone"></i><?=$personal_phone?></a> 
                        </div>
                    <?php endif; ?> 
                    
                    
                </div>
                <div class="items">                    
                    <?php if(!empty($href)): ?> 
                        <div class="img-icon vcard">
                            <a href="<?=$href?>"><i class="fa fa-user-plus"> Contacto</i> </a>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($personal_phone)): ?> 
                            <div class="img-icon cell">
                                <a href="tel:<?=$personal_phone?>"><i class="fa fa-phone"></i></a> 
                            </div>
                    <?php endif; ?>  
                    <?php if(!empty($calendly)): ?> 
                        <div class="img-icon cell">
                            <a class="perfil-button" href="<?= $calendly ?>">
                            <i class="fa fa-calendar"></i>                              
                            <!-- <i class="fab fa-tiktok"></i> -->
                            </a>    
                        </div>
                    <?php endif; ?>                                 
                </div>
                
                <div>
                    <h2>Redes Sociales</h2>
                </div>

                <div class="redes-sociales">
                    <?php if(!empty($facebook)): ?> 
                        <div>
                            <a class="perfil-button" href="<?= $facebook ?>">
                                
                                <i class="fab fa-facebook"></i>
                            </a>   
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($youtube)): ?> 
                        <div>
                            <a class="perfil-button" href="<?= $youtube ?>">
                                
                                <i class="fab fa-youtube"></i>
                            </a>    
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($instagram)): ?> 
                        <div>
                            <a class="perfil-button" href="<?= $instagram ?>">
                                
                                <i class="fab fa-instagram"></i>
                            </a>    
                            
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($linkedin)): ?> 
                        <div>
                            <a class="perfil-button" href="<?= $linkedin ?>">
                                
                                <i class="fab fa-linkedin"></i>
                            </a>    
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($twitter)): ?> 
                        <div>
                            <a class="perfil-button" href="<?= $twitter ?>">
                                
                                <i class="fab fa-twitter"></i>
                            </a>    
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($tiktok)): ?> 
                        <div>
                            <a class="perfil-button" href="<?= $tiktok ?>">
                                
                                <i class="fab fa-tiktok"></i>
                            </a>    
                        </div>
                    <?php endif; ?>            
                </div>
                <?php if(!empty($per_infor)): ?>    
                <div class="sobre-mi">
                    <h2>Sobre mí</h2>                  
                        <div>
                            <?= $per_infor ?>
                        </div>                    
                </div>
                <?php endif; ?>

                <?php if(!empty($opensea) && (!empty($metamask))): ?> 
                    <div class="web3">
                        <h2>Web 3</h2>
                    
                        <?php if(!empty($opensea)): ?> 
                            <div class="web3-img">
                                <img class="img-web" src="<?=plugins_url(basename(__DIR__) . '/assets/img/opensea.svg')?>"/>
                                <p>Opensea: <a class="perfil-button" href="<?= $opensea ?>"><?= $opensea ?>
                                    
                                    <!-- <i class="fab fa-tiktok"></i> -->
                                    </a>  </p>
                                
                            </div>
                        <?php endif; ?> 
                        <?php if(!empty($metamask)): ?> 
                            <div class="web3-img">
                                <img class="img-web" src="<?=plugins_url(basename(__DIR__) . '/assets/img/MetaMask_Fox.svg')?>"/>
                                <p class="metamask" >Metamask: <?= $metamask ?>
                                    
                                <!-- <i class="fab fa-tiktok"></i> -->
                                </p>    
                            </div>
                        <?php endif; ?> 
                    </div>
                <?php endif; ?> 
            </div>
        </div>
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
    