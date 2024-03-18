<?php

function create_table_contact()
{
	echo "creamndssss create_table_contact";
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$table_name = $wpdb->prefix . "vcards_contacts";
	$charset_collate = $wpdb->get_charset_collate();
	echo "creando tabla".$charset_collate;
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id_contact INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		user_id INT NOT NULL,
		vcard_id INT NOT NULL,
		names VARCHAR(100),
		last_names VARCHAR(100),
		phone VARCHAR(100),
		email VARCHAR(100)
	) $charset_collate;";

	// Comprueba si existe la tabla en la BD
	dbDelta($sql);
}
