<?php
function update_nfc($atts){
    global $wpdb;
    
    $vcard_info = array(
            'order_id' => intval($atts['order']),
            'product_id' => intval($atts['product']),
            'customer_id' => intval($atts['customer']),
            'user_id' => intval($atts['user']),
            'hiden_ref' => $atts['hiden_ref'],
          );


    // NEW asdsad
    $get_id = $wpdb->get_results("SELECT id_vcard FROM `wp_vcards` WHERE user_id = 0 LIMIT 1"); // THE LAST REGISTER UPDATE FOR ASIGNED BY ADMIN
    if(count($get_id)){
      foreach($get_id as $n1):
            $wpdb->update(
            $wpdb->prefix . 'vcards',
            $vcard_info, // ARREGLO OF ELEMENTS TO UPDATE
            array('id_vcard' => $n1->id_vcard) // ID OF REGISTER WHERE UPDATE
            );
            
      endforeach;
      return 0;
    }else{
      return 1; 
    }
}
