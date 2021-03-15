<?php

// put this file in wp-content/uploads/submissions

define('WP_USE_THEMES', false);
require('../../../wp-load.php'); //location of this file may be different 

function return_404()
{
  // 1. Ensure `is_*` functions work
  global $wp_query;
  $wp_query->set_404();

  // 2. Fix HTML title
  add_action('wp_title', function () {
    return '404: Not Found';
  }, 9999);

  // 3. Throw 404
  status_header(404);
  nocache_headers();

  // 4. Show 404 template
  require get_404_template();

  // 5. Stop execution
  exit;
}

function handle_file_download()
{
  //  prevent_directory_traversal
  // https://en.wikipedia.org/wiki/Directory_traversal_attack
  // https://stackoverflow.com/questions/4205141/preventing-directory-traversal-in-php-but-allowing-paths

  $basepath = WP_CONTENT_DIR . "/uploads/submissions";
  $realBase = realpath($basepath);

  $filepath = $basepath . '/' . $_GET['file'];
  $realUserPath = realpath($filepath);

  if ($realUserPath === false || strcmp($realUserPath, $realBase) < 0 || strpos($realUserPath, $realBase . DIRECTORY_SEPARATOR) !== 0) {
    return_404();
  }
  if (get_current_user_id()) {
    //user has valid WP login session
    header("Content-Type: " . mime_content_type($realUserPath));
    readfile($realUserPath);
  } else {
    // website guest
    return_404();
  }
}

handle_file_download();

exit; //just because