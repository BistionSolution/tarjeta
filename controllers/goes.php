<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php
/*
Template Name: Lista de Productos
*/
// get_header(); // Incluir el encabezado del tema
// Obtener el valor de 'mi_variable' pasado desde la URL
// Incluir los estilos y scripts necesarios
$mi_variable = isset($parameters['username']) ? $parameters['username'] : ($parameters['token'] ?? ''); // Obtener el valor de 'username' desde los parámetros, o usar 'token' si 'username' no está presente
$column_name = isset($parameters['username']) ? 'profile_url' : 'token'; // Determinar el nombre de la columna según el valor de 'username'
?>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', false, 'right'); ?><?php echo $mi_variable ?></title> <!-- Aquí defines tu título personalizado -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <?php wp_head(); ?>
    <?php
    if ($mi_variable) :
        global $wpdb;
        $table_name = $wpdb->prefix . "vcards";

        $sql = "SELECT * FROM $table_name WHERE $column_name = '$mi_variable'";
        $wpdb->query($sql);
        $result = $wpdb->last_result;
        // Si es una token válido
        if (!empty($result)) :
            $id_vcard = $result[0]->id_vcard;
            $foto = $result[0]->photo;
            $foto_business = $result[0]->photo_business;
            $nombres = $result[0]->names;
            $apellidos = $result[0]->last_names;
            $profile_url = $result[0]->profile_url;
            $personal_web = $result[0]->personal_web;
            $personal_phone = $result[0]->personal_cell_phone;
            $whatsapp_message = $result[0]->whatsapp_ms;
            $cell_phone = $result[0]->company_cell_phone;
            $correo = $result[0]->personal_email;
            $company_name = $result[0]->company_name;
            $company_web = $result[0]->company_web;
            $company_charge = $result[0]->company_charge;
            $company_mail = $result[0]->company_mail;
            $facebook = $result[0]->url_facebook;
            $youtube = $result[0]->url_youtube;
            $video_youtube = $result[0]->video_youtube;
            $instagram = $result[0]->url_instagram;
            $linkedin = $result[0]->url_linkedin;
            $twitter = $result[0]->url_twitter;
            $calendly = $result[0]->calendly;
            $opensea = $result[0]->opensea;
            $metamask = $result[0]->metamask;
            $tiktok = $result[0]->url_tiktok;
            $spotify = $result[0]->url_spotify;
            $apple_music = $result[0]->url_apple_music;
            $url_behance = $result[0]->url_behance;
            $url_github = $result[0]->url_github;
            $url_telegram = $result[0]->url_telegram;
            $url_wechat = $result[0]->url_wechat;
            $personal_presentation = $result[0]->personal_presentation;
            $token = $result[0]->token;
            $background_color = $result[0]->background_color;
            $button_text_color = $result[0]->button_text_color;
            $button_background_color = $result[0]->button_background_color;
            $text_title_color = $result[0]->text_title_color;
            $text_color = $result[0]->text_color;
            $per_infor = $result[0]->personal_information;
            $href = home_url() . "/wp-vcards/$token.vcf";
            $facebook_business = $result[0]->facebook_business;
            $youtube_business = $result[0]->youtube_business;
            $instagram_business = $result[0]->instagram_business;
            $linkedin_business = $result[0]->linkedin_business;
            $twitter_business = $result[0]->twitter_business;
            $tiktok_business = $result[0]->tiktok_business;
    ?>
            <style type="text/css">
                body,
                .wrap {
                    background-color: <?= $background_color ?>;
                }

                .items .vcard {
                    color: <?= $button_text_color ?>;
                    background-color: <?= $button_background_color ?>;
                }

                .items .cell i {
                    color: <?= $button_text_color ?>;
                    background-color: <?= $button_background_color ?>;
                }

                .perfil-button i {
                    color: <?= $button_text_color ?>;
                    background-color: <?= $button_background_color ?>;
                }

                h1,
                h2 {
                    color: <?= $text_title_color ?>;
                }

                p,
                a,
                button {
                    color: <?= $text_color ?>;
                }

                /* Asegúrate de ajustar los selectores .button según tu tema o plantilla */
            </style>
</head>

