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
						<a href="http://localhost/wordpress/mi-cuenta/card-edit/?id=<?= $vcard->id_vcard ?>" class="btn btn-beige btn-block btn-sm">Actualizar datos</a>
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
	add_rewrite_endpoint('cards', EP_ROOT | EP_PAGES);
	add_rewrite_endpoint('card-edit', EP_ROOT | EP_PAGES);
}

/**
 * Recuperando el contenido de la nueva variable
 */

add_action('woocommerce_account_card-edit_endpoint', 'card_edit_endpoint_content');
function card_edit_endpoint_content()
{
	global $wpdb;
	// echo 'VAMO GO : '.$_GET['ga'];

	if (isset($_GET['id'])) :
		$id = $_GET['id'];

		$user_id = get_current_user_id();
		$customer_id = $wpdb->get_var("SELECT customer_id FROM {$wpdb->prefix}wc_customer_lookup where user_id='$user_id'");
		$vcards = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where customer_id='$customer_id' and id_vcard = $id");

		if (!empty($vcards)) {
			// var_dump($vcards);
		?>
			<div>
				<form id="perfil-qr-form" class="woocommerce-EditProfileQrForm edit-profile-qr" action="" method="post" enctype="multipart/form-data">
				<?php foreach( $vcards as $v): ?>
					<header class="form-header">
						<h2 class="entry-title featured ">Datos de Contacto</h2>
						<p class="text-muted">Agrega la información que quieras compartir cuando escaneen tu tarjeta. Puedes actualizarla cada vez que lo requieras.</p>
					</header>

					<div class="profile-img-container">
						<div class="profile-img">
							<img class="profile-pic" src="">
						</div>
						<div class="profile-button">
							<i class="fa fa-camera upload-button"></i>
							<input class="file-upload" type="file" accept="image/*" name="qr_fields[__MEDIA__][__USERIMG__]" value="<?=$v->photo?>">
						</div>
					</div>


					<div class="accordion">
						<section class="accordion-row">

							<header id="field-group-1-heading" class="accordion-header" data-toggle="collapse" data-target="#field-group-1" aria-expanded="true" aria-controls="field-group-1">
								<h6 class="title">Datos Personales</h6>
								<span class="icon"><i class="fas fa-plus"></i></span>
							</header>
							<div id="field-group-1" class="accordion-content collapse show" aria-labelledby="field-group-1-heading" data-parent="#perfil-qr-form">
								<div class="row row-fields row-form-container">
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-1">Nombres</label>
											<input id="field-1-1" name="qr_fields[__PERSONAL__][__FIRSTNAME__]" value="<?=$v->names?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-2">Apellidos</label>
											<input id="field-1-2" name="qr_fields[__PERSONAL__][__LASTNAME__]" value="<?=$v->last_names?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-3">Seudónimo</label>
											<input id="field-1-3" name="qr_fields[__PERSONAL__][__NICKNAME__]" value="<?=$v->pseudonym?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-4">Cumpleaños</label>
											<input id="field-1-4" name="qr_fields[__PERSONAL__][__BIRTHDATE__]" value="<?=$v->birthday?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-7">Página Web Personal</label>
											<input id="field-1-7" name="qr_fields[__PERSONAL__][__PERSONALWEBSITE__]" value="<?=$v->personal_web?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-8">Email Principal</label>
											<input id="field-1-8" name="qr_fields[__PERSONAL__][__PERSONALEMAIL__]" value="<?=$v->personal_email?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-9">Celular</label>
											<input id="field-1-9" name="qr_fields[__PERSONAL__][__CELULAR__]" value="<?=$v->personal_cell_phone?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-10">Fijo / Casa</label>
											<input id="field-1-10" name="qr_fields[__PERSONAL__][__FONOHOME__]" value="<?=$v->personal_telephone?>">
										</div>
									</div>
								</div>
							</div>

						</section>


						<section class="accordion-row">

							<header id="field-group-2-heading" class="accordion-header" data-toggle="collapse" data-target="#field-group-2" aria-expanded="false" aria-controls="field-group-2">
								<h6 class="title">Trabajo</h6>
								<span class="icon"><i class="fas fa-plus"></i></span>
							</header>
							<div id="field-group-2" class="accordion-content collapse " aria-labelledby="field-group-2-heading" data-parent="#perfil-qr-form">
								<div class="row row-fields row-form-container">
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-11">Empresa</label>
											<input id="field-2-11" name="qr_fields[__WORK__][__NOMBREEMPRESA__]" value="<?=$v->company_name?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-12">Cargo</label>
											<input id="field-2-12" name="qr_fields[__WORK__][__CARGO__]" value="<?=$v->company_charge?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-13">Página Web</label>
											<input id="field-2-13" name="qr_fields[__WORK__][__WEBEMPRESA__]" value="<?=$v->company_web?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-14">Email Corporativo</label>
											<input id="field-2-14" name="qr_fields[__WORK__][__EMAILEMPRESA__]" value="<?=$v->company_mail?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-15">Teléfono</label>
											<input id="field-2-15" name="qr_fields[__WORK__][__FONOEMPRESA__]" value="‪+51&nbsp;981&nbsp;453&nbsp;349‬">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-16">Dirección</label>
											<input id="field-2-16" name="qr_fields[__WORK__][__EMPRESADIRECCION__]" value="Av. Santa Cruz 1347">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-17">Departamento / Región</label>
											<input id="field-2-17" name="qr_fields[__WORK__][__EMPRESACIUDAD__]" value="Lima">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-18">País</label>
											<input id="field-2-18" name="qr_fields[__WORK__][__EMPRESAPAIS__]" value="Perú">
										</div>
									</div>
								</div>
							</div>

						</section>


						<section class="accordion-row">

							<header id="field-group-3-heading" class="accordion-header" data-toggle="collapse" data-target="#field-group-3" aria-expanded="false" aria-controls="field-group-3">
								<h6 class="title">Domicilio</h6>
								<span class="icon"><i class="fas fa-plus"></i></span>
							</header>
							<div id="field-group-3" class="accordion-content collapse " aria-labelledby="field-group-3-heading" data-parent="#perfil-qr-form">
								<div class="row row-fields row-form-container">
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-3-19">Dirección</label>
											<input id="field-3-19" name="qr_fields[__HOME__][__CASADIRECCION__]" value="">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-3-20">Departamento / Región</label>
											<input id="field-3-20" name="qr_fields[__HOME__][__CASACIUDAD__]" value="Lima">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-3-21">País</label>
											<input id="field-3-21" name="qr_fields[__HOME__][__CASAPAIS__]" value="Perú">
										</div>
									</div>
								</div>
							</div>

						</section>


						<section class="accordion-row">

							<header id="field-group-4-heading" class="accordion-header" data-toggle="collapse" data-target="#field-group-4" aria-expanded="false" aria-controls="field-group-4">
								<h6 class="title">Perfiles Sociales</h6>
								<span class="icon"><i class="fas fa-plus"></i></span>
							</header>
							<div id="field-group-4" class="accordion-content collapse " aria-labelledby="field-group-4-heading" data-parent="#perfil-qr-form">
								<div class="row row-fields row-form-container">
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-22">Facebook</label>
											<input id="field-4-22" name="qr_fields[__SOCIAL__][__SOCIALFACEBOOK__]" value="https://www.facebook.com/renzo.trujillo.35">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-23">Instagram</label>
											<input id="field-4-23" name="qr_fields[__SOCIAL__][__SOCIALINSTAGRAM_]" value="https://www.instagram.com/renzo.trujillo.96/">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-24">LinkedIn</label>
											<input id="field-4-24" name="qr_fields[__SOCIAL__][__SOCIALLINKEDIN__]" value="https://www.linkedin.com/in/renzotrujillo/">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-25">Twitter</label>
											<input id="field-4-25" name="qr_fields[__SOCIAL__][__SOCIALTWITTER__]" value="https://twitter.com/renzotrujillom">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-26">Tiktok</label>
											<input id="field-4-26" name="qr_fields[__SOCIAL__][__SOCIALTIKTOK__]" value="">
										</div>
									</div>
								</div>
							</div>

						</section>


						<div class="form-buttons">
							<input type="hidden" id="_nonce" name="_nonce" value="b45be13a75"><input type="hidden" name="_wp_http_referer" value="/cuenta/actualizar-mi-tarjeta/"> <a href="https://tarjetacenturion.com/cuenta/mi-tarjeta/" class="btn btn-light">Cancelar</a>
							<button type="submit" class="button btn btn-dark" name="arct_save_profile_qr" value="Guardar cambios">Guardar cambios</button>
							<input type="hidden" name="action" value="arct_save_profile_qr">
						</div>

					</div>
					<?php endforeach; ?>
				</form>
			</div>
<?php
		} else {
			echo "TE CAYO LA LEY PRRO";
		}
	// if (!empty($_GET['id'])): 
	//     global $wpdb;
	//     $token = sanitize_text_field($_GET['token']);
	//     $table_name = $wpdb->prefix . "vcards";
	//     $sql = "SELECT * FROM $table_name WHERE token='$token'";
	//     $wpdb->query($sql);
	//     // Si es una token válido
	//     if (!empty($wpdb->last_result)):

	//     $nombre = $wpdb->last_result[0]->company;
	//     $telefono = $wpdb->last_result[0]->title;
	//     // $direccion = $wpdb->last_result[0]->address;
	//     $correo = $wpdb->last_result[0]->email;
	//     $token = $wpdb->last_result[0]->token;
	//     $href = "/wp-nfc/wp-vcards/$token.vcf";
	//     endif;
	// endif; 
	endif;
	// create_nuevo_vc();
	//get_template_part('mis-tarjetas.php');

}

add_action('woocommerce_account_cards_endpoint', 'cards_endpoint_content');
function cards_endpoint_content()
{
	//echo 'tarjeta';
	// create_nuevo_vc();
	echo "holas";
	echo do_shortcode('[productos_cliente]');
	//get_template_part('mis-tarjetas');
}
/** 
 * Modificaciones a menu de mi cuenta 
 */
function my_account_menu_order()
{
	$menuOrder = array(
		'cards'             => __('Tus tarjetas', 'woocommerce'),
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
