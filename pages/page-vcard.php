<?php
// require 'error.php';
function encodeURIComponent($str)
{
    $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
    return strtr(rawurlencode($str), $revert);
}

function page_vcard()
{
?>
    <div class="wrap">
        <?php
        if (isset($_GET['token']) && !empty($_GET['token'])) :
            global $wpdb;
            $token = sanitize_text_field($_GET['token']);
            $table_name = $wpdb->prefix . "vcards";
            $sql = "SELECT * FROM $table_name WHERE token='$token'";
            $wpdb->query($sql);
            $result = $wpdb->last_result;
            // Si es una token válido
            if (!empty($result)) :
                $foto = $result[0]->photo;
                $foto_business = $result[0]->photo_business;
                $nombres = $result[0]->names;
                $apellidos = $result[0]->last_names;
                $personal_phone = $result[0]->personal_cell_phone;
                $whatsapp_message = $result[0]->whatsapp_ms;
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
                $spotify = $result[0]->url_spotify;
                $apple_music = $result[0]->url_apple_music;
                $token = $result[0]->token;
                $per_infor = $result[0]->personal_information;
                $href = home_url() . "/wp-vcards/$token.vcf";
        ?>
                <div class="perfil">
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
                            <h2>
                                <?= $company_charge ?> de
                                <?= $company_name ?>
                            </h2>
                        <?php else : ?>
                            <h2>
                                <?= $company_name ?>
                            </h2>
                        <?php endif; ?>
                        <div>
                            <?php if (!empty($correo)) : ?>
                                <div class="img-icon mail">
                                    <a href="mailto:<?= $correo ?>" target="_blank"><i class="fa fa-envelope"></i> <?= $correo ?></a>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="items">
                            <?php if (!empty($href)) : ?>
                                <div class="img-icon vcard">
                                    <a href="<?= $href ?>" download><i class="fa fa-user-plus"> </i><span>Contacto</span> </a>
                                </div>
                            <?php endif; ?>
                            <button type="button" class="vcard" data-bs-toggle="modal" data-bs-target="#modalContact"><i class="fa fa-exchange"></i> <span>Intercambio contacto</span></button>
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
                            <?php if (!empty($whatsapp_message)) : ?>
                                <div class="img-icon cell wsp-efect">
                                    <a class="perfil-button" href="<?= "https://wa.me/" . $personal_phone . "?text=" . encodeURIComponent($whatsapp_message) ?>" target="_blank">
                                        <i class="fa fa-whatsapp wsap-button"></i>
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

                        <div>
                            <h2>Redes Sociales</h2>
                        </div>

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

                                        <i class="fab fa-twitter"></i>
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

                        </div>
                        <?php if (!empty($per_infor)) : ?>
                            <div class="sobre-mi">
                                <h2>Sobre mí</h2>
                                <div>
                                    <?= $per_infor ?>
                                </div>
                            </div>
                        <?php endif; ?>

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

                                        <span>Metamask: </span>

                                        <button id="buttongo" class="button-go "><i class="fa fa-copy" title="Copiar para pegar"></i><span class="for-copy"><?= $metamask ?> </span>

                                        </button>
                                        <!-- <span id="tooltip"></span>  -->
                                        <!-- <i class="fab fa-tiktok"></i> -->
                                    </div>


                                <?php endif; ?>
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
    }
