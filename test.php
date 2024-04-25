add_action('admin_footer', 'admin_js_mensaje');
function admin_js_mensaje()
{
    $screen = get_current_screen();

    // Indicar el CTP
    if ($screen->id !== 'nwka_audit') { 
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            // Aca parece ser que esta trabajando con todos los acf-relationship? Me faltó chequear
            $('.acf-relationship .choices-list').on('click', 'li span', function () {
                var postId = $(this).data('id'); 
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        'action': 'fetch_post_meta',
                        'post_id': postId,
                    },
                    success: function (response) {
                        // console.log( response);
                        $('#mensaje-relacionado').html(response);
                    },
                    error: function (error) {
                        console.error("Error fetching post meta:", error);
                        $('#mensaje-relacionado').html('Hubo un error al obtener los datos.');
                    }
                });
            });
        });
    </script>

    <?php
}

add_action('wp_ajax_fetch_post_meta', 'fetch_post_meta_callback');

function fetch_post_meta_callback()
{
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if ($post_id) {
        $meta_value = get_post_meta($post_id, 'mensaje_secreto', true); // Indicar el meta
        
        if (!empty($meta_value)) {
            echo esc_html($meta_value);
        } else {
            echo 'El mensaje solicitado no está disponible.';
        }
    } else {
        echo 'No se encontró el mensaje.';
    }
    wp_die();
}

add_action('add_meta_boxes', 'test_metabox');

function test_metabox()
{
    add_meta_box('mensaje-metabox', 'Mensaje', 'custom_metabox_cb', 'nwka_audit', 'side', 'default'); // Indicar el CTP
}

function custom_metabox_cb($post)
{
    echo '<div id="mensaje-relacionado">Selecciona un post para ver el mensaje.</div>';
}











