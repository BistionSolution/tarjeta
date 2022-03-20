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
	add_menu_page(
		'Cuentas de tarjetas',
		'Cuentas de Tarjetas',
		'manage_options',
		plugin_dir_path(__FILE__) . 'clientes.php', //slug
		null, // Funcion
		plugin_dir_url(__FILE__) . 'assets/img/icon.svg',
		'1'
	);
	add_submenu_page(
		plugin_dir_path(__FILE__) . 'clientes.php', // parent slug
		'Tarjetas Registradas', // titulo de la pagina
		'Todas las Tarjetas', // titulo del menu
		'manage_options',
		plugin_dir_path(__FILE__) . 'tarjeta_detalle.php', // slug
		null // funcion
	);
}

add_action('admin_menu', 'menu_ajuste_tarjetas');
/*add_action('admin_menu','menu_ajuste_tarjetas2');*/

function genera_hmtl_pagina()
{
	// $codigo_pagina = get_option('genera_hmtl_pagina');
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
	$customer_id = $wpdb->get_var("SELECT customer_id FROM {$wpdb->prefix}wc_customer_lookup where user_id='$user_id'");
	$vcards = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where customer_id='$customer_id'");

	foreach ($vcards as $vcard) { ?>
		<div id="perfil-qr-page" class="card-view">
			<div class="row block-row block-header">
				<div class="col-4 col-left">
					<div class="col-container">
						<figure class="perfil-image">
							<img src="https://tarjetacenturion.com/wp-content/uploads/perfil-qr/user-qr-be8fb0c5422c0b692b08e65a490c6a1d.png" alt="Perfil QR">
						</figure>
						<a href="https://tarjetacenturion.com/cuenta/actualizar-mi-tarjeta/" class="btn btn-beige btn-block btn-sm">Actualizar datos</a>
						<div class="qr-download">
							<i class="fas fa-qrcode"></i> <a href="https://tarjetacenturion.com/wp-content/uploads/perfil-qr/user-qr-be8fb0c5422c0b692b08e65a490c6a1d.png" target="_blank" class="link link-light featured">Descargar QR</a>
						</div>
					</div>
				</div>
				<div class="col-8 col-right">
					<div class="col-container">
						<div class="content-block fullname">
							<h3>Renzo Jesús Trujillo Mendoza</h3>
						</div>
						<div class="content-block profile-lins">
							<small class="label-small">Enlace para compartir</small>
							<ul class="profile-qr-links">
								<li>
									<div class="copy-link">
										<div class="copy-link-container">
											<span class="textLink user-select-all">https://tarjetacenturion.com/u/wdz37219h54v</span>
											<button type="button" id="btnCopy" class="btn btn-dark btn-xs">Copiar</button>
											<input type="hidden" value="https://tarjetacenturion.com/u/wdz37219h54v">
										</div>
									</div>
								</li>
							</ul>
						</div>

						<div class="content-block social-networks">
							<small class="label-small">Compartir por</small>
							<ul class="social-share-icons">
								<li class="social-network messenger">
									<a href="javascript:void(0)" onclick="javascript:window.open( this.dataset.href, '_blank', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600' );return false;" data-href="http://www.facebook.com/dialog/send?app_id=891268654262273&amp;redirect_uri=https%3A%2F%2Ftarjetacenturion.com%2Fu%2Fwdz37219h54v&amp;link=https%3A%2F%2Ftarjetacenturion.com%2Fu%2Fwdz37219h54v&amp;display=popup" target="_blank">
										<i class="fab fa-facebook-messenger"></i> </a>
								</li>
								<li class="social-network whatsapp">
									<a href="https://wa.me/?text=Ahora+tengo+mi+Tarjeta+Centuri%C3%B3n+y+puedes+descargar+mis+datos+aqu%C3%AD%3A+https%3A%2F%2Ftarjetacenturion.com%2Fu%2Fwdz37219h54v" target="_blank">
										<i class="fab fa-whatsapp"></i> </a>
								</li>
								<li class="social-network 0">
									<a target="_blank">
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
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
	add_rewrite_endpoint('regalos', EP_ROOT | EP_PAGES);
}

/**
 * Recuperando el contenido de la nueva variable
 */

add_action('woocommerce_account_regalos_endpoint', 'regalos_endpoint_content');
function regalos_endpoint_content()
{
	//echo 'tarjeta';
	// create_nuevo_vc();
	if (!is_dir('users/')) {
		mkdir('users/');
		echo '<h1>HOLA QUE HACE</h1>';
	}
	echo do_shortcode('[productos_cliente]');
	//get_template_part('mis-tarjetas');
}
/** 
 * Modificaciones a menu de mi cuenta 
 */
function my_account_menu_order()
{
	$menuOrder = array(
		'regalos'             => __('Tus tarjetas', 'woocommerce'),
		'orders'             => __('Tus pedidos', 'woocommerce'),
		'edit-address'       => __('Direcciones', 'woocommerce'),
		'edit-account'    => __('Mis datos', 'woocommerce'),
		'customer-logout'    => __('Salir', 'woocommerce'),
		'dashboard'          => __('Inicio', 'woocommerce'),
	);
	return $menuOrder;
}
add_filter('woocommerce_account_menu_items', 'my_account_menu_order');




add_action('wp_enqueue_scripts', 'carga_stilos');

function carga_stilos()
{
	wp_register_style('fontawesomecss', plugin_dir_url(__FILE__) . "assets/style.css");
	wp_enqueue_style('fontawesomecss');
}
