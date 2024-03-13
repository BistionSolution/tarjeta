<?php

//PRODUCTOS 
add_shortcode('card_contacts', 'card_contacts_template');

//Declaramos un shortcode para poder mostrar el listado mediante [productos_cliente]
function card_contacts_template()
{
    global $wpdb;
    $user_id = get_current_user_id();
    //$customer_id = $wpdb->get_var("SELECT customer_id FROM {$wpdb->prefix}wc_customer_lookup where user_id='$user_id'"); // activar
    //$vcards = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where customer_id='$customer_id'"); //activas
    $vcards = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where user_id='$user_id'"); // scar si no funciona
    $href = dirname($_SERVER["REQUEST_URI"]);

    $count = 1;
    foreach ($vcards as $vcard) { ?>
        <div id="perfil-qr-page" class="card-view">
            <div class="row block-row block-header">
                <div class="col-4 col-left">
                    <div class="col-container">
                        <div class="col-container">
                        </div>
                        <figure class="perfil-image">
                            <?= do_shortcode("[kaya_qrcode content=$vcard->url_token]"); ?>
                        </figure>
                        <div class="tarjeta-buttons">
                            <a href="<?= $href . '/card-edit/?id=' . $vcard->id_vcard ?>" class="btn-block">Actualizar datos</a>
                            <a href="<?= $href . '/card-edit/?id=' . $vcard->id_vcard ?>" class="btn-block">Contactos generados</a>
                        </div>

                        <!-- <div class="qr-download">
							<i class="fas fa-qrcode"></i> <a href="https://tarjetacenturion.com/wp-content/uploads/perfil-qr/user-qr-be8fb0c5422c0b692b08e65a490c6a1d.png" target="_blank" class="link link-light featured">Descargar QR</a>
						</div> -->
                    </div>
                </div>
                <div class="col-8 col-right">
                    <div class="col-container">
                        <div class="content-block fullname">
                            <h3>
                                Tipo: <?php echo $wpdb->get_var("SELECT post_title FROM {$wpdb->prefix}posts where id=$vcard->product_id");
                                        ?>
                            </h3>
                            <h3><?php
                                if (empty($vcard->names) & empty($vcard->last_names)) {
                                    echo 'Nombres y Apellidos';
                                } else {
                                    echo ucwords($vcard->names . ' ' . $vcard->last_names);
                                }
                                ?></h3>
                        </div>
                        <div class="content-block profile-lins">
                            <small class="label-small">Enlace para compartir</small>
                            <ul class="profile-qr-links">
                                <li>
                                    <div class="copy-link">


                                        <div class="copy-link-container">

                                            <input type="text" id="url" class="textLink<?= $count ?> user-select-all" value="<?= $vcard->url_token ?>" />
                                            <span class="icon">
                                                <i class="fa fa-copy" id="<?= $count ?>" title="Copiar para pegar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <span id="tooltip" class="tooltip"></span>
                                    <?php
                                    $count += 1;
                                    ?>
                                </li>
                                <li>

                                </li>

                            </ul>
                        </div>

                        <div class="content-block social-networks">
                            <small class="label-small">Compartir por</small>
                            <ul class="social-share-icons">
                                <li class="social-network messenger">
                                    <a href="javascript:void(0)" onclick="javascript:window.open( this.dataset.href, '_blank', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600' );return false;" data-href="http://www.facebook.com/dialog/send?app_id=891268654262273&amp;redirect_uri=https%3A%2F%2Ftarjetacenturion.com%2Fu%2Fwdz37219h54v&amp;link=<?php echo $vcard->url_token; ?>&amp;display=popup" target="_blank">
                                        <i class="fab fa-facebook-messenger"></i> </a>
                                </li>
                                <li class="social-network whatsapp">
                                    <a href="https://wa.me/?text=Ahora+tengo+mi+Tarjeta+Zentoc+y+puedes+descargar+mis+datos+aqu%C3%AD%3A+<?php echo $vcard->url_token ?>" target="_blank">
                                        <i class="fab fa-whatsapp"></i> </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?= plugins_url(basename(__DIR__)) ?>/assets/copy_text.js"></script>
        <?php }
}


/**
 * Recuperando el contenido de la nueva variable
 */

add_action('woocommerce_account_card-contacts_endpoint', 'card_contacts_endpoint_content');
function card_contacts_endpoint_content()
{
    // Obtener variables de la url para saber que mostrar
    $user_id = get_current_user_id();
    global $wpdb;

    // if ($action == 'edit') {
    //     include 'card_edit.php';
    // } else {
    //     include 'card_contact.php';
    // }
    if (isset($_GET['id'])) :

        $id = $_GET['id'];
        $user_id = get_current_user_id();
        $contacts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards_contacts where user_id='$user_id' and vcard_id = $id");

        if (!empty($contacts)) {
        ?>
            <table class="shop_table shop_table_responsive my_account_orders">
                <thead>
                    <tr>
                        <th class="order-number"><span>Nombre</span></th>
                        <th class="order-date"><span>Apellido</span></th>
                        <th class="order-status"><span>Correo</span></th>
                        <th class="order-total"><span>Teléfono</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($contacts as $contact) {
                    ?>
                        <tr class="order">
                            <td class="order-number" data-title="Nombre">
                                <?php echo $contact->names; ?>
                            </td>
                            <td class="order-date" data-title="Apellido">
                                <?php echo $contact->last_names; ?>
                            </td>
                            <td class="order-status" data-title="Correo">
                                <?php echo $contact->email; ?>
                            </td>
                            <td class="order-total" data-title="Teléfono">
                                <?php echo $contact->phone; ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
<?php
        } else {
            echo "No tienes autorización para acceder aqui.";
        }
    endif;
}


// /** 
//  * Modificaciones a menu de mi cuenta 
//  */
// function my_account_menu_order()
// {
//     $menuOrder = array(
//         'dashboard'          => __('Inicio', 'woocommerce'),
//         'cards'             => __('Mis tarjetas', 'woocommerce'),
//         'orders'             => __('Mis pedidos', 'woocommerce'),
//         'edit-account'    => __('Mis datos', 'woocommerce'),
//         'edit-address'       => __('Direcciones', 'woocommerce'),
//         'customer-logout'    => __('Salir', 'woocommerce')
//     );
//     return $menuOrder;
// }
