<?php

/**
 * Plugin Name: Mis tarjetas
 * Version: 4.2.0
 */

require "tarjetas.php";
require "create-vcard.php";
require "delete-vcard.php";
require "page-vcard.php";
require "error.php";
require "update-vcard.php";
require "qr-download.php";
require "generar_tarjetas.php";
require "edit_card.php";

// Este hook nos sirve cuando ativamos este fragmento de código para crear la tabla
register_activation_hook(__FILE__, 'create_table_vcards');
// register_activation_hook(__FILE__, 'add_page_view_contact');
// Este hook nos sirve cuando desactivamos este fragmento de código y con esto borraremos la tabla
// register_deactivation_hook(__FILE__, 'drop_table_vcards');
// register_deactivation_hook(__FILE__, 'drop_directory_vcards');
// register_deactivation_hook(__FILE__, 'drop_directory_photos');
// register_deactivation_hook( __FILE__, 'delete_page_view_contact');

function insert_contact($atts)
{
      global $wpdb;
      $token = uniqid('cod'); // GENERA CODIGO UNICO
      $vcard_info = array(
            'order_id' => 0,
            'product_id' => 0,
            'customer_id' => 0,
            'user_id' => 0,
            'hiden_ref' => 0,
            'token' => $token,
            'type_card' => $atts['tipo'],
            // 'url_token' => get_home_url()."/view-contact/?token=".$token
            'url_token' => get_home_url() . "/card/?token=" . $token
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
add_shortcode('update_nfc', 'update_nfc');
add_shortcode('page_vcard', 'page_vcard');
add_shortcode('page_error', 'page_error');
add_shortcode('qr_download', 'qr_download');

// add_shortcode('insert_directory', 'insert_directory');


// VAMOSSSSSSSS DESDE ACA ve el admin las cuentas con su tarjetas
// Loading table class
if (!class_exists('WP_List_Table')) {
      require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

// Extending class
class Employees_List_Table extends WP_List_Table
{
      private $users_data;

      private function get_users_data($search = "")
      {
            global $wpdb;

            if (!empty($search)) {

                  $sql = "SELECT sum(wo.num_items_sold) as sum_items, wo.customer_id, max(wo.order_id) as max_order, wo.status,wc.username as username, wc.user_id as id_user
                        FROM {$wpdb->prefix}wc_order_stats as wo
                        INNER JOIN {$wpdb->prefix}wc_customer_lookup AS wc
                        ON wo.customer_id = wc.customer_id
                        WHERE wo.status in ('wc-processing','wc-completed') AND username Like '%{$search}%'
                        GROUP BY wo.customer_id
                        ORDER BY max_order DESC";
                  // $sql = "SELECT ID,user_login,user_email,display_name from {$wpdb->prefix}users WHERE ID Like '%{$search}%' OR user_login Like '%{$search}%' OR user_email Like '%{$search}%' OR display_name Like '%{$search}%'";

                  return $wpdb->get_results(
                        $sql,
                        ARRAY_A
                  );
            } else {
                  $sql = "SELECT sum(wo.num_items_sold) as sum_items, wo.customer_id, max(wo.order_id) as max_order, wo.status,wc.username as username, wc.user_id as id_user
                        FROM {$wpdb->prefix}wc_order_stats as wo
                        INNER JOIN {$wpdb->prefix}wc_customer_lookup AS wc 
                        ON wo.customer_id = wc.customer_id
                        WHERE wo.status in ('wc-processing','wc-completed')
                        GROUP BY wo.customer_id
                        ORDER BY max_order DESC";

                  // $sql = "SELECT ID,user_login,user_email,display_name from {$wpdb->prefix}users";
                  return $wpdb->get_results(
                        $sql,
                        ARRAY_A
                  );
            }
      }

      // Define table columns
      function get_columns()
      {
            $columns = array(
                  'cb'            => '<input type="checkbox" />',
                  'max_order'    => 'Ultima orden',
                  'id_user' => 'ID Usuario',
                  'username' => 'Username',
                  'sum_items'    => 'Cantidad',
                  'customer_id'      => 'ID Cliente',
                  'accion' => 'Accion'
            );
            return $columns;
      }

      // Bind table with columns, data and all
      function prepare_items()
      {
            if (isset($_POST['page']) && isset($_POST['s'])) {
                  $this->users_data = $this->get_users_data($_POST['s']);
            } else {
                  $this->users_data = $this->get_users_data();
            }

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);

            /* pagination */
            $per_page = 15;
            $current_page = $this->get_pagenum();
            $total_items = count($this->users_data);

            $this->users_data = array_slice($this->users_data, (($current_page - 1) * $per_page), $per_page);

            $this->set_pagination_args(array(
                  'total_items' => $total_items, // total number of items
                  'per_page'    => $per_page // items to show on a page
            ));

            usort($this->users_data, array(&$this, 'usort_reorder'));

            $this->items = $this->users_data;
      }

      var $tablita_id;
      // bind data with column
      function column_default($item, $column_name)
      {
            global $wpdb;
            $value_pag = basename(__DIR__);

            switch ($column_name) {
                  case 'id_user':
                        $id = $item[$column_name];
                        $GLOBALS['tablita_id'] = $item[$column_name];
                        return $id;
                  case 'username':
                        return $item[$column_name];

                  case 'sum_items':
                        return ucwords($item[$column_name]);
                  case 'max_order':
                        return $item[$column_name];
                  case 'customer_id':
                        return ucwords($item[$column_name]);
                  case 'accion':
                        $id_tabla = $GLOBALS['tablita_id'];
                        //$custo = $wpdb->get_var("SELECT customer_id FROM {$wpdb->prefix}wc_customer_lookup where user_id='$id_tabla'");  // activar si no finciona
                        //return '<a class="button" href="'.esc_url(admin_url('admin.php?page='.$value_pag.'/tarjeta_detalle.php&valor='.$custo)).'">Ver tarjetas</a>'; // activar si no finciona
                        return '<a class="button" href="' . esc_url(admin_url('admin.php?page=' . $value_pag . '/tarjeta_detalle.php&valor=' . $id_tabla)) . '">Crear y Ver tarjetas main</a>'; // quitar si no finciona
                  default:
                        return print_r($item, true); //Show the whole array for troubleshooting purposes
            }
      }

      // To show checkbox with each row
      function column_cb($item)
      {
            return sprintf(
                  '<input type="checkbox" name="user[]" value="%s" />',
                  $item['id_user']
            );
      }

      // Add sorting to columns
      protected function get_sortable_columns()
      {
            $sortable_columns = array(
                  'max_order' => array('max_order', false),
                  'id_user'  => array('id_user', false),
                  'username' => array('username', false),
                  'customer_id'   => array('customer_id', false)
            );
            return $sortable_columns;
      }

      // Sorting function
      function usort_reorder($a, $b)
      {
            // If no sort, default to user_login
            $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'max_order';
            // If no order, default to asc
            $order = (!empty($_GET['order'])) ? $_GET['order'] : 'desc';
            // Determine sort order
            $result = strcmp($a[$orderby], $b[$orderby]);
            // Send final sort direction to usort
            return ($order === 'asc') ? $result : -$result;
      }
}

// Adding menu
function my_add_menu_items()
{
      add_menu_page(
            'Cuentas de tarjetas', // titulo de la pagina
            'Cuentas tarjetas', // titulo del menu
            'manage_options', // capability // esto deberia estas : activate_plugins
            'employees_list_table', //slug
            'employees_list_init', // funcion del contenido
            plugin_dir_url(__FILE__) . 'assets/img/icon.svg', // icono
            '1'
      ); // Lugar

      add_submenu_page(
            'employees_list_table', // parent slug
            'Tarjetas pendientes', // titulo de la pagina
            'Tarjetas pendientes', // titulo del menu
            'manage_options', // permisos al admin solo - capability
            'pendient_list_table', // slug
            'pendient_impresion' // funcion
      );

      add_submenu_page(
            null, // parent slug
            'Tarjetas Registradas', // titulo de la pagina
            'Todas las Tarjetas', // titulo del menu
            'manage_options',
            plugin_dir_path(__FILE__) . 'tarjeta_detalle.php', // slug
            null // funcion
      );

      // MENU PARA VER CODIGO DE TARJERAS
      add_submenu_page(
            'employees_list_table', // parent slug
            'Cuentas tarjetas', // titulo de la pagina
            'Generar tarjetas', // titulo del menu
            'manage_options', // permisos al admin solo - capability
            'pendient_cuenta_table', // slug
            'cuenta_list' // funcion
      );
}
add_action('admin_menu', 'my_add_menu_items');

// Plugin menu callback function
function employees_list_init()
{
      // Creating an instance
      $empTable = new Employees_List_Table();

      echo '<div class="wrap"><h2>Cuenta de tarjetas clientes</h2>';
      // Prepare table
      $empTable->prepare_items();
?>
      <form method="post">
            <input type="hidden" name="page" value="employees_list_table" />
            <?php $empTable->search_box('search', 'search_id'); ?>
      </form>
<?php
      // Display table

      $empTable->display();
      echo '</div>';
}

// Extending class
class Pendient_List_Table extends WP_List_Table
{
      private $users_data;

      private function get_users_data($search = "")
      {
            global $wpdb;

            if (!empty($search)) {
                  $sql = "SELECT sum(wo.num_items_sold) as sum_items, wo.customer_id, max(wo.order_id) as max_order, wo.status,wc.username as username, wc.user_id as id_user
                        FROM {$wpdb->prefix}wc_order_stats as wo
                        INNER JOIN {$wpdb->prefix}wc_customer_lookup AS wc
                        ON wo.customer_id = wc.customer_id
                        WHERE wo.status = 'wc-processing' AND username Like '%{$search}%'
                        GROUP BY wo.customer_id ORDER BY max_order ASC";
                  // $sql = "SELECT ID,user_login,user_email,display_name from {$wpdb->prefix}users WHERE ID Like '%{$search}%' OR user_login Like '%{$search}%' OR user_email Like '%{$search}%' OR display_name Like '%{$search}%'";

                  return $wpdb->get_results(
                        $sql,
                        ARRAY_A
                  );
            } else {
                  $sql = "SELECT sum(wo.num_items_sold) as sum_items, wo.customer_id, max(wo.order_id) as max_order, wo.status,wc.username as username, wc.user_id as id_user
                        FROM {$wpdb->prefix}wc_order_stats as wo
                        INNER JOIN {$wpdb->prefix}wc_customer_lookup AS wc ON wo.customer_id = wc.customer_id
                        WHERE wo.status = 'wc-processing'
                        GROUP BY wo.customer_id ORDER BY max_order DESC";

                  // $sql = "SELECT ID,user_login,user_email,display_name from {$wpdb->prefix}users";
                  return $wpdb->get_results(
                        $sql,
                        ARRAY_A
                  );
            }
      }

      // Define table columns
      function get_columns()
      {
            $columns = array(
                  'cb'            => '<input type="checkbox" />',
                  'max_order'    => 'Ultima orden',
                  'id_user' => 'ID Usuario',
                  'username' => 'Username',
                  'sum_items'    => 'Cantidad',
                  'customer_id'      => 'ID Cliente',
                  'accion' => 'Accion'
            );
            return $columns;
      }

      // Bind table with columns, data and all
      function prepare_items()
      {
            if (isset($_POST['page']) && isset($_POST['s'])) {
                  $this->users_data = $this->get_users_data($_POST['s']);
            } else {
                  $this->users_data = $this->get_users_data();
            }

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);

            /* pagination */
            $per_page = 15;
            $current_page = $this->get_pagenum();
            $total_items = count($this->users_data);

            $this->users_data = array_slice($this->users_data, (($current_page - 1) * $per_page), $per_page);

            $this->set_pagination_args(array(
                  'total_items' => $total_items, // total number of items
                  'per_page'    => $per_page // items to show on a page
            ));

            usort($this->users_data, array(&$this, 'usort_reorder'));

            $this->items = $this->users_data;
      }

      var $tablita_id;
      // bind data with column
      function column_default($item, $column_name)
      {
            global $wpdb;
            $value_pag = basename(__DIR__);

            switch ($column_name) {
                  case 'id_user':
                        $id = $item[$column_name];
                        $GLOBALS['tablita_id'] = $item[$column_name];
                        return $id;

                  case 'username':
                        return $item[$column_name];

                  case 'sum_items':
                        return ucwords($item[$column_name]);

                  case 'max_order':
                        return $item[$column_name];

                  case 'customer_id':
                        return ucwords($item[$column_name]);

                  case 'accion':
                        $id_tabla = $GLOBALS['tablita_id'];
                        //$custo = $wpdb->get_var("SELECT customer_id FROM {$wpdb->prefix}wc_customer_lookup where user_id='$id_tabla'");  // activar si no finciona
                        //return '<a class="button" href="'.esc_url(admin_url('admin.php?page='.$value_pag.'/tarjeta_detalle.php&valor='.$custo)).'">Ver tarjetas</a>'; // activar si no finciona
                        return '<a class="button" href="' . esc_url(admin_url('admin.php?page=' . $value_pag . '/tarjeta_detalle.php&valor=' . $id_tabla)) . '">Crear y ver tarjetas</a>'; // quitar si no finciona
                  default:
                        return print_r($item, true); //Show the whole array for troubleshooting purposes
            }
      }

      // To show checkbox with each row
      function column_cb($item)
      {
            return sprintf(
                  '<input type="checkbox" name="user[]" value="%s" />',
                  $item['id_user']
            );
      }

      // Add sorting to columns
      protected function get_sortable_columns()
      {
            $sortable_columns = array(
                  'max_order' => array('max_order', false),
                  'id_user'  => array('id_user', false),
                  'username' => array('username', false),
                  'customer_id'   => array('customer_id', false)
            );
            return $sortable_columns;
      }

      // Sorting function
      //     function usort_reorder($a, $b)
      //     {
      //           // If no sort, default to user_login
      //           $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'username';
      //           // If no order, default to asc
      //           $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
      //           // Determine sort order
      //           $result = strcmp($a[$orderby], $b[$orderby]);
      //           // Send final sort direction to usort
      //           return ($order === 'asc') ? $result : -$result;
      //     }
}

function pendient_impresion()
{
      // Creating an instance
      $empTable = new Pendient_List_Table();

      echo '<div class="wrap"><h2>Tarjetas pendientes de creacion</h2>';
      echo '<div class="wrap"><p>Si se crean todas las tarjetas pendientes el cliente desaparecera de esta lista</p>';
      // Prepare table
      $empTable->prepare_items();
?>
      <form method="post">
            <input type="hidden" name="page" value="pendient_list_table" />
            <?php $empTable->search_box('search', 'search_id'); ?>
      </form>
<?php
      // Display table
      $empTable->display();
      echo '</div>';
}



class order_List_Table extends WP_List_Table
{
      private $users_data;

      private function get_users_data($search = "")
      {
            global $wpdb;

            if (!empty($search)) {
                  $sql = "SELECT sum(wo.num_items_sold) as sum_items, wo.customer_id, max(wo.order_id) as max_order, wo.status,wc.username as username, wc.user_id as id_user
                        FROM {$wpdb->prefix}wc_order_stats as wo
                        INNER JOIN {$wpdb->prefix}wc_customer_lookup AS wc
                        ON wo.customer_id = wc.customer_id
                        WHERE wo.status = 'wc-processing' AND username Like '%{$search}%'
                        GROUP BY wo.customer_id";
                  // $sql = "SELECT ID,user_login,user_email,display_name from {$wpdb->prefix}users WHERE ID Like '%{$search}%' OR user_login Like '%{$search}%' OR user_email Like '%{$search}%' OR display_name Like '%{$search}%'";

                  return $wpdb->get_results(
                        $sql,
                        ARRAY_A
                  );
            } else {
                  $sql = "SELECT wo.num_items_sold as cantidad,wc.username,wc.user_id, wo.customer_id, wo.order_id, wo.status
                FROM {$wpdb->prefix}wc_order_stats as wo
                INNER JOIN {$wpdb->prefix}wc_customer_lookup AS wc
                ON wo.customer_id = wc.customer_id
                WHERE wo.status = 'wc-processing' ORDER BY order_id DESC";

                  // $sql = "SELECT ID,user_login,user_email,display_name from {$wpdb->prefix}users";
                  return $wpdb->get_results(
                        $sql,
                        ARRAY_A
                  );
            }
      }

      // Define table columns
      function get_columns()
      {
            $columns = array(
                  'cb'            => '<input type="checkbox" />',
                  'cantidad'    => 'Cantidad',
                  'username'    => 'Nombre',
                  'user_id' => 'ID Usuario',
                  'customer_id' => 'ID cliente',
                  'order_id'    => 'ID orden',
                  'status'      => 'Estado',
                  'accion' => 'Accion'
            );
            return $columns;
      }

      // Bind table with columns, data and all
      function prepare_items()
      {
            if (isset($_POST['page']) && isset($_POST['s'])) {
                  $this->users_data = $this->get_users_data($_POST['s']);
            } else {
                  $this->users_data = $this->get_users_data();
            }

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);

            /* pagination */
            $per_page = 15;
            $current_page = $this->get_pagenum();
            $total_items = count($this->users_data);

            $this->users_data = array_slice($this->users_data, (($current_page - 1) * $per_page), $per_page);

            $this->set_pagination_args(array(
                  'total_items' => $total_items, // total number of items
                  'per_page'    => $per_page // items to show on a page
            ));

            usort($this->users_data, array(&$this, 'usort_reorder'));

            $this->items = $this->users_data;
      }

      var $tablita_id;
      // bind data with column
      function column_default($item, $column_name)
      {
            global $wpdb;
            $value_pag = basename(__DIR__);

            switch ($column_name) {
                  case 'cantidad':
                        return $item[$column_name];

                  case 'username':
                        return $item[$column_name];

                  case 'user_id':
                        return ucwords($item[$column_name]);

                  case 'customer_id':
                        return $item[$column_name];

                  case 'order_id':
                        $GLOBALS['order_id'] = $item[$column_name];
                        return ucwords($item[$column_name]);

                  case 'status':
                        return ucwords($item[$column_name]);

                  case 'accion':
                        return "<button type='button' class='btn btn-primary' onclick='OtenerId(" . $GLOBALS['order_id'] . ")'>
                              Ver
                        </button>"; // quitar si no finciona

                  default:
                        return print_r($item, true); //Show the whole array for troubleshooting purposes
            }
      }

      // To show checkbox with each row
      function column_cb($item)
      {
            return sprintf(
                  '<input type="checkbox" name="user[]" value="%s" />',
                  $item['order_id']
            );
      }

      // Add sorting to columns
      protected function get_sortable_columns()
      {
            $sortable_columns = array(
                  'cantidad' => array('cantidad', false),
                  'id_user'  => array('id_user', false),
                  'order_id' => array('order_id', false),
                  'customer_id'   => array('customer_id', false)
            );
            return $sortable_columns;
      }

      // Sorting function
      //     function usort_reorder($a, $b)
      //     {
      //           // If no sort, default to user_login
      //           $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'order_id';
      //           // If no order, default to asc
      //           $order = (!empty($_GET['order'])) ? $_GET['order'] : 'desc';
      //           // Determine sort order
      //           $result = strcmp($a[$orderby], $b[$orderby]);
      //           // Send final sort direction to usort
      //           return ($order === 'asc') ? $result : -$result;
      //     }
}