<body <?php body_class(); ?>>
    <div class="wrap">
        <div class="row">
            <div class="col-sm">
            </div>
            <div class="col-sm perfil">
                <?php if (empty($foto)) : ?>
                    <div class="profile-img">
                        <img class="img-perfil" src="<?= plugins_url(basename(__DIR__) . '/assets/img/perfil.jpg') ?>" />
                    </div>
                <?php else : ?>
                    <div class="profile-img">
                        <img class="profile-pic" src="<?= get_home_url() . '/' . $foto ?>" />
                    </div>

                <?php endif; ?>

                <?php if (!empty($foto_business)) : ?>

                    <div class="for-border">
                        <div class="img-business">
                            <img class="profile-pic" src="<?= get_home_url() . '/' . $foto_business ?>" />
                        </div>
                    </div>

                <?php endif; ?>

                <div class="contenido">
                    <?php if (empty($nombres) && empty($apellidos)) : ?>
                        <div class="names">
                            <h1>Nombres y apellidos</h1>
                        </div>
                    <?php else : ?>
                        <div class="names">
                            <h1><?= $nombres . ' ' . $apellidos ?></h1>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($company_charge)) : ?>
                        <p>
                            <?= $company_charge ?> de
                            <?= $company_name ?>
                        </p>
                    <?php else : ?>
                        <p>
                            <?= $company_name ?>
                        </p>
                    <?php endif; ?>
                    <div>
                        <?php if (!empty($correo)) : ?>
                            <div class="img-icon mail">
                                <a href="mailto:<?= $correo ?>" target="_blank"><i class="fa fa-envelope"></i> <?= $correo ?></a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="items">

                        <div class="img-icon vcard">
                            <a href="<?php echo admin_url('admin-ajax.php?action=generarVcard&identificador=' . $id_vcard); ?>" download>
                                <i class="fa fa-user-plus"></i>
                                <span>Contacto</span>
                            </a>
                        </div>
                        <button type="button" class="vcard" data-bs-toggle="modal" data-bs-target="#modalContact"><i class="fa fa-exchange"></i> <span>Enviar contacto</span></button>
                    </div>
                    <div class="items">
                        <?php if (!empty($personal_phone)) : ?>
                            <div class="img-icon cell">
                                <a href="tel:<?= $personal_phone ?>"><i class="fa fa-phone"></i></a>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($calendly)) : ?>
                            <div class="img-icon cell">
                                <a class="perfil-button" href="<?= $calendly ?>" target="_blank">
                                    <i class="fa fa-calendar"></i>
                                    <!-- <i class="fab fa-tiktok"></i> -->
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($personal_web)) : ?>
                            <div class="img-icon cell">
                                <a class="perfil-button" href="<?= $personal_web ?>" target="_blank">
                                    <i class="fa fa-globe"></i>
                                    <!-- <i class="fab fa-tiktok"></i> -->
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($whatsapp_message)) : ?>
                            <div class="img-icon cell wsp-efect">
                                <a class="perfil-button" href="<?= "https://wa.me/" . $personal_phone . "?text=" . encodeURIComponent($whatsapp_message) ?>" target="_blank">
                                    <i class="fa fa-whatsapp wsap-button"></i>
                                    <!-- <i class="fab fa-tiktok"></i> -->
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($personal_presentation)) : ?>
                            <div class="img-icon cell wsp-efect">
                                <a class="presentation-button" href="<?= $personal_presentation ?>" target="_blank">
                                    <i class="fa fa-file"></i>
                                    <!-- <i class="fab fa-tiktok"></i> -->
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <div class="modal fade" id="modalContact" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form id="mi_formulario" method="post">
                                    <input type="hidden" name="id_vcard" value="<?= $result[0]->id_vcard ?>">
                                    <input type="hidden" name="user_id" value="<?= $result[0]->user_id ?>">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Datos contacto</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="recipient-name" class="col-form-label">Nombres:</label>
                                                <input type="text" name="names" class="form-control" id="recipient-name">
                                            </div>
                                            <div class="mb-3">
                                                <label for="lastname" class="col-form-label">Apellidos:</label>
                                                <input type="text" name="last_names" class="form-control" id="lastname">
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="col-form-label">Email:</label>
                                                <input type="text" name="email" class="form-control" id="email">
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone" class="col-form-label">Número de contacto:</label>
                                                <input type="text" name="phone" class="form-control" id="phone">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <input type="submit" class="btn btn-primary" value="Guardar" class="button">
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>


                    <?php if (!empty($per_infor)) : ?>
                        <div class="sobre-mi">
                            <h2>Sobre mí</h2>
                            <p>
                                <?= $per_infor ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <div>
                        <h2>Redes Sociales</h2>
                        <div class="redes-sociales">
                            <?php if (!empty($facebook)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $facebook ?>" target="_blank">

                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($youtube)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $youtube ?>" target="_blank">

                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($instagram)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $instagram ?>" target="_blank">

                                        <i class="fab fa-instagram"></i>
                                    </a>

                                </div>
                            <?php endif; ?>
                            <?php if (!empty($linkedin)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $linkedin ?>" target="_blank">

                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($twitter)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $twitter ?>" target="_blank">

                                        <i class="fab fa-x-twitter"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($tiktok)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $tiktok ?>" target="_blank">

                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($url_telegram)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $url_telegram ?>" target="_blank">

                                        <i class="fab fa-telegram"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($url_github)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $url_github ?>" target="_blank">

                                        <i class="fab fa-github"></i>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($url_behance)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $url_behance ?>" target="_blank">
                                        <i class="fab fa-behance"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($url_wechat)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $url_wechat ?>" target="_blank">
                                        <i class="fab fa-weixin"></i>
                                    </a>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div>
                        <?php if (!empty($company_mail)) : ?>
                            <h2>Información Empresarial</h2>
                            <div class="img-icon mail">
                                <a href="mailto:<?= $company_mail ?>" target="_blank"><i class="fa fa-envelope"></i> <?= $company_mail ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="redes-sociales">

                            <?php if (!empty($company_web)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $company_web ?>" target="_blank">
                                        <i class="fa fa-globe"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($facebook_business)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $facebook_business ?>" target="_blank">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($youtube_business)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $youtube_business ?>" target="_blank">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($instagram_business)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $instagram_business ?>" target="_blank">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($linkedin_business)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $linkedin_business ?>" target="_blank">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($twitter_business)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $twitter_business ?>" target="_blank">
                                        <i class="fab fa-x-twitter"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($tiktok_business)) : ?>
                                <div>
                                    <a class="perfil-button" href="<?= $tiktok_business ?>" target="_blank">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($opensea) && (!empty($metamask))) : ?>
                        <div class="web3">
                            <h2>Web 3</h2>
                            <?php if (!empty($opensea)) : ?>
                                <div class="web3-img opensea">
                                    <img class="img-web" src="<?= plugins_url(basename(__DIR__) . '/assets/img/opensea.svg') ?>" />
                                    <p>Opensea: <a class="perfil-button" href="<?= $opensea ?>" target="_blank"><?= $opensea ?>
                                            <!-- <i class="fab fa-tiktok"></i> --></a> </p>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($metamask)) : ?>
                                <div class="web3-img">

                                    <img class="img-web" src="<?= plugins_url(basename(__DIR__) . '/assets/img/MetaMask_Fox.svg') ?>" />

                                    <p>Metamask: </p>

                                    <button id="buttongo" class="button-go "><i class="fa fa-copy" title="Copiar para pegar"></i><span class="for-copy"><?= $metamask ?> </span>

                                    </button>
                                    <!-- <span id="tooltip"></span>  -->
                                    <!-- <i class="fab fa-tiktok"></i> -->
                                </div>


                            <?php endif; ?>
                        </div>
                    <?php endif; ?>


                    <?php if (!empty($video_youtube)) :
                        $cantidad_url_video     = strlen($video_youtube);
                        if ($cantidad_url_video == '28') {
                            $cortar_url             = str_replace('https://youtu.be/', '', $video_youtube);
                            $url_final_video         = 'https://www.youtube.com/embed/' . $cortar_url;
                        } elseif ($cantidad_url_video == '41') {
                            $cortar_url = str_replace('https://m.youtube.com/watch?v=', '', $video_youtube);
                            $url_final_video = 'https://www.youtube.com/embed/' . $cortar_url;
                        } elseif ($cantidad_url_video == '43') {
                            $cortar_url = str_replace('https://www.youtube.com/watch?v=', '', $video_youtube);
                            $url_final_video = 'https://www.youtube.com/embed/' . $cortar_url;
                        } elseif ($cantidad_url_video == '58') {
                            $cortar_url = str_replace('https://m.youtube.com/watch?v=', '', $video_youtube);
                            $url_final_video = 'https://www.youtube.com/embed/' . $cortar_url;
                        } elseif ($cantidad_url_video == '60') {
                            $cortar_url = str_replace('https://www.youtube.com/watch?v=', '', $video_youtube);
                            $url_final_video = 'https://www.youtube.com/embed/' . $cortar_url;
                        } else {
                            echo "URL INVALIDA";
                        } ?>
                        <div class="video-yutu">
                            <h2>Video Youtube</h2>
                            <div class="video-youtube">
                                <iframe width="100%" height="315" src="<?= $url_final_video ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>

                    <?php endif; ?>


                    <?php if (!empty($spotify) && (!empty($apple_music))) : ?>
                        <div class="musica">
                            <h2>Mi música</h2>
                            <div class="cajita">
                                <?php if (!empty($spotify)) : ?>
                                    <div>
                                        <a class="perfil-button" href="<?= $spotify ?>" target="_blank">

                                            <i class="fab fa-spotify"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($apple_music)) : ?>
                                    <div>
                                        <a class="perfil-button" href="<?= $apple_music ?>" target="_blank">

                                            <i class="fab fa-apple"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm">
            </div>
        </div>

    </div>
    <script src="<?= plugins_url(basename(__DIR__)) ?>/assets/copy_fast.js"></script>

<?php
        else :
            // $atts = [
            //     'message' => 'Link caído',
            //     'bye' => 'Consulte con su proveedor →',
            //     'href' => 'Volver a página principal'
            // ];
            // page_error($atts);

            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part(404);
            exit();
        endif;
    else :
        // $atts = [
        //     'message' => 'No se encontraron resultados',
        //     'bye' => 'Vuelve a',
        //     'href' => 'casa campeón!'
        // ];
        // page_error($atts);

        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        get_template_part(404);
        exit();
    endif;

    // get_footer(); // Incluir el pie de página del tema
    wp_footer();
?>
</body>

</html>
