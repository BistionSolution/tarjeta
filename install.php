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
		company VARCHAR(255) NOT NULL,
		title VARCHAR(255) NOT NULL,
		email varchar(255),
		telephone INT(11),
		url_page varchar(255),
		token varchar(255)
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