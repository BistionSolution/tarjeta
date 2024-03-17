<?php

// register_activation_hook(__FILE__, 'activar');

function activar()
{
	/* 	global $wpdb;
	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuesta(
		'tarjetaId' INT NOT NULL AUTO_INCREMENT,
		'shortcode' VARCHAR(45) NULL
	)"; */

	if (get_option('tarjetas_cuenta') === false) {
		add_option('tarjetas_cuenta', "HOLA");
	}
}
function menu_ajuste_tarjetas()
{
	// add_menu_page(
	// 	'Cuentas de tarjetas',
	// 	'Cuentas de Tarjetas',
	// 	'manage_options',
	// 	plugin_dir_path(__FILE__) . 'clientes.php', //slug
	// 	null, // Funcion
	// 	plugin_dir_url(__FILE__) . 'assets/img/icon.svg',
	// 	'1'
	// );

	// add_submenu_page(
	// 	null, // parent slug
	// 	'Tarjetas Registradas', // titulo de la pagina
	// 	'Todas las Tarjetas', // titulo del menu
	// 	'manage_options',
	// 	plugin_dir_path(__FILE__) . 'tarjeta_detalle.php', // slug
	// 	null // funcion
	// );

	// add_submenu_page(
	// 	'employees_list_table', // parent slug
	// 	'Tarjetas pendientes', // titulo de la pagina
	// 	'Tarjetas pendientes', // titulo del menu
	// 	'manage_options', // permisos al admin solo - capability
	// 	'manager_pro', // slug
	// 	'pendient_goes' // funcion
	// );
}

// add_action('admin_menu', 'menu_ajuste_tarjetas');

/*add_action('admin_menu','menu_ajuste_tarjetas2');*/
function genera_hmtl_pagina()
{
	// $codigo_pagina = get_option('genera_hmtl_pagina')
?>
	<div class="wrap">
		<h2>Cuentas de clientes</h2>
	</div>
	<?php
}
//PRODUCTOS 
add_shortcode('productos_cliente', 'productos_cliente');

//Declaramos un shortcode para poder mostrar el listado mediante [productos_cliente]
function productos_cliente($parametros)
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
							<a href="<?= $href . '/card-contacts/?id=' . $vcard->id_vcard ?>" class="btn-block">Contactos generados</a>
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
 * Modificaciones a menu de mi cuenta 
 */
/**
 * Nuevas variables para la pagina de mi cuenta.
 */
add_action('init', 'my_account_new_endpoints');

function my_account_new_endpoints()
{
	add_rewrite_endpoint('cards', EP_ROOT | EP_PAGES);
	add_rewrite_endpoint('card-edit', EP_ROOT | EP_PAGES);
	add_rewrite_endpoint('card-contacts', EP_ROOT | EP_PAGES);
}

/**
 * Recuperando el contenido de la nueva variable
 */

add_action('woocommerce_account_card-edit_endpoint', 'card_edit_endpoint_content');
function card_edit_endpoint_content()
{
	global $wpdb;
	// echo 'VAMO GO : '.$_GET['ga'];
	require "modal_carga.php";
	if (isset($_GET['id'])) :

		$id = $_GET['id'];
		$user_id = get_current_user_id();
		$customer_id = $wpdb->get_var("SELECT customer_id FROM {$wpdb->prefix}wc_customer_lookup where user_id='$user_id'");
		$vcards = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where user_id='$user_id' and id_vcard = $id");

		if (!empty($vcards)) { ?>
			<form id="perfil-qr-form" class="woocommerce-EditProfileQrForm edit-profile-qr" action="<?= esc_url(admin_url('admin-post.php')) ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" class="identificador-vcard" name="identificador" value="<?= $id ?>">
				<?php foreach ($vcards as $v) : ?>
					<header class="form-header">
						<h2 class="entry-title featured ">Datos de Contacto</h2>
						<p class="text-muted">Agrega la información que quieras compartir cuando escaneen tu tarjeta. Puedes actualizarla cada vez que lo requieras.</p>
					</header>

					

					<div class="accordion">
						

						<section class="accordion-row">

							<header id="field-group-3-heading" class="accordion-header" data-toggle="collapse" data-target="#field-group-3" aria-expanded="false" aria-controls="field-group-3">
								<h6 class="title">Web 3</h6>
								<span class="icon"><i class="fas fa-plus"></i></span>
							</header>
							<div id="field-group-3" class="accordion-content collapse show" aria-labelledby="field-group-3-heading" data-parent="#perfil-qr-form">
								<div class="row row-fields row-form-container">
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-26">Opensea</label>
											<input id="field-4-26" name="opensea" value="<?= $v->opensea ?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-26">Metamask</label>
											<input id="field-4-26" name="metamask" value="<?= $v->metamask ?>">
										</div>
									</div>
								</div>
							</div>

						</section>

						<div class="form-buttons">
							<!-- <input type="hidden" id="_nonce" name="_nonce" value="b45be13a75"><input type="hidden" name="_wp_http_referer" value="/cuenta/actualizar-mi-tarjeta/"> <a href="https://tarjetacenturion.com/cuenta/mi-tarjeta/" class="btn btn-light">Cancelar</a> -->
							<button type="submit" id="btn-actualizar" class="button btn btn-dark" name="arct_save_profile_qr" value="Guardar cambios">Guardar cambios</button>
							<input type="hidden" name="action" value="updateVcard">
						</div>

					</div>
				<?php endforeach; ?>
			</form>
			<script src="<?= plugins_url(basename(__DIR__)) ?>/assets/imagen.js"></script>
<?php } else {
			echo "No tienes autorización para acceder aqui.";
		}
	endif;
}

add_action('woocommerce_account_cards_endpoint', 'cards_endpoint_content');
function cards_endpoint_content()
{
	// echo 'tarjeta';
	// create_nuevo_vc();
	echo do_shortcode('[productos_cliente]');
	//get_template_part('mis-tarjetas');
}
/** 
 * Modificaciones a menu de mi cuenta 
 */
function my_account_menu_order()
{
	$menuOrder = array(
		'dashboard'          => __('Inicio', 'woocommerce'),
		'cards'             => __('Mis tarjetas', 'woocommerce'),
		'orders'             => __('Mis pedidos', 'woocommerce'),
		'edit-account'    => __('Mis datos', 'woocommerce'),
		'edit-address'       => __('Direcciones', 'woocommerce'),
		'customer-logout'    => __('Salir', 'woocommerce')
	);
	return $menuOrder;
}

function carga_stilos()
{
	wp_register_style('fontawesomecss', plugin_dir_url(__FILE__) . "assets/style.css");
	wp_register_style('estilos_404', plugin_dir_url(__FILE__) . "assets/error.css");
	wp_enqueue_style('fontawesomecss');
	wp_enqueue_style('estilos_404');
}

add_filter('woocommerce_account_menu_items', 'my_account_menu_order');
add_action('wp_enqueue_scripts', 'carga_stilos');
