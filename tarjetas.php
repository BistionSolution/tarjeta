<?php

register_activation_hook(__FILE__, 'activar');

function activar(){
/* 	global $wpdb;
	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuesta(
		'tarjetaId' INT NOT NULL AUTO_INCREMENT,
		'shortcode' VARCHAR(45) NULL
	)"; */

	if (get_option('tarjetas_cuenta')=== false){
		add_option('tarjetas_cuenta',"HOLA");
	}

	
}
function menu_ajuste_tarjetas(){
	add_menu_page('Cuentas de tarjetas',
	'Cuentas de Tarjetas',
	'manage_options',
	plugin_dir_path(__FILE__).'clientes.php', //slug
	null, // Funcion
	plugin_dir_url(__FILE__).'assets/img/icon.svg',
	'1'
	);
	
/* 	add_submenu_page(
		'conf-tarjeta',// parent slug
		'Ajustes',// titulo de la pagina
		'Ajustes',// titulo del menu
		'manage_options',
		'tarjeta_ajustes', // slug
		'submenu' // funcion
	);
 */
}

add_action('admin_menu','menu_ajuste_tarjetas');

function genera_hmtl_pagina(){
	// $codigo_pagina = get_option('genera_hmtl_pagina');
	?>
	<div class="wrap">
		<h2>Cuentas de clientes</h2>
	</div>
	<?php
}
/* 
function submenu(){
	// $codigo_pagina = get_option('genera_hmtl_pagina');
	?>
	<div class="wrap">
		<h2>Cuentas de clientes</h2>
	</div>
	<?php
} */




//PRODUCTOS 
add_shortcode('productos_cliente', 'productos_cliente');

//Declaramos un shortcode para poder mostrar el listado mediante [productos_cliente]
function productos_cliente($parametros)
{
	global $wpdb;
	$customer_ids = $wpdb->get_col("SELECT DISTINCT meta_value  FROM $wpdb->postmeta
    WHERE meta_key = '_customer_user' AND meta_value > 0");
	echo "Listado de productos comprados <br/>";
	// Obtenemos todos los pedidos del cliente
	$pedidos = get_posts(array(
		'numberposts' => -1,
		'meta_key'    => '_customer_user',
		'meta_value'  => get_current_user_id(),
		'post_type'   => wc_get_order_types(),
		'post_status' => array_keys(wc_get_order_statuses()),
	));
	echo 'HE WE GO ---'. var_dump($customer_ids). '--- <br>';
	foreach ($customer_ids as $customer_id) {
		$customer = new WP_User($customer_id);
		echo 'VAMOSS:- ' .$customer->display_name. '<br />';
	}
	$cliente = wp_get_current_user();
	$idcliente = $cliente->ID;
	foreach ($pedidos as $pedido) {
		echo 'Identificador del pedido realizado:------------------------------ ' . $pedido->ID . '<br />';
		echo 'ESTADO:------------------------------ ' . $pedido->post_status . '<br />';
		if($pedido->post_status == 'wc-on-hold'){
			echo 'hahah';
		}

		//echo 'VAR DUMP :------------------------------ ' . var_dump($pedido) . '<br />'; ?>
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
<?php
		echo '<div></div>';
		$wp_pedido = new WC_Order($pedido->ID);
		$usuario = new WP_User($wp_pedido->user_id);
		$informacion_usuario = get_userdata($wp_pedido->user_id);
		echo 'Nombre: ' . $informacion_usuario->first_name . '<br/>';
		$lineas_pedido = $wp_pedido->get_items();
		foreach ($lineas_pedido as $linea_pedido) {
			$idproducto = $linea_pedido['product_id'];
			$producto = new WC_Product($idproducto);
			echo 'Identificador del producto comprado: ' . $linea_pedido['product_id'] . '<br />';
			echo 'Tipo de tarjeta: ' . $linea_pedido['name'] . '<br />';
		}
	}
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
// Premios

function create_nuevo_vc(){
    global $wp;
    $content = "BEGIN:VCARD\r\n";
    $content .= "VERSION:3.0\r\n";
    $content .= "CLASS:PUBLIC\r\n";
    $content .= "FN:Joe Wegner\r\n";
    $content .= "N:Wegner;Joe ;;;\r\n";
    $content .= "TITLE:Technology And Systems Administrator\r\n";
    $content .= "ORG:Wegner Design\r\n";
    $content .= "ADR;TYPE=work:;;21 W. 20th St.;Broadview ;IL;60559;\r\n";
    $content .= "EMAIL;TYPE=internet,pref:joe@wegnerdesign.com\r\n";
    $content .= "TEL;TYPE=work,voice:7089181512\r\n";
    $content .= "TEL;TYPE=HOME,voice:8352355189\r\n";
    $content .= "URL:http://www.wegnerdesign.com\r\n";
    $content .= "END:VCARD\r\n";
	
	$url_actual = home_url();
    $file_name = dirname(__FILE__);
    //$file = fopen($file_name,"w"); // W de Write
	// $file = fopen($file_name, 'w');
    // fwrite($file, $content);
    // fclose($file);

	// path to admin/
	$this_dir = dirname(__FILE__);
	$parent_dir = realpath($this_dir . '/..');
	$target_path = $parent_dir . '/tarjetas/' . 'pierre.vcf';
	$ourFileHandle = fopen($target_path, 'w') or die("can't open file");
	
	fwrite($ourFileHandle, $content);
	fclose($ourFileHandle);
	
	echo '<h1>SE CREO VCF'.$file_name.'</h1>';
}

add_action('woocommerce_account_regalos_endpoint', 'regalos_endpoint_content');
function regalos_endpoint_content()
{
	//echo 'tarjeta';
	create_nuevo_vc();

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

add_action('wp_enqueue_scripts','carga_stilos');

function carga_stilos(){
	wp_register_style('fontawesomecss' , plugin_dir_url(__FILE__)."assets/style.css");
	wp_enqueue_style('fontawesomecss' );
}