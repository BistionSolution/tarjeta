
<div class=wrap>
<?php
    echo "<h1>".get_admin_page_title()."</h1>";
    global $wpdb;
    $query = "SELECT sum(wo.num_items_sold) as sum_items, wo.customer_id, wo.status,wc.username as username, wc.user_id as id_user FROM {$wpdb->prefix}wc_order_stats as wo INNER JOIN {$wpdb->prefix}wc_customer_lookup AS wc ON wo.customer_id = wc.customer_id WHERE wo.status in ('wc-processing','wc-completed') GROUP BY wo.customer_id";
    
    $lista = $wpdb->get_results($query,ARRAY_A);
?>
<a class="page-title-action">Añadir nueva</a>    
<br><br><br>
<table class="wp-list-table widefat fixed striped page">
    <thead>
        <th>ID</th>
        <th>Nombre de Empleado</th>
        <th>Precio total</th>
        <th>Cantidad de tarjetas</th>
        <th>Accion</th>
    </thead>
     
    <tbody>
        <?php foreach ($lista as $key => $value) :?>
            <tr>
                <td><?php echo $value['id_user']; ?></td>
                <td><?php echo $value['username']; ?></td>
                <td><?php echo wc_get_customer_total_spent($value['id_user']); ?></td>
                <td><?php echo $value['sum_items']; ?></td>
                <td>
                    <form method="post" action="<?=esc_url(admin_url('admin.php?page=tarjeta/tarjeta_detalle.php'))?>">
                        <input type="text" name="valor" hidden value="<?php
                        global $wpdb; 
                        $id = $value['id_user'];
                        echo $custo = $wpdb->get_var("SELECT customer_id FROM {$wpdb->prefix}wc_customer_lookup where user_id='$id'");
                        ?>"/>
                        <button type="submit">Ver tarjetas</button>
                    </form> 
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