function order_impresion()
{
      // Creating an instance
      $empTable = new order_List_Table();
      echo '<h1>Ordenes de Clientes</h1>';

      include("modal_orders.php");

      // Prepare table
      $empTable->prepare_items();
?>
      <form method="post">
            <input type="hidden" name="page" value="order_list_table" />
            <?php $empTable->search_box('search', 'search_id'); ?>
      </form>

      <?php
      // Display table

      $empTable->display();
      ?>
<?php

}

function bootstrap_admin_style()
{
      wp_enqueue_style('boostrap_admin_style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
      wp_enqueue_style('own_admin_style', plugins_url(basename(__DIR__)) . '/assets/styles_admin.css');
      wp_enqueue_script('boostrap_admin_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js');
}
add_action('admin_enqueue_scripts', 'bootstrap_admin_style');
add_action('wp_enqueue_scripts', 'bootstrap_admin_style');

function ow_enqueue_script()
{
      wp_register_script(
            'ow-js',
            plugins_url(basename(__DIR__)) . "/assets/modal_pro.js",
            array('jquery'),
      );

      wp_localize_script('ow-js', 'ajax_object', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ow_once'),
            'hook' => 'ow_action'
      ));
      wp_enqueue_script('ow-js');
}

add_action('admin_enqueue_scripts', 'ow_enqueue_script');
add_action('wp_enqueue_scripts', 'ow_enqueue_script');


