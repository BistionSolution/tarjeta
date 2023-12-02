<div class="wrap">
<?php
echo "<h1>".get_admin_page_title()."</h1>";
?>
<br>
<?php
dcms_list_data();

function dcms_list_data() {
    global $wpdb;
    $search_condition = "";
    $search = $_REQUEST['search']??'';
    //$search = $_GET['search'];
    if ( $search ) $search_condition = "WHERE `names` like '%$search%'";

    // Count items
    $sqlTotalReg = "SELECT sum(wo.num_items_sold) as sum_items, wo.customer_id, wo.status,wc.username as username, wc.user_id as id_user FROM {$wpdb->prefix}wc_order_stats as wo INNER JOIN {$wpdb->prefix}wc_customer_lookup AS wc ON wo.customer_id = wc.customer_id WHERE wo.status in ('wc-processing','wc-completed') GROUP BY wo.customer_id";
    $resultTotalReg = $wpdb->get_results($sqlTotalReg, OBJECT);
    $numTotalRegistros = count($resultTotalReg);

    // Cantidad de registros por pagina
    $tamanoPagina = 1;
    
    // Jalamos las variables del paginador para calcular el offset
    if ((isset($_GET['paged'])) && ($_GET['paged'] != '')) {
        $offset = ($_GET['paged'] - 1) * $tamanoPagina;
        var_dump($offset);
    }

    else {
        $offset = 0;
    }

    // Items
    // $sql = "SELECT * FROM `$table_name` $search_condition LIMIT $start_number, $items_per_page";
    $sql = "SELECT sum(wo.num_items_sold) as sum_items, wo.customer_id, wo.status,wc.username as username, wc.user_id as id_user FROM {$wpdb->prefix}wc_order_stats as wo INNER JOIN {$wpdb->prefix}wc_customer_lookup AS wc ON wo.customer_id = wc.customer_id WHERE wo.status in ('wc-processing','wc-completed') GROUP BY wo.customer_id 
    LIMIT  " . $tamanoPagina . "
    OFFSET " . $offset . "; ";
    $items = $wpdb->get_results($sql,ARRAY_A);
    $value_pag = basename(__DIR__);

    $empTable = new WP_List_Table();
    $empTable->search_box('search', 'search_id');

    echo dcms_print_search($search,$value_pag);
    echo dcms_print_table($items,$value_pag);
    // echo "HOLA -> ".$search;

    //PAGINACION
    //Examino la página a mostrar y el inicio del registro a mostrar
    
    //Calculo el total de páginas
    $totalPaginas = (round($numTotalRegistros / $tamanoPagina));

    ?>
    <div class="paginate">
        <?php
        if ((isset($_GET['paged'])) && ($_GET['paged'] != '')) {
            $current = $_GET['paged'];
        }
        else {
            $current = 0;
        }
        $args = array(
            'base' => '%_%',
            'format' => '?paged=%#%',
            'total' => $totalPaginas,
            'current' => $current,
            'show_all' => false,
            'end_size' => 3,
            'mid_size' => 1,
            'prev_next' => true,
            'prev_text' => __('« Anterior'),
            'next_text' => __('Siguiente »'),
            'type' => 'plain',
            'add_args' => false,
            'add_fragment' => '',
            'before_page_number' => '',
            'after_page_number' => ''
        );
        ?>
        <?php echo paginate_links($args); ?>
    </div>
    <?php
}

function dcms_print_search($search,$value_pag){

	return '<form role="search" method="get" action="'.esc_url( home_url( '/' ) ).'">
				<input type="search" minlength="2" placeholder="Buscar Cliente" name="search" value="'.$search.'">
				<input type="submit" value="Buscar">
			';
}

function dcms_print_table($items,$value_pag){
	$result = '';
    
	// field names
	foreach ($items as $key => $value) {

        global $wpdb; 
        $id = $value['id_user'];
        $custo = $wpdb->get_var("SELECT customer_id FROM {$wpdb->prefix}wc_customer_lookup where user_id='$id'");
        
		$result .= '<tr>
			<td>'.$value['id_user'].'</td>
			<td>'.$value['username'].'</td>
			<td>'.wc_get_customer_total_spent($value['id_user']).'</td>
			<td>'.$value['sum_items'].'</td>
			<td><a class="button" href="'.esc_url(admin_url('admin.php?page='.$value_pag.'/tarjeta_detalle.php&valor='.$custo)).'">Ver tarjetas</a></td>
		</tr>';
	}

	$template = '<table class="wp-list-table widefat fixed striped page">
				  <thead>
					<th>ID</th>
					<th>Nombres</th>
					<th>Apellido</th>
					<th>Correo</th>
					<th>Accion</th>
				  </thead>
				  {data}
				</table>';

	return str_replace('{data}', $result, $template);
}
?>
</form>
</div>