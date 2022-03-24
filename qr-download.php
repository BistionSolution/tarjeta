<?php

function qr_download()
{
    ?><div class="wrap"><?php
    if(isset($_GET['url_token']) && !empty($_GET['url_token'])): 
        $url_token = sanitize_text_field($_GET['url_token']);
        ?>        
        <?php echo do_shortcode("[kaya_qrcode content=$url_token]"); ?>
        <a href="#" id="content-qr-download" onclick="downloadqr()">Descargar QR</a>
    </div>
    <script>
        function downloadqr()
        {
            var values = document.getElementsByClassName('wpkqcg_qrcode_wrapper');
            var img_b64 = null;
            for(var i=0; i<values.length; i++){
                img = values[i].getElementsByTagName('img')[0]['currentSrc'];
            }
            console.log(img);
            var a_qr = document.getElementById('content-qr-download');
            a_qr.setAttribute("download", "qr.png");
            a_qr.setAttribute("href", img);
        }
    </script>
<?php
    else:
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 ); exit();
    endif;
}
    