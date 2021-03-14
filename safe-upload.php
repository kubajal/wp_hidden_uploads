<?php
/**
 * Safe file upload
 *
 * @package     SafeFileUpload
 * @author      JJ
 * @copyright   2021 JJ
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Safe file upload
 * Plugin URI:  asdf
 * Description: Safe file upload.
 * Version:     0.0.0
 * Author:      JJ
 * Author URI:  asdf
 * Text Domain: safe-file-upload
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

add_filter('upload_dir', 'custom_upload_dir');

function custom_upload_dir($dir)
{
  if(isset($_POST["wpcf-file_attachment"]))
  {
    $dir['path'] = WP_CONTENT_DIR . '/uploads/submissions';
    $dir['subdir'] = WP_CONTENT_DIR . '/submissions';
  }

  return $dir;
}

add_filter('wp_handle_upload', 'custom_handle_upload');

function custom_handle_upload($path)
{
  if(isset($_POST["wpcf-file_attachment"]))
  {
    $filename = basename($path['file']);
    $path['url'] = content_url() . '/uploads/submissions?file=' . $filename;
  }

  return $path;
}

function add_image_insert_override($sizes){
  if(isset($_POST["wpcf-file_attachment"]))
  {
    unset( $sizes['thumbnail']);
    unset( $sizes['medium']);
    unset( $sizes['medium_large']);
    unset( $sizes['large']);
    unset( $sizes["1536x1536"]);
    unset( $sizes["thumb-small"]);
    unset( $sizes["thumb-standard"] );
    unset( $sizes["thumb-medium"] );
    unset( $sizes["thumb-large"] );
    unset( $sizes["thumb-xlarge"] );
    unset( $sizes["thumb-xxlarge"] );
  }
}
add_filter('intermediate_image_sizes_advanced', 'add_image_insert_override' );