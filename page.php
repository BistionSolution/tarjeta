<?php
function page_vcard()
{
    ?><div class="wrap"><?php
    if(isset($_GET['token']) && !empty($_GET['token'])):
        global $wpdb;
        $token = sanitize_text_field($_GET['token']);
        $table_name = $wpdb->prefix . "vcards";
        $sql = "SELECT * FROM $table_name WHERE token='$token'";
        $wpdb->query($sql);
        // Si es una token válido
        if (!empty($wpdb->last_result)):            
            $foto = $wpdb->last_result[0]->photo;
            $nombres = $wpdb->last_result[0]->names;
            $apellidos = $wpdb->last_result[0]->last_names;
            $correo = $wpdb->last_result[0]->personal_email;
            $company_name = $wpdb->last_result[0]->company_name;
            $company_charge = $wpdb->last_result[0]->company_charge;
            $company_mail = $wpdb->last_result[0]->company_mail;
            $instagram = $wpdb->last_result[0]->url_instagram;
            $linkedin = $wpdb->last_result[0]->url_linkedin;
            $twitter = $wpdb->last_result[0]->url_twitter;
            $tiktok = $wpdb->last_result[0]->url_tiktok;
            $token = $wpdb->last_result[0]->token;
            $href = "/wp-nfc/wp-vcards/$token.vcf";
            $short_code_url = get_home_url()."/wp-nfc/wp-vcards/$token.vcf";
        ?>        
            <div class="text-center">
                <h1><?= $nombres ?></h1>
                <h1><?= $apellidos ?></h1>
                <h1><?= $correo ?></h1>
                <h1><?= $company_name ?></h1>
                <h1><?= $company_charge ?></h1>
                <h1><?= $company_mail ?></h1>
                <h1><?= $instagram ?></h1>
                <h4><?= $linkedin ?></h4>
                <h4><?= $twitter ?></h4>
                <h4><?= $tiktok ?></h4>
                <a href="<?=$href?>"><img src="<?=get_home_url()?>/images/user.png" alt="" width="40"></a>
            </div>
            <div>
                <?=do_shortcode("[kaya_qrcode content=$short_code_url]");?>
            </div>
    </div>
<?php
        endif;
    else:?>
        <style type="text/css">
            div.err {
                background: white;
                color: white;
                font-family: "Bungee", cursive;
                margin-top: 50px;
                text-align: center;
            }
            svg {
                width: 50vw;
            }
            .lightblue {
                fill: #444;
            }
            .eye {
                cx: calc(115px + 30px * var(--mouse-x));
                cy: calc(50px + 30px * var(--mouse-y));
            }
            #eye-wrap {
                overflow: hidden;
            }
            .error-text {
                font-size: 120px;
            }
            .alarm {
                animation: alarmOn 0.5s infinite;
            }
    
            @keyframes alarmOn {
                to {
                    fill: darkred;
                }
            }
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bungee">
        <div class="err" style="padding-top: 80px; padding-bottom: 80px;">
            <svg xmlns="http://www.w3.org/2000/svg" id="robot-error" viewBox="0 0 260 118.9">
            <defs>
                <clipPath id="white-clip">
                    <circle id="white-eye" fill="#cacaca" cx="130" cy="65" r="20" />
                </clipPath>
                <text id="text-s" class="error-text" y="106"> 404 </text>
            </defs>
            <path class="alarm" fill="#e62326" d="M120.9 19.6V9.1c0-5 4.1-9.1 9.1-9.1h0c5 0 9.1 4.1 9.1 9.1v10.6" />
            <use xlink:href="#text-s" x="-0.5px" y="-1px" fill="black"></use>
            <use xlink:href="#text-s" fill="#2b2b2b"></use>
            <g id="robot">
                <g id="eye-wrap">
                    <use xlink:href="#white-eye"></use>
                    <circle id="eyef" class="eye" clip-path="url(#white-clip)" fill="#000" stroke="#2aa7cc" stroke-width="2" stroke-miterlimit="10" cx="130" cy="65" r="11" />
                    <ellipse id="white-eye" fill="#2b2b2b" cx="130" cy="40" rx="18" ry="12" />
                </g>
                <circle class="lightblue" cx="105" cy="32" r="2.5" id="tornillo" />
                <use xlink:href="#tornillo" x="50"></use>
                <use xlink:href="#tornillo" x="50" y="60"></use>
                <use xlink:href="#tornillo" y="60"></use>
            </g>
            </svg>
            <h2>No hay resultados</h2>
            <h2>Vuelve a <a style="color: #565151;" href="<?=get_home_url()?>">casa campeón!</a></h2>
        </div>
        <script>
            var root = document.documentElement;
            var eyef = document.getElementById('eyef');
            var cx = document.getElementById("eyef").getAttribute("cx");
            var cy = document.getElementById("eyef").getAttribute("cy");
    
            document.addEventListener("mousemove", evt => {
                let x = evt.clientX / innerWidth;
                let y = evt.clientY / innerHeight;
    
                root.style.setProperty("--mouse-x", x);
                root.style.setProperty("--mouse-y", y);
    
                cx = 115 + 30 * x;
                cy = 50 + 30 * y;
                eyef.setAttribute("cx", cx);
                eyef.setAttribute("cy", cy);
    
            });
    
            document.addEventListener("touchmove", touchHandler => {
                let x = touchHandler.touches[0].clientX / innerWidth;
                let y = touchHandler.touches[0].clientY / innerHeight;
    
                root.style.setProperty("--mouse-x", x);
                root.style.setProperty("--mouse-y", y);
            });
        </script>
        <?php
    endif;
}
    