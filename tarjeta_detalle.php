
<?php 
    global $wpdb;
    require "modal_carga.php";
    $id= $_GET['valor'];
    // AUTO GENERA
    $hiden_ref = "";
   
    $user_id = "";
    $count_rest = 0;
    //$name_last = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wc_customer_lookup WHERE customer_id=$id");
    $name_last = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wc_customer_lookup WHERE user_id=$id");

    foreach($name_last as $n1):
        $customer_id = $n1->customer_id;
        $hiden_ref = $n1->username;
        echo "<h2>Total de Cuentas de: ".ucwords($n1->first_name." ".$n1->last_name." username:".$n1->username)."</h2>";   
    endforeach;
    
    $product_order = $wpdb->get_results("SELECT pl.product_qty, pl.customer_id,pl.product_id,pl.order_id, os.status, pos.post_title as title 
                                            FROM {$wpdb->prefix}wc_order_product_lookup as pl 
                                            INNER JOIN {$wpdb->prefix}wc_order_stats AS os ON pl.order_id = os.order_id 
                                            INNER JOIN {$wpdb->prefix}posts AS pos ON pl.product_id = pos.ID 
                                            WHERE os.status in ('wc-processing','wc-completed') and os.customer_id = $customer_id;");
    foreach ($product_order as $product):
        $vcar = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where user_id = $id and order_id = $product->order_id and product_id = $product->product_id ");
        if (empty($vcar)){ 
            $i = 1;
            // Asigna usuario a tarjeta
            do{
                $count_rest += do_shortcode("[update_nfc order={$product->order_id} product={$product->product_id} customer={$customer_id} hiden_ref={$hiden_ref} user={$id}]");
                $i = $i + 1;
            }
            while($i <= $product->product_qty);
        }     
    endforeach;
    echo '<span>Falta crear esta cantidad de tarjetas : '.$count_rest.'</span>';


    // GENERAR vista TABLA DE TARJETAS DEL USUARIO
    $customer_tarjets = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where user_id= $id");
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
        <th>Estado</th>
    </thead>
    <tbody>
        <?php foreach ($customer_tarjets as $i) : 
            $estado = '';
            if($i->state==1){
                $estado = 'checked';
            }
            ?>
            <tr>
                <td><?php echo $i->id_vcard ; ?></td>
                <td><?php echo $i->order_id ; ?></td>
                <td><?php 
                $id_product = $i->product_id;
                echo $tipo_targeta = $wpdb->get_var("SELECT post_title FROM {$wpdb->prefix}posts where id=$id_product"); 
                ?></td>
                <td><?php echo $i->url_token ; ?></td>
                <td><a href="<?=home_url() . "/qr-download/?url_token=$i->url_token"?>" target="_blank">Ir a descargar</a></td>
                <td><form method="post">
                            <div class="switch-button">
                                <input type="checkbox" name="<?= $i->id_vcard ?>" id="switch-label-<?= $i->id_vcard ?>" class="switch-button__checkbox" <?php echo $estado ?>>
                                <label for="switch-label-<?= $i->id_vcard ?>" class="switch-button__label"></label>
                            </div>
                        </form></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
    