function detect_see_product()
{
      global $wpdb;
      ob_clean();

      if (isset($_REQUEST)) {
            $product_customers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = '{$_REQUEST['num_order']}'");
            print(json_encode($product_customers));
      }
}
add_action('wp_ajax_see_product', 'detect_see_product');

function delete_img()
{
      global $wpdb;
      ob_clean();
      if (isset($_REQUEST)) {
            $vcard_info = array(
                  'photo' => NULL
            );
            $id_tarje = $_REQUEST['id_vcard'];
            $file_photo = $wpdb->get_var("SELECT photo FROM {$wpdb->prefix}vcards where id_vcard='$id_tarje'");
            $file_photo_business = $wpdb->get_var("SELECT photo_business FROM {$wpdb->prefix}vcards where id_vcard='$id_tarje'");
            if (!empty($file_photo)) {
                  // Borramos la foto anterior
                  $path_directory_photo = realpath(dirname(__FILE__) . '/../../..');
                  unlink($path_directory_photo . '/' . $file_photo);
            }
            $wpdb->update(
                  $wpdb->prefix . 'vcards',
                  $vcard_info, // ARREGLO OF ELEMENTS TO UPDATE
                  array('id_vcard' => $id_tarje) // ID OF REGISTER WHERE UPDATE
            );
            $data = plugins_url(basename(__DIR__) . '/assets/img/ZENTOC-perfil.png');
            print($data);
      }
}
add_action('wp_ajax_delete_img', 'delete_img');

function delete_img_b()
{
      global $wpdb;
      ob_clean();
      if (isset($_REQUEST)) {
            $vcard_info = array(
                  'photo_business' => NULL
            );
            $id_tarje = $_REQUEST['id_vcard'];
            // $file_photo = $wpdb->get_var("SELECT photo FROM {$wpdb->prefix}vcards where id_vcard='$id_tarje'");
            $file_photo_business = $wpdb->get_var("SELECT photo_business FROM {$wpdb->prefix}vcards where id_vcard='$id_tarje'");
            if (!empty($file_photo_business)) {
                  // Borramos la foto anterior
                  $path_directory_photo = realpath(dirname(__FILE__) . '/../../..');
                  unlink($path_directory_photo . '/' . $file_photo_business);
            }
            $wpdb->update(
                  $wpdb->prefix . 'vcards',
                  $vcard_info, // ARREGLO OF ELEMENTS TO UPDATE
                  array('id_vcard' => $id_tarje) // ID OF REGISTER WHERE UPDATE
            );
            $data = plugins_url(basename(__DIR__) . '/assets/img/ZENTOC-perfil.png');
            print($data);
      }
}
add_action('wp_ajax_delete_img_b', 'delete_img_b');
