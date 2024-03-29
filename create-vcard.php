<?php

function create_table_vcards()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "vcards";
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id_vcard INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		profile_url VARCHAR(100) UNIQUE,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        customer_id INT NOT NULL,
		user_id INT NOT NULL,
		names VARCHAR(100),
		last_names VARCHAR(100),
		pseudonym VARCHAR(100),
		birthday DATE,
		photo VARCHAR(100),
		photo_business VARCHAR(100),
		personal_web VARCHAR(100),
		personal_email VARCHAR(100),
		personal_cell_phone VARCHAR(100),
		personal_telephone VARCHAR(100),
		personal_address VARCHAR(100),
		personal_department VARCHAR(50),
		personal_country VARCHAR(50),
		personal_information TEXT,
		company_name VARCHAR(100),
		company_charge VARCHAR(100),
		company_web VARCHAR(100),
		company_mail VARCHAR(100),
		company_cell_phone VARCHAR(100),
		company_address VARCHAR(100),
		company_department VARCHAR(50),
		company_country VARCHAR(50),
		calendly VARCHAR(200),
		opensea VARCHAR(200),
		metamask VARCHAR(200),
		url_token VARCHAR(200),
		url_facebook VARCHAR(200),
		url_youtube VARCHAR(200),
		video_youtube VARCHAR(200),
		url_instagram VARCHAR(200),
		url_linkedin VARCHAR(200),
		url_twitter VARCHAR(200),
		url_tiktok VARCHAR(200),
		url_spotify VARCHAR(200),
		url_apple_music VARCHAR(200),
		state BOOLEAN,
		token varchar(100),
		hiden_ref varchar(50),
		whatsapp_ms text,
		type_card enum('blanca','negra','colores','personalizada')
	);";

	// Comprueba si existe la tabla en la BD
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
} 

// function add_page_view_contact()
// {
//     // Create post object
//     $my_page = array(
//       'post_title'    => wp_strip_all_tags('View Contact'),
//       'post_status'   => 'publish',
//       'post_author'   => 1,
//       'post_type'     => 'page'
//     );

//     // Insert the post into the database
//     $newvalue = wp_insert_post($my_page);

// 	update_option('view_contact', $newvalue);
// }