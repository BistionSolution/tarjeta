<?php
/**
 * Plugin Name: Mis tarjetas
 */

require "tarjetas.php";
require "install.php";
require "uninstall.php";
require "page.php";
require "update-vcard.php";

// Este hook nos sirve cuando ativamos este fragmento de código para crear la tabla
register_activation_hook(__FILE__, 'create_table_vcards');
// Este hook nos sirve cuando desactivamos este fragmento de código y con esto borraremos la tabla
register_deactivation_hook(__FILE__, 'drop_table_vcards');
register_deactivation_hook(__FILE__, 'drop_directory_vcards');
register_deactivation_hook(__FILE__, 'drop_directory_photos');

// function edit_vcard(){
//         $id_vcard = $_POST['vcard'];
//         wp_redirect(add_query_arg(array(
//             'ga' => $id_vcard
//         ), get_home_url(). "/my-account/card-edit"));
// }

// add_action('admin_post_nopriv_editvcard', 'edit_vcard');
// add_action('admin_post_editvcard', 'edit_vcard');




// function add_my_custom_page() {
//     // Create post object
//     $my_post = array(
//       'post_title'    => wp_strip_all_tags( 'My Custom Page' ),
//       'post_content'  => 'My custom page content',
//       'post_status'   => 'publish',
//       'post_author'   => 1,
//       'post_type'     => 'page',
//       'to_ping' => plugin_dir_path(__FILE__).'page-my-custom-page.php'
//     );

//     // Insert the post into the database
//     wp_insert_post( $my_post );
// }


// register_activation_hook(__FILE__, 'add_my_custom_page');

function insert_contact($atts)
{
	global $wpdb;
    // echo "<pre>";
    // var_dump($atts['order']);
    
    $token = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());
	$vcard_info = array(
        'order_id' => intval($atts['order']),
		'product_id' => intval($atts['product']),
		'customer_id' => intval($atts['customer']),
        'token' => $token,
        'url_token' => get_home_url()."/view-contact/?token=".$token
	);
    
    $path_directory = realpath(dirname(__FILE__) . '/../../..') . '/wp-vcards';
    // echo $path_directory;
    if (!file_exists($path_directory)) {
        // do_shortcode("[insert_directory path='$path_directory']");
        insert_directory($path_directory);
    }

    $content = "";
    // $content = "BEGIN:VCARD\r\n";
    // $content .= "VERSION:3.0\r\n";
    // $content .= "CLASS:PUBLIC\r\n";
    // $content .= "FN:Joe Wegner\r\n";
    // $content .= "N:Wegner;Joe ;;;\r\n";
    // $content .= "TITLE:Technology And Systems Administrator\r\n";
    // $content .= "ORG:Wegner Design\r\n";
    // $content .= "ADR;TYPE=work:;;21 W. 20th St.;Broadview ;IL;60559;\r\n";
    // $content .= "EMAIL;TYPE=internet,pref:joe@wegnerdesign.com\r\n";
    // $content .= "TEL;TYPE=work,voice:7089181512\r\n";
    // $content .= "TEL;TYPE=HOME,voice:8352355189\r\n";
    // $content .= "URL:http://www.wegnerdesign.com\r\n";
    // $content .= "END:VCARD\r\n";

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
    // $path_dir = $parent_dir . "/wp-img";
    mkdir($path, 0777);
}

// function btn_link()
// {
    // global $wpdb;
    // $current_user = wp_get_current_user();
    // $table_name = $wpdb->prefix . "contacts";
    // $sql = "SELECT * FROM $table_name WHERE user_id=$current_user->ID token=$token";

    // $wpdb->query($sql);
    // echo "<pre>";
    // var_dump($wpdb->last_result);
    // var_dump($_SERVER);
    // $ruta = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
    // return "<a href='http://localhost/wordpress'><i class='fas fa-user'></i></a>";
// }

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
// add_shortcode('btn_link', 'btn_link');
// add_shortcode('insert_directory', 'insert_directory');