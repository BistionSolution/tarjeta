<?php

function drop_table_vcards()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "vcards";
	$sql = "DROP TABLE IF EXISTS $table_name";
	
	$wpdb->query($sql);
}

function drop_directory_vcards()
{
	$path_directory = realpath(dirname(__FILE__) . '/../../..') . '/wp-vcards';
	foreach(glob($path_directory . "/*") as $archivos_carpeta){             
		if (is_dir($archivos_carpeta)){
			drop_directory_vcards($archivos_carpeta);
		} else {
			unlink($archivos_carpeta);
		}
	}	
	rmdir($path_directory);
}

function drop_directory_photos($path_directory)
{
	if (empty($path_directory))
	{
		$path_directory = realpath(dirname(__FILE__) . '/../../..') . '/wp-photos';
	}
	foreach(glob($path_directory . "/*") as $archivos_carpeta){             
		if (is_dir($archivos_carpeta)){
			drop_directory_photos($archivos_carpeta);
		} else {
			unlink($archivos_carpeta);
		}
	}	
	rmdir($path_directory);     
}

// function delete_page_view_contact()
// {
//     $page_id = get_option('view_contact');
//     wp_delete_post($page_id);
// }