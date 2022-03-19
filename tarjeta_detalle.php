
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
    $customer_tarjets = $wpdb->get_results("SELECT * FROM vcard where customer_id= $id");


?>
<div class=wrap>
<br><br><br>
<table class="wp-list-table widefat fixed striped page">
    <thead>
        <th>col 1</th>
        <th>col 2</th>
        <th>col 3</th>
        <th>col 4</th>
        <th>col 5</th>
        <th>Accion</th>
    </thead>
     
    <tbody>
        <?php foreach ($customer_tarjets as $i) : ?>
            <tr>
                <td><?php echo $i->id_tarjeta ; ?></td>
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
