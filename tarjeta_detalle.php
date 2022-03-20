
<?php
    echo "<h1>Total de Cuentas</h1>";

/*if(!isset($_POST['valor']) || empty($_POST['valor'])):*/
        // wp_redirect(add_query_arg(array(
        //     'errormsg' => 'El campo nombre está incompleto'
        // ), get_home_url(). "/ver-contacto"));
        // exit;
    $id = sanitize_text_field($_POST['valor']);
 /*   endif;*/
    global $wpdb;
    /*$items = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wc_order_product_lookup where customer_id= $id");
    $cant_tarjets = $wpdb->get_var("SELECT SUM(product_qty) FROM {$wpdb->prefix}wc_order_product_lookup GROUP by customer_id HAVING customer_id=4");
    for ($i = 1; $i <=$cant_tarjets; $i++){
        $wpdb->insert( 'vcard', 
        array( 
        'customer_id' => 4
        )
        );
    }*/
        
        $product_order = $wpdb->get_results("SELECT pl.product_qty, pl.customer_id,pl.product_id,pl.order_id, os.status, pos.post_title as title FROM wp_wc_order_product_lookup as pl INNER JOIN wp_wc_order_stats AS os ON pl.order_id = os.order_id INNER JOIN wp_posts AS pos ON pl.product_id = pos.ID WHERE os.status in ('wc-processing','wc-completed') and os.customer_id = $id;");
        $insert = "INSERT INTO vcard(customer_id) VALUES (NEW.customer_id)";
        
        foreach ($product_order as $product):
            $vcar = $wpdb->get_results("SELECT * FROM vcard where customer_id= $id and order_id = $product->order_id and product_id = $product->product_id ");
            if (empty($vcar)){ // SI NO EXISTE PEDIDO ENTONCES entonces crea
                $i = 1;
                do{
                    // FUNCION MICHEAL PARA GENERAR QR
                    // CREAR TOKEN (PIERRE)
                    // CREAR VCARD Y LO GUARDAS EN LA CARPETA
                    // CREAR QR Y LO GUARDAS EN CAPETA QR
                    $datos = [
                        'id_tarjeta' => null,
                        'order_id' => $product->order_id,
                        'product_id' => $product->product_id,
                        'nombre' => $product->title,
                        'customer_id' => $id
                        // 'link' => funcion
                    ];
                    $wpdb->insert("vcard",$datos);
                    echo "CREA REGISTRO: ".$product->product_qty."<br>";
                    echo "CREA REGISTRO: ".$product->order_id."<br>";
                    echo "CREA REGISTRO: ".$product->product_id."<br>";
                    $i = $i + 1;
                }
                while($i <= $product->product_qty);
            }
            // if ($product->order_id == 'ga') {
            //     echo "hola";
            // }
                
        endforeach;
        $customer_tarjets = $wpdb->get_results("SELECT * FROM vcard where customer_id= $id");
        
?>
<div class=wrap>
<br><br><br>
<table class="wp-list-table widefat fixed striped page">
    <thead>
        <th>ID TARJETA</th>
        <th>order id</th>
        <th>Cliente id</th>
        <th>nombre</th>
        <th>col 4</th>
        <th>col 5</th>
        <th>Accion</th>
    </thead>
     
    <tbody>
        <?php foreach ($customer_tarjets as $i) : ?>
            <tr>
                <td><?php echo $i->id_tarjeta ; ?></td>
                <td><?php echo $i->order_id ; ?></td>
                <td><?php echo $i->customer_id ; ?></td>
                <td><?php echo $i->nombre ; ?></td>
                <td><?php echo $i->organizacion ; ?></td>
                <td><?php echo $i->url_pagina ; ?></td>
                <!-- <td><a class="page-title-action">:3</a></td> -->
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>      
