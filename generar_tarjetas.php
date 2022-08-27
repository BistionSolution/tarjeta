<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


// NUEVA INTEGRACION TRAJETA POR MAYOR
class Cuenta_lista_table extends WP_List_Table
{
    private $users_data;

    private function get_users_data($search = "")
    {
          global $wpdb;

          if (!empty($search)) {
                $sql = "SELECT id_vcard, order_id, product_id, url_token, user_id, state FROM {$wpdb->prefix}vcards";
                // $sql = "SELECT ID,user_login,user_email,display_name from {$wpdb->prefix}users WHERE ID Like '%{$search}%' OR user_login Like '%{$search}%' OR user_email Like '%{$search}%' OR display_name Like '%{$search}%'";
                return $wpdb->get_results(
                      $sql,
                      ARRAY_A
                );
          }else{
                $sql = "SELECT id_vcard, order_id, product_id, url_token, user_id, state FROM {$wpdb->prefix}vcards";
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
                'cb' => '<input type="checkbox" />',
                'id_vcard' => 'ID card',
                'order_id' => 'ID pedido',
                'product_id' => 'ID producto',
                'url_token' => 'URL token',
                'qr_generate' => 'QR',
                'user_id' => 'ID usuario',
                'state' => 'Estado Impresa'
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
          $per_page = 30;
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

    // bind data with column
    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id_vcard':
                  $id = $item[$column_name];
                  $GLOBALS['vcard_id'] = $item[$column_name];
                  return $id;

            case 'order_id':
                return ucwords($item[$column_name]);

            case 'product_id':
                  return ucwords($item[$column_name]);

            case 'url_token':
                  $GLOBALS['url_token'] = $item[$column_name];
                  return '<p>'.$item[$column_name].'</p>';

            case 'qr_generate':
                  return '<a href="'.home_url().'/qr-download/?url_token='.$GLOBALS['url_token'].'" target="_blank">Ir a descargar</a>';

            case 'user_id':
                  $color = '#00a878';
                  if ($item[$column_name] == 0){
                        $color = '#fe5e41';
                  }
                  return '<p style="background:'.$color.';" class="detect-id">'.$item[$column_name].'</p>';// quitar si no finciona

            case 'state':
                $estado = '';
                if($item[$column_name]==1){
                    $estado = 'checked';
                }
                return '<form method="post">
                            <div class="switch-button">
                                <input type="checkbox" name="'.$GLOBALS['vcard_id'].'" id="switch-label-'.$GLOBALS['vcard_id'].'" class="switch-button__checkbox" '.$estado.'>
                                <label for="switch-label-'.$GLOBALS['vcard_id'].'" class="switch-button__label"></label>
                            </div>
                        </form>';

            default:
                    return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    // To show checkbox with each row
     function column_cb($item)
     {
           return sprintf(
                 '<input type="checkbox" name="user[]" value="%s" />',
                 $item['id_vcard']
           );
     }

    // Add sorting to columns
    protected function get_sortable_columns()
    {
          $sortable_columns = array(
            'id_vcard' => array('id_vcard', true),
            'product_id'  => array('product_id', false)
          );
          return $sortable_columns;
    }

    // Sorting function
    function usort_reorder($a, $b)
    {
          // If no sort, default to user_login
          $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'id_vcard';
          // If no order, default to asc
          $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
          // Determine sort order
          $result = strcmp($a[$orderby], $b[$orderby]);
          // Send final sort direction to usort
          return ($order === 'asc') ? $result : -$result;
    }
}

function cuenta_list(){
      global $wpdb;
      // Creating an instance
      $empTable = new Cuenta_lista_table();
      echo '<div class="wrap"><h2>Tarjetas pendientes de creacion</h2>';
      echo '<div class="wrap"><p>Si se crean todas las tarjetas pendientes el cliente desaparecera de esta lista</p></div>';

      require "modal_carga.php";
      ?>
            <form method="post" action="">
                  <label>Cantidad de tarjetas a generar</label>
                  <input type="number" name="cant" placeholder="Cantidad">
                  <input type="submit" name="btncant" value="GENERAR" class="button">
            </form>
      <?php

      if(isset($_POST['btncant'])){
            $cant = $_POST['cant'];
            if($cant <= 0){
                  show_message('<div style="background:#EB6F6B;padding:3px 3px 3px 3px; border-radius:10px;"><h3><strong>Ingresar numero mayor a 0</strong></h3></div>');
            }else{
                  for($j=0; $j<$cant; $j++){
                        $defect_null = NULL;
                        do_shortcode("[insert_contact order={$defect_null} product={$defect_null} customer={$defect_null} hiden_ref={$defect_null} user={$defect_null}]");
                  }
            }
      }

      if(isset($_POST['btnsave_id'])){
            $user_id_get = $_POST['id_user'];
            $exit_user = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users WHERE ID = '{$user_id_get}'");
            if (empty($exit_user)){
                  show_message('<div style="background:#EB6F6B;padding:3px 3px 3px 3px; border-radius:10px;"><h3><strong>No existe el usuario</strong></h3></div>');
            }
            else{
                  $ar = array(
                        'user_id' => $_POST['id_user']
                  );
                        $wpdb->update(
                        $wpdb->prefix . 'vcards',
                        $ar,
                        array('id_vcard' => $_POST['id_vcard'])
                  );
            }
      }

       // Prepare table
       $empTable->prepare_items();
       ?>
             <form method="post">
                   <input type="hidden" name="page" value="pendient_cuenta_table" />
                   <?php $empTable->search_box('search', 'search_id'); ?>
             </form>
       <?php
       // Display table

       $empTable->display();

    //    order_impresion();
}


function detect_swicht_state(){
    global $wpdb;
    ob_clean();

    if (isset($_REQUEST)){
        $vcard_info = array(
            'state' => $_REQUEST['state']
          );
        $wpdb->update(
            $wpdb->prefix . 'vcards',
            $vcard_info, // ARREGLO OF ELEMENTS TO UPDATE
            array('id_vcard' => $_REQUEST['id_vcard']) // ID OF REGISTER WHERE UPDATE
            );
    }
}
add_action('wp_ajax_swicht_state','detect_swicht_state');