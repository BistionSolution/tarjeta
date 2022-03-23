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
		null, // parent slug
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
	// $codigo_pagina = get_option('genera_hmtl_pagina')
	?>
	<div class="wrap">
		<h2>Cuentas de clientes</h2>
	</div>
<?php }


//PRODUCTOS 
add_shortcode('productos_cliente', 'productos_cliente');

//Declaramos un shortcode para poder mostrar el listado mediante [productos_cliente]
function productos_cliente($parametros)
{
	global $wpdb;
	$user_id = get_current_user_id();
	$customer_id = $wpdb->get_var("SELECT customer_id FROM {$wpdb->prefix}wc_customer_lookup where user_id='$user_id'");
	$vcards = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where customer_id='$customer_id'");
	$href = dirname($_SERVER["REQUEST_URI"]);
	foreach ($vcards as $vcard) { ?>
		<div id="perfil-qr-page" class="card-view">
			<div class="row block-row block-header">
				<div class="col-4 col-left">
					<div class="col-container">
						<figure class="perfil-image">
							<?=do_shortcode("[kaya_qrcode content=$vcard->url_token]");?>
						</figure>
						<a href="<?= $href . '/card-edit/?id=' . $vcard->id_vcard ?>" class="btn btn-beige btn-block btn-sm">Actualizar datos</a>
						<!-- <div class="qr-download">
							<i class="fas fa-qrcode"></i> <a href="https://tarjetacenturion.com/wp-content/uploads/perfil-qr/user-qr-be8fb0c5422c0b692b08e65a490c6a1d.png" target="_blank" class="link link-light featured">Descargar QR</a>
						</div> -->
					</div>
				</div>
				<div class="col-8 col-right">
					<div class="col-container">
						<div class="content-block fullname">
							<h3><?php 
							if (empty($vcard->names) & empty($vcard->last_names)){ 
							echo 'Nombres y Apellidos';
							 }else{
							echo ucwords($vcard->names.' '. $vcard->last_names); }
							?></h3>
						</div>
						<div class="content-block profile-lins">
							<small class="label-small">Enlace para compartir</small>
							<ul class="profile-qr-links">
								<li>
									<div class="copy-link">
										<div class="copy-link-container">
										<input type="text" id="url" class="textLink user-select-all" value="<?=$vcard->url_token?>"/>
                                            <span>
                                                <i class="fa fa-copy"></i>
                                            </span>
										</div>
									</div>
								</li>
							</ul>
						</div>

						<div class="content-block social-networks">
							<small class="label-small">Compartir por</small>
							<ul class="social-share-icons">
								<li class="social-network messenger">
									<a href="javascript:void(0)" onclick="javascript:window.open( this.dataset.href, '_blank', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600' );return false;" data-href="http://www.facebook.com/dialog/send?app_id=891268654262273&amp;redirect_uri=https%3A%2F%2Ftarjetacenturion.com%2Fu%2Fwdz37219h54v&amp;link=<?php echo $vcard->url_token ;?>&amp;display=popup" target="_blank">
										<i class="fab fa-facebook-messenger"></i> </a>
								</li>
								<li class="social-network whatsapp">
									<a href="https://wa.me/?text=Ahora+tengo+mi+Tarjeta+Zentoc+y+puedes+descargar+mis+datos+aqu%C3%AD%3A+<?php echo $vcard->url_token?>" target="_blank">
										<i class="fab fa-whatsapp"></i> </a>
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

		if (!empty($vcards)) {?>
			<div>
				<form id="perfil-qr-form" class="woocommerce-EditProfileQrForm edit-profile-qr" action="<?=esc_url(admin_url('admin-post.php')) ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="identificador" value="<?=$id?>">
				<?php foreach( $vcards as $v): ?>
					<header class="form-header">
						<h2 class="entry-title featured ">Datos de Contacto</h2>
						<p class="text-muted">Agrega la información que quieras compartir cuando escaneen tu tarjeta. Puedes actualizarla cada vez que lo requieras.</p>
					</header>

					<div class="profile-img-container">
						<div class="profile-img">
							<div id="preview">
								<img class="profile-pic" src="<?=get_home_url().'/'.$v->photo?>">
							</div>
						</div>
						<div class="profile-button">
							<i class="fa fa-camera upload-button"></i>
							<input class="file-upload" id="file_img" type="file" accept="image/png, image/jpeg, image/jpg" name="foto" multiple>
							<div id="preview"></div>
							<div id="text-img"  style="width: 300px;"></div>
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
											<input id="field-1-1" name="nombres" value="<?=$v->names?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-2">Apellidos</label>
											<input id="field-1-2" name="apellidos" value="<?=$v->last_names?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-3">Seudónimo</label>
											<input id="field-1-3" name="seudonimo" value="<?=$v->pseudonym?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-4">Cumpleaños</label>
											<input type="date" id="field-1-4" name="cumpleanios" value="<?=$v->birthday?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-7">Página Web Personal</label>
											<input id="field-1-7" name="paginaWebPersonal" value="<?=$v->personal_web?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-8">Email Principal</label>
											<input id="field-1-8" name="emailPrincipal" value="<?=$v->personal_email?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-9">Celular</label>
											<input id="field-1-9" name="celular" value="<?=$v->personal_cell_phone?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-1-10">Fijo / Casa</label>
											<input id="field-1-10" name="telefonoFijo" value="<?=$v->personal_telephone?>">
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
											<input id="field-2-11" name="empresa" value="<?=$v->company_name?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-12">Cargo</label>
											<input id="field-2-12" name="cargo" value="<?=$v->company_charge?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-13">Página Web</label>
											<input id="field-2-13" name="paginaWeb" value="<?=$v->company_web?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-14">Email Corporativo</label>
											<input id="field-2-14" name="emailCorporativo" value="<?=$v->company_mail?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-15">Teléfono</label>
											<input id="field-2-15" name="telefonoTrabajo" value="<?=$v->company_cell_phone?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-16">Dirección</label>
											<input id="field-2-16" name="direccionTrabajo" value="<?=$v->company_address?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-17">Departamento / Región</label>
											<input id="field-2-17" name="departamentoTrabajo" value="<?=$v->company_department?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-2-18">País</label>
											<input id="field-2-18" name="paisTrabajo" value="<?=$v->company_country?>">
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
											<input id="field-3-19" name="direccion" value="<?=$v->personal_address?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-3-20">Departamento / Región</label>
											<input id="field-3-20" name="departamento" value="<?=$v->personal_department?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-3-21">País</label>
											<input id="field-3-21" name="pais" value="<?=$v->personal_country?>">
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
											<input id="field-4-22" name="facebook" value="<?=$v->url_facebook?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-23">Instagram</label>
											<input id="field-4-23" name="instagram" value="<?=$v->url_instagram?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-24">LinkedIn</label>
											<input id="field-4-24" name="linkedin" value="<?=$v->url_linkedin?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-25">Twitter</label>
											<input id="field-4-25" name="twitter" value="<?=$v->url_twitter?>">
										</div>
									</div>
									<div class="form-row col-6">
										<div class="field-container">
											<label for="field-4-26">Tiktok</label>
											<input id="field-4-26" name="tiktok" value="<?=$v->url_tiktok?>">
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
			</div>
			<script src="<?=plugins_url(basename(__DIR__))?>/assets/imagen.js"></script>
		<?php } else {
			echo "No tienes autorización para acceder aqui."; }
	endif;

}

add_action('woocommerce_account_cards_endpoint', 'cards_endpoint_content');
function cards_endpoint_content()
{
	//echo 'tarjeta';
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
		'cards'             => __('Tus tarjetas', 'woocommerce'),
		'orders'             => __('Tus pedidos', 'woocommerce'),
		'edit-account'    => __('Mis datos', 'woocommerce'),
		'edit-address'       => __('Direcciones', 'woocommerce'),
		'customer-logout'    => __('Salir', 'woocommerce')
	);
	return $menuOrder;
}

function carga_stilos()
{
	wp_register_style('fontawesomecss', plugin_dir_url(__FILE__) . "assets/style.css");
	wp_register_style('estilos_404',plugin_dir_url(__FILE__) . "assets/error.css");
	wp_enqueue_style('fontawesomecss');
	wp_enqueue_style('estilos_404');
}

add_filter('woocommerce_account_menu_items', 'my_account_menu_order');
add_action('wp_enqueue_scripts', 'carga_stilos');


