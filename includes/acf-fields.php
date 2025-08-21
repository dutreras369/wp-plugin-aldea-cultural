<?php
if ( ! defined('ABSPATH') ) exit;

add_action('acf/init', function(){
  if( ! function_exists('acf_add_local_field_group') ) return;

  // Taller
  acf_add_local_field_group([
    'key' => 'group_taller',
    'title' => 'Ficha de Taller',
    'fields' => [
      ['key'=>'taller_fecha','label'=>'Fecha','name'=>'fecha','type'=>'date_picker','display_format'=>'dd/mm/yyyy','return_format'=>'Y-m-d'],
      ['key'=>'taller_hora','label'=>'Hora','name'=>'hora','type'=>'time_picker','display_format'=>'H:i','return_format'=>'H:i:s'],
      ['key'=>'taller_lugar','label'=>'Lugar','name'=>'lugar','type'=>'text'],
      ['key'=>'taller_valor','label'=>'Valor','name'=>'valor','type'=>'text'],
      ['key'=>'taller_link','label'=>'Link inscripción (Google Form)','name'=>'link_inscripcion','type'=>'url'],
      ['key'=>'taller_whatsapp','label'=>'WhatsApp (opcional)','name'=>'whatsapp','type'=>'text','instructions'=>'56912345678 (solo números)'],
      ['key'=>'taller_destacado','label'=>'Destacado Home','name'=>'destacado','type'=>'true_false','ui'=>1],
      ['key'=>'taller_galeria','label'=>'Galería de Imágenes','name'=>'galeria','type'=>'gallery','return_format'=>'array','preview_size'=>'medium'],
    ],
    'location' => [[['param'=>'post_type','operator'=>'==','value'=>'taller']]],
    'position' => 'acf_after_title',
  ]);

  // Alianza
  acf_add_local_field_group([
    'key' => 'group_alianza',
    'title' => 'Ficha de Alianza',
    'fields' => [
      ['key'=>'alianza_url','label'=>'Sitio / Enlace','name'=>'url','type'=>'url'],
      ['key'=>'alianza_logo','label'=>'Logo','name'=>'logo','type'=>'image','return_format'=>'id','preview_size'=>'medium'],
      ['key'=>'alianza_breve','label'=>'Descripción breve','name'=>'breve','type'=>'textarea','rows'=>3],
    ],
    'location' => [[['param'=>'post_type','operator'=>'==','value'=>'alianza']]],
    'position' => 'acf_after_title',
  ]);

  // Posts (actividades): galería extra para slider
  acf_add_local_field_group([
    'key' => 'group_post_galeria',
    'title' => 'Galería (Slider del post)',
    'fields' => [
      ['key'=>'post_galeria','label'=>'Imágenes','name'=>'galeria','type'=>'gallery','return_format'=>'array','preview_size'=>'medium'],
    ],
    'location' => [[['param'=>'post_type','operator'=>'==','value'=>'post']]],
    'position' => 'acf_after_title',
  ]);
});
