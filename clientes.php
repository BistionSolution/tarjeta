
<div class=wrap>
<?php
    echo "<h1>".get_admin_page_title()."</h1>";
    global $wpdb;

    $query = "SELECT sum(num_items_sold) AS hola , customer_id, status FROM {$wpdb->prefix}wc_order_stats GROUP BY customer_id, status HAVING status != 'wc-on-hold'";
    $lista = $wpdb->get_results($query,ARRAY_A);
    foreach ($lista as $key => $value) {
        $custome = $value['hola'];
        echo 'Vakis : '.$custome. '<br>';
    }
	$customer_ids = $wpdb->get_col("SELECT DISTINCT meta_value  FROM $wpdb->postmeta
    WHERE meta_key = '_customer_user' AND meta_value > 0");

    foreach ($customer_ids as $customer_id) {
    $customer = new WP_User($customer_id);
}
?>
<a class="page-title-action">Añadir nueva</a>    
<br><br><br>
<table class="wp-list-table widefat fixed striped page">
    <thead>
        <th>Nombre de Empleado</th>
        <th>Correo </th>
        <th>Precio total</th>
        <th>Cantidad de tarjetas</th>
        <th>Accion</th>
    </thead>
     
    <tbody>
        <?php foreach ($customer_ids as $customer_id) :
            $customer = new WP_User($customer_id); ?>
            <tr>
                <td><?php echo $customer->display_name; ?></td>
                <td><?php echo $customer->user_email; ?></td>
                <td><?php echo wc_get_customer_total_spent($customer_id); ?></td>
                <td></td>
                <td>
                    <form method="post" action="<?=esc_url(admin_url('admin.php?page=tarjeta/tarjeta_detalle.php'))?>">
                        <input type="text" name="valor" hidden value="<?php
                        global $wpdb; 
                        echo $custo = $wpdb->get_var("SELECT customer_id FROM {$wpdb->prefix}wc_customer_lookup where user_id=$customer_id");
                        ?>"/>
                        <button type="submit">Ver tarjetas</button>
                    </form> 
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
