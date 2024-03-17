<?php
function agregar_ruta_dinamica_al_dominio()
{
    // Captura cualquier cosa que siga a tu dominio principal, por ejemplo, https://tudominio.com/lo-que-sea
    add_rewrite_rule('ga/^([^/]+)/?$', 'index.php?mi_variable=$matches[1]', 'top');
}
add_action('init', 'agregar_ruta_dinamica_al_dominio');

function registrar_mi_variable_query($vars)
{
    $vars[] = 'mi_variable';
    return $vars;
}
add_filter('query_vars', 'registrar_mi_variable_query');

function cargar_contenido_para_ruta_dinamica()
{
    global $wp;
    // Obtén la ruta actual del URL
    $ruta_actual = trim($wp->request, '/');
    if (empty($ruta_actual)) {
        // Hacer algo si no existe ruta actual
        // Por ejemplo, redireccionar a una página de error o mostrar un mensaje
        return;
    }

    // Comprueba si la ruta actual coincide con una página existente en WordPress
    $pagina = get_page_by_path($ruta_actual);

    if ($pagina !== null) {
        if ($ruta_actual === 'card') {
            global $wpdb;
            global $wp_query;
            $token = isset($_GET['token']) ? $_GET['token'] : '';
            // GENERAR vista TABLA DE TARJETAS DEL USUARIO
            $userCard = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}vcards where token='$token'");

            $pseudonym = '';
            // foreach ($userCard as $i) :
            //     $pseudonym = $i->pseudonym;
            // endforeach;
            $pseudonym = $userCard[0]->pseudonym;
            $ruta_plantilla = plugin_dir_path(__FILE__) . 'goes.php';
            if (empty($pseudonym)) {
                $parameters = array(
                    'token' => $token // Agrega tantos pares clave-valor como necesites
                );
                ob_start();
                include($ruta_plantilla);
                $template_content = ob_get_clean();

                // Imprimir el contenido de la plantilla
                echo $template_content;
                exit;
            }

            // Obtener la url de la pagina
            $url = home_url('/' . $pseudonym);
            // Redireccionar a la nueva url
            wp_redirect($url, 301);
            exit;
        }
        // La
        // La ruta existe y está manejada por WordPress
        // Realiza tus acciones aquí
        return;
    } else {
        // La ruta no existe o no está manejada por WordPress
        // Puedes manejar esta situación según sea necesario
        global $wpdb;
        global $wp_query;

        $table_name = $wpdb->prefix . "vcards";
        $sql = "SELECT * FROM $table_name WHERE pseudonym='$ruta_actual'";
        $wpdb->query($sql);
        $result = $wpdb->last_result;
        if (!empty($result)) :
            $ruta_plantilla = plugin_dir_path(__FILE__) . 'goes.php';
            if (file_exists($ruta_plantilla)) {
                $parameters = array(
                    'username' => $ruta_actual // Agrega tantos pares clave-valor como necesites
                );
                ob_start();
                include($ruta_plantilla);
                $template_content = ob_get_clean();

                // Imprimir el contenido de la plantilla
                echo $template_content;
                exit; // Detén la ejecución para evitar que WordPress continúe cargando otras plantillas.
            } else {
                // Verificar si existen rutas ya creadas
                $wp_query->set_404();
                status_header(404);
                get_template_part(404); // Carga el archivo 404.php de tu tema
                exit;
            }
        else :
            return;
        endif;
    }
}
add_action('template_redirect', 'cargar_contenido_para_ruta_dinamica');
