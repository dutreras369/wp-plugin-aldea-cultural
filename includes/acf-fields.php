
<?php
if (! defined('ABSPATH')) exit;

/**
 * ACF local fields coherentes con shortcodes:
 * - Taller: usado por [aldea_talleres]
 * - Alianza: usado por [aldea_alianzas]
 * - Post (actividades): galería usada por [aldea_slider] cuando no se pasan IDs
 */
add_action('acf/init', function () {
  if (! function_exists('acf_add_local_field_group')) return;

  /**
   * TALLER
   * Campos que leen los shortcodes/listados de talleres.
   */
  acf_add_local_field_group([
    'key' => 'group_taller',
    'title' => 'Ficha de Taller',
    'fields' => [
      [
        'key' => 'taller_fecha',
        'label' => 'Fecha',
        'name' => 'fecha',
        'type' => 'date_picker',
        'display_format' => 'dd/mm/yyyy',
        'return_format'  => 'Y-m-d',
      ],
      [
        'key' => 'taller_hora',
        'label' => 'Hora',
        'name' => 'hora',
        'type' => 'time_picker',
        'display_format' => 'H:i',
        'return_format'  => 'H:i',
      ],
      [
        'key' => 'taller_lugar',
        'label' => 'Lugar',
        'name' => 'lugar',
        'type' => 'text',
        'placeholder' => 'Sala / Dirección',
      ],
      [
        'key' => 'taller_valor',
        'label' => 'Valor',
        'name' => 'valor',
        'type' => 'text',
        'placeholder' => '$',
      ],
      [
        'key' => 'taller_link',
        'label' => 'Link inscripción (Google Form)',
        'name' => 'link_inscripcion',
        'type' => 'url',
        'instructions' => 'Si se completa, el botón mostrará “Inscribirse” y usará este link.',
        'placeholder' => 'https://docs.google.com/forms/...',
      ],
      [
        'key' => 'taller_whatsapp',
        'label' => 'WhatsApp (opcional)',
        'name' => 'whatsapp',
        'type' => 'text',
        'instructions' => 'Usado sólo si no hay link de inscripción. Formato: 56912345678 (sólo números).',
        'placeholder' => '56912345678',
      ],
      [
        'key' => 'taller_destacado',
        'label' => 'Destacado en Home',
        'name' => 'destacado',
        'type' => 'true_false',
        'ui'   => 1,
      ],
      [
        'key' => 'taller_galeria',
        'label' => 'Galería de Imágenes',
        'name' => 'galeria',
        'type' => 'gallery',
        'return_format' => 'array',      // ← el slider lee $img['ID']
        'preview_size'  => 'medium',
        'instructions'  => 'Usada por carruseles o lightbox en la ficha/slider.',
      ],
    ],
    'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'taller']]],
    'position' => 'acf_after_title',
  ]);

  /* -------------------------------------------------
 * EVENTO
 * Mapeado para el grid [ev_eventos]:
 * - Campos por item (CPT evento)
 * - Cabecera del bloque: titulo_eventos / intro_eventos (ver más abajo)
 * ------------------------------------------------- */
  acf_add_local_field_group([
    'key' => 'group_evento',
    'title' => 'Ficha de Evento',
    'fields' => [
      [
        'key' => 'evento_fecha',
        'label' => 'Fecha',
        'name' => 'fecha',
        'type' => 'date_picker',
        'display_format' => 'dd/mm/yyyy',
        'return_format'  => 'Y-m-d', // ← permite ordenar en WP_Query meta_value
        'required'       => 1,
      ],
      [
        'key' => 'evento_hora',
        'label' => 'Hora',
        'name' => 'hora',
        'type' => 'time_picker',
        'display_format' => 'H:i',
        'return_format'  => 'H:i',
      ],
      [
        'key' => 'evento_lugar',
        'label' => 'Lugar',
        'name' => 'lugar',
        'type' => 'text',
        'placeholder' => 'Sala / Dirección',
      ],
      [
        'key' => 'evento_valor',
        'label' => 'Valor',
        'name' => 'valor',
        'type' => 'text',
        'placeholder' => '$',
      ],
      [
        'key' => 'evento_link',
        'label' => 'Link inscripción (Google Form)',
        'name' => 'link_inscripcion',
        'type' => 'url',
        'instructions' => 'Si se completa, el botón mostrará “Inscribirse” y usará este link.',
        'placeholder' => 'https://docs.google.com/forms/...',
      ],
      [
        'key' => 'evento_whatsapp',
        'label' => 'WhatsApp (opcional)',
        'name' => 'whatsapp',
        'type' => 'text',
        'instructions' => 'Usado sólo si no hay link de inscripción. Formato: 56912345678 (sólo números).',
        'placeholder' => '56912345678',
      ],
      [
        'key' => 'evento_destacado',
        'label' => 'Destacado',
        'name' => 'destacado',
        'type' => 'true_false',
        'ui'   => 1,
      ],
      [
        'key'   => 'evento_descripcion',
        'label' => 'Descripción breve',
        'name'  => 'descripcion',
        'type'  => 'textarea',
        'rows'  => 3,
        'instructions' => 'Si se deja vacío, el shortcode usará el extracto o truncará el contenido.',
      ],
      [
        'key' => 'evento_galeria',
        'label' => 'Galería de Imágenes',
        'name' => 'galeria',
        'type' => 'gallery',
        'return_format' => 'array',   // ← para sliders/lightbox (usa $img["ID"])
        'preview_size'  => 'medium',
        'instructions'  => 'Opcional para fichas o sliders de eventos.',
      ],
    ],
    'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'evento']]],
    'position' => 'acf_after_title',
  ]);

  /**
   * ALIANZA
   * Mapeado para el grid [aldea_alianzas]: logo (ID) + URL + descripción.
   */
  acf_add_local_field_group([
    'key' => 'group_alianza',
    'title' => 'Ficha de Alianza',
    'fields' => [
      [
        'key' => 'alianza_url',
        'label' => 'Sitio / Enlace',
        'name' => 'url',
        'type' => 'url',
        'placeholder' => 'https://…',
      ],
      [
        'key' => 'alianza_logo',
        'label' => 'Logo',
        'name' => 'logo',
        'type' => 'image',
        'return_format' => 'id',         // ← usamos wp_get_attachment_image( ID )
        'preview_size'  => 'medium',
      ],
      [
        'key' => 'alianza_breve',
        'label' => 'Descripción breve',
        'name' => 'breve',
        'type' => 'textarea',
        'rows' => 3,
      ],
    ],
    'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'alianza']]],
    'position' => 'acf_after_title',
  ]);

  /**
   * POST (actividades)
   * Galería para el slider [aldea_slider] cuando no se pasan IDs.
   */
  acf_add_local_field_group([
    'key' => 'group_post_galeria',
    'title' => 'Galería (Slider del post)',
    'fields' => [
      [
        'key' => 'post_galeria',
        'label' => 'Imágenes',
        'name' => 'galeria',
        'type' => 'gallery',
        'return_format' => 'array',      // ← el shortcode espera array con ['ID']
        'preview_size'  => 'medium',
        'instructions'  => 'Si no pasas IDs en [aldea_slider], tomará estas imágenes.',
      ],
    ],
    'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'post']]],
    'position' => 'acf_after_title',
  ]);
});
