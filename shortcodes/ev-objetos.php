<?php
/**
 * Shortcode: [ev-objetos tipo="terapia"]
 * Muestra tarjetas de objetos (terapias, cursos, programas) con modales personalizados.
 */
function ev_objetos_shortcode($atts) {
  $atts = shortcode_atts([
    'tipo' => 'terapia',
    'cantidad' => -1
  ], $atts, 'ev-objetos');

  $query = blog_get_custom_post_type($atts['tipo'], $atts['cantidad']);

  ob_start();

  if ($query->have_posts()) :
?>
    <div class="ev-objetos-grid">
      <?php while ($query->have_posts()) : $query->the_post(); ?>
        <?php
          $post_id = get_the_ID();
          $titulo = get_the_title();
          $imagen = get_the_post_thumbnail($post_id, 'medium');
          $descripcion = get_field('descripcion');
          $objetivo = get_field('objetivo');
          $propuesta = get_field('propuesta_valor');
          $proposito = get_field('proposito');
          $cliente = get_field('cliente_potencial');
          $modal_id = 'modal-' . $post_id;
          $tipo = $atts['tipo'];

          // Inicializar variables
          $producto_id = '';
          $payment_url = '';
          $formato = [];

          // Lógica según tipo
          if ($tipo === 'course') {
            $producto_id = get_post_meta($post_id, '_course_product_id', true);
            $payment_url = get_post_meta($post_id, 'course_payment_url', true);
            $formato = get_post_meta($post_id, 'course_formato', true);
          } elseif ($tipo === 'program') {
            $producto_id = get_post_meta($post_id, '_program_product_id', true);
          } elseif ($tipo === 'terapia') {
            $producto_id = get_post_meta($post_id, '_linked_product_id', true);
          }
        ?>

        <div class="ev-objeto-card">
          <?php echo $imagen; ?>
          <h3><?php echo esc_html($titulo); ?></h3>
          <button class="ev-open-modal" data-target="<?php echo esc_attr($modal_id); ?>">
            Ver más
          </button>
        </div>

        <!-- Modal -->
        <div id="<?php echo esc_attr($modal_id); ?>" class="ev-modal">
          <div class="ev-modal-content">
            <span class="ev-close-modal">&times;</span>
            <h2><?php echo esc_html($titulo); ?></h2>
            <?php echo get_the_post_thumbnail($post_id, 'large'); ?>

            <?php if ($descripcion): ?>
              <div class="ev-modal-section">
                <strong>Descripción:</strong>
                <p><?php echo wp_kses_post($descripcion); ?></p>
              </div>
            <?php endif; ?>

            <?php if ($objetivo): ?>
              <div class="ev-modal-section">
                <strong>Objetivo:</strong>
                <p><?php echo wp_kses_post($objetivo); ?></p>
              </div>
            <?php endif; ?>

            <?php if ($propuesta): ?>
              <div class="ev-modal-section">
                <strong>Propuesta de Valor:</strong>
                <p><?php echo wp_kses_post($propuesta); ?></p>
              </div>
            <?php endif; ?>

            <?php if ($proposito): ?>
              <div class="ev-modal-section">
                <strong>Propósito:</strong>
                <p><?php echo wp_kses_post($proposito); ?></p>
              </div>
            <?php endif; ?>

            <?php if ($cliente): ?>
              <div class="ev-modal-section">
                <strong>Cliente Ideal:</strong>
                <p><?php echo wp_kses_post($cliente); ?></p>
              </div>
            <?php endif; ?>

            <!-- Acción -->
            <div class="ev-modal-section text-center">
              <?php if ($tipo === 'course' && is_array($formato) && in_array('grabado', $formato)): ?>
                <?php if (!empty($payment_url)): ?>
                  <a href="<?php echo esc_url($payment_url); ?>" class="ev-cta-button" target="_blank" rel="noopener">
                    Ver curso grabado
                  </a>
                <?php else: ?>
                  <span class="ev-cta-unavailable">Curso grabado – enlace no disponible</span>
                <?php endif; ?>

              <?php elseif (!empty($producto_id)): ?>
                <a href="<?php echo esc_url(get_permalink($producto_id)); ?>" class="ev-cta-button">
                  Adquirir ahora
                </a>

              <?php else: ?>
                <span class="ev-cta-unavailable">Este contenido estará disponible pronto</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
<?php
  endif;

  wp_reset_postdata();
  return ob_get_clean();
}
add_shortcode('ev-objetos', 'ev_objetos_shortcode');
