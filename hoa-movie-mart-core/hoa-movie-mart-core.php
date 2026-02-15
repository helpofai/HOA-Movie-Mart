<?php
/**
 * Plugin Name: HOA Movie Mart Core
 * Description: Core functionality for HOA Movie Mart theme, including Movie Custom Post Type and Taxonomies.
 * Version: 1.0
 * Author: the HelpOfAi team
 * Text Domain: helpofai
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define Constants
define( 'HOA_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'HOA_CORE_URL', plugin_dir_url( __FILE__ ) );

// Include Post Types
require_once HOA_CORE_PATH . 'includes/post-types/movie.php';
require_once HOA_CORE_PATH . 'includes/post-types/report.php';

// Include Taxonomies
require_once HOA_CORE_PATH . 'includes/taxonomies/init.php';

// Include Meta Boxes

require_once HOA_CORE_PATH . 'includes/meta-boxes/movie-details.php';



// Include Custom Widgets

require_once HOA_CORE_PATH . 'includes/widgets/class-hoa-widgets.php';
