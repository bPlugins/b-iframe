<?php
/**
 * Plugin Name: iFrame – Responsive Embeds for Videos, Maps, Websites & Docs
 * Description: Responsive iframe embedding for videos, live website, and more..
 * Version: 1.1.0
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: b-iframe
 * Requires at least: 6.5
 * Tested up to: 7.1
 * Requires PHP: 7.4
 */
// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }
 
// Constant
define( 'BIFRM_VERSION', '1.1.0' );
define( 'BIFRM_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'BIFRM_DIR_PATH', plugin_dir_path( __FILE__ ) );

// Freemius SDK — usage tracking and support, only after the user opts in.
if ( ! function_exists( 'bi_fs' ) ) {
    // Create a helper function for easy SDK access.
    function bi_fs() {
        global $bi_fs;

        if ( ! isset( $bi_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';

            $bi_fs = fs_dynamic_init( array(
                'id'                  => '38487',
                'slug'                => 'b-iframe',
                'type'                => 'plugin',
                'public_key'          => 'pk_6f5f1f8bfd89ff4a6dbba9ff7ea97',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'is_org_compliant'    => true,
                'menu'                => array(
                    'slug'           => 'bifrm_demo_page',
                    'first-path'     => 'edit.php?post_type=b-iframe&page=bifrm_demo_page',
                    'account'        => false,
                    'support'        => false,
                    'contact'        => false,
                    'parent'         => array(
                        'slug' => 'edit.php?post_type=b-iframe',
                    ),
                ),
            ) );
        }

        return $bi_fs;
    }

    // Init Freemius.
    bi_fs();
    // Signal that SDK was initiated.
    do_action( 'bi_fs_loaded' );
}

// Includes
require_once BIFRM_DIR_PATH . 'includes/Converter.php';
require_once BIFRM_DIR_PATH . 'includes/FrameCheck.php';
require_once BIFRM_DIR_PATH . 'includes/ShortCode.php';
require_once BIFRM_DIR_PATH . 'includes/PostType.php';

if( !class_exists( 'BIFRMPlugin' ) ){
	class BIFRMPlugin {
		function __construct(){
			add_action( 'init', [$this, 'onInit'] );
			add_action( 'enqueue_block_editor_assets', [$this, 'setTranslations'] );
		}

		function onInit() {
			register_block_type( __DIR__ . '/build' );
		}

		function setTranslations() {
			wp_set_script_translations( 'bifrm-iframe-editor-script', 'b-iframe', BIFRM_DIR_PATH . '/languages' );
			wp_set_script_translations( 'bifrm-iframe-view-script', 'b-iframe', BIFRM_DIR_PATH . '/languages' );
		}
	}
	new BIFRMPlugin();
}