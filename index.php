<?php
/**
 * Plugin Name: Mis tarjetas
 */

require "tarjetas.php";
require "install.php";
require "uninstall.php";
require "page.php";
require "error.php";
require "update-vcard.php";

// Este hook nos sirve cuando ativamos este fragmento de código para crear la tabla
register_activation_hook(__FILE__, 'create_table_vcards');
register_activation_hook(__FILE__, 'add_page_view_contact');
// Este hook nos sirve cuando desactivamos este fragmento de código y con esto borraremos la tabla
register_deactivation_hook(__FILE__, 'drop_table_vcards');
register_deactivation_hook(__FILE__, 'drop_directory_vcards');
register_deactivation_hook(__FILE__, 'drop_directory_photos');
register_deactivation_hook( __FILE__, 'delete_page_view_contact' );

function insert_contact($atts)
{
	global $wpdb;

    $token = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());
	$vcard_info = array(
        'order_id' => intval($atts['order']),
		'product_id' => intval($atts['product']),
		'customer_id' => intval($atts['customer']),
        'token' => $token,
        'url_token' => get_home_url()."/view-contact/?token=".$token
	);
    
    $path_directory = realpath(dirname(__FILE__) . '/../../..') . '/wp-vcards';
    
    if (!file_exists($path_directory)) {
        // El shortcode no soporta muchos caracteres como los hay en una ruta
        // do_shortcode("[insert_directory path='$path_directory']");
        insert_directory($path_directory);
    }

    $content = "";

    $file = fopen($path_directory . "/$token.vcf", 'w');
    fwrite($file, $content);
    fclose($file);

	$table_name = $wpdb->prefix . 'vcards';
	$wpdb->insert($table_name, $vcard_info);
	
	// echo "<pre>";
	// var_dump($wpdb);
}

function insert_directory($path)
{
    // var_dump($path);
    // Obtenemos la ruta del archivo en el que nos encontramos
    // $this_dir = dirname(__FILE__);
    // Retrocedemos hasta llegar a la raíz del proyecto
    // $parent_dir = realpath($this_dir . '/../../..');    
    // $path_dir = $parent_dir . "/wp-vcards";
    mkdir($path, 0777);
}

// function salcodes_cta( $atts ) {
//     $a = shortcode_atts( array(
//         'link' => '#',
//         'id' => 'salcodes',
//         'color' => 'blue',
//         'size' => '',
//         'label' => 'Button',
//         'target' => '_self'
//     ), $atts );
//     $output = '<p><a href="' . esc_url( $a['link'] ) . '" id="' . esc_attr( $a['id'] ) . '" class="button ' . esc_attr( $a['color'] ) . ' ' . esc_attr( $a['size'] ) . '" target="' . esc_attr($a['target']) . '">' . esc_attr( $a['label'] ) . '</a></p>';
//     return $output;
// }

// add_shortcode( 'cta_button', 'salcodes_cta' );

add_shortcode('insert_contact', 'insert_contact');
add_shortcode('page_vcard', 'page_vcard');
add_shortcode('page_error', 'page_error');
// add_shortcode('insert_directory', 'insert_directory');