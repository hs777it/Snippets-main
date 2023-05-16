<?php

/**
 * Admin - Columns Image
 *
 * &nbsp;
 * 
 * 
 */
add_filter('manage_posts_columns', 'add_img_column');
add_filter('manage_pages_columns', 'add_img_column');
add_filter('manage_posts_custom_column', 'manage_img_column', 10, 2);
add_filter('manage_pages_custom_column', 'manage_img_column', 10, 2);
function add_img_column($columns)
{
  //$columns = array_slice($columns, 0, 1, true) + array("links" => "Featured Image") + array_slice($columns, 1, count($columns) - 1, true);
  $columns['links'] = 'Featured Image';
  return $columns;
}
function manage_img_column($column_name, $post_id)
{
  if ($column_name == 'links') {
    echo get_the_post_thumbnail($post_id, array(100, 70)); // 'thumbnail', 'medium', 'large', 'full' , array( 100, 100)
  }
  return $column_name;
}

