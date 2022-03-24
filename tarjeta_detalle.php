
<?php 
    global $wpdb;
    $id= $_GET['valor'];
    $name_last = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wc_customer_lookup WHERE customer_id=$id");
    foreach($name_last as $nl):
    echo "<h2>Total de Cuentas de: ".ucwords($nl->first_name." ".$nl->last_name)."</h2>";   
    endforeach;
        $product_order = $wpdb->get_results("SELECT pl.product_qty, pl.customer_id,pl.product_id,pl.order_id, os.status, pos.post_title as title FROM {$wpdb->prefix}wc_order_product_lookup as pl INNER JOIN {$wpdb->prefix}wc_order_stats AS os ON pl.order_id = os.order_id INNER JOIN {$wpdb->prefix}posts AS pos ON pl.product_id = pos.ID WHERE os.status in ('wc-processing','wc-completed') and os.customer_id = $id;");
        foreach ($product_order as $product):
            $vcar = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where customer_id= $id and order_id = $product->order_id and product_id = $product->product_id ");
            if (empty($vcar)){ 
                $i = 1;
                do{
                    do_shortcode("[insert_contact order={$product->order_id} product={$product->product_id} customer={$id}]");
                    $i = $i + 1;
                }
                while($i <= $product->product_qty);
            }     
        endforeach;
        $customer_tarjets = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where customer_id= $id");
?>
<div class=wrap>
<br>
<table class="wp-list-table widefat fixed striped page">
    <thead>
        <th>ID TARJETA</th>
        <th>Order id</th>
        <th>Tipo de Tarjeta</th>
        <th>Url</th>
        <th>QR</th>
    </thead>
    <tbody>
        <?php foreach ($customer_tarjets as $i) : ?>
            <tr>
                <td><?php echo $i->id_vcard ; ?></td>
                <td><?php echo $i->order_id ; ?></td>
                <td><?php 
                $id_product = $i->product_id;
                echo $tipo_targeta = $wpdb->get_var("SELECT post_title FROM {$wpdb->prefix}posts where id=$id_product"); 
                ?></td>
                <td><?php echo $i->url_token ; ?></td>
                <td><a href="<?=home_url() . "/qr-download/?url_token=$i->url_token"?>" target="_blank">Ir a descargar</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
    
