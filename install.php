<?php

function create_table_vcards()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "vcards";
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id_vcard INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        customer_id INT NOT NULL,
		names VARCHAR(100),
		last_names VARCHAR(100),
		pseudonym VARCHAR(100),
		birthday DATE,
		photo LONGBLOB,
		personal_web VARCHAR(100),
		personal_email VARCHAR(100),
		personal_cell_phone BIGINT,
		personal_telephone BIGINT,
		personal_address VARCHAR(100),
		personal_department VARCHAR(50),
		personal_country VARCHAR(50),
		company_name VARCHAR(100),
		company_charge VARCHAR(100),
		company_web VARCHAR(100),
		company_mail VARCHAR(100),
		company_cell_phone BIGINT,
		company_address VARCHAR(100),
		company_department VARCHAR(50),
		company_country VARCHAR(50),
		url_token VARCHAR(200),
		url_facebook VARCHAR(200),
		url_instagram VARCHAR(200),
		url_linkedin VARCHAR(200),
		url_twitter VARCHAR(200),
		url_tiktok VARCHAR(200),
		token varchar(100)
	);";
	
	// Comprueba si existe la tabla en la BD
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
} 

// function pepr_crear_tablas_bd() {
//     global $wpdb;
//     // Definimos el nombre de la tabla con el prefijo usado en la instalación:
//     $notas = $wpdb->prefix . 'pepr_notas';
//     $charset_collate = $wpdb->get_charset_collate();
//     // Diseñamos la consulta SQL para la nueva tabla:
//     $sql = "CREATE TABLE $notas (
//          id int(9) NOT NULL AUTO_INCREMENT,
//          proyecto varchar(55) NOT NULL,
//          titulo varchar(55) NOT NULL,
//          descripcion varchar(255),
//          prioridad varchar(55),
//          periodicidad varchar(55),
//          UNIQUE KEY id(id)
//          ) $charset_collate;";
   
//     require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
   
//     // Ejecutamos la consulta:
//     dbDelta($sql);
//    }
//    register_activation_hook(__FILE__, 'pepr_crear_tablas_bd');