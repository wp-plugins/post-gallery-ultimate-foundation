<?php
/*
Plugin Name: Post Gallery Ultimate Foundation
Description: Extend the Post Gallery Ultimate plugin to use the Foundation Framework instead of the default styles.
Version: 1.0
Author: Jeff Bullins
Author URI: http://www.thinklandingpages.com
*/


//adapted from https://github.com/easydigitaldownloads/EDD-Extension-Boilerplate/blob/master/plugin-name/plugin-name.php
//include_once 'custom-post-type.php';  

if( !defined( 'ABSPATH' ) ) exit;
if( !class_exists( 'PGU_Foundation' ) ) {
    /**
     * Main EDD_Plugin_Name class
     *
     * @since       1.0.0
     */
    class PGU_Foundation {
    
    	private static $instance;
        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true PGU_Foundation
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new PGU_Foundation();
                self::$instance->setup_constants();
                //self::$instance->includes();
                //self::$instance->load_textdomain();
                self::$instance->hooks();
            }
            return self::$instance;
        }
        
        private function hooks(){
        	add_filter('pgu_redirect_to_gallery_page_filter', array( $this, 'add_foundation_css' ));
        	add_action('wp_footer', array( $this, 'enqueue_footer_scripts'));
        	add_action('wp_enqueue_scripts', array( $this, 'enqueue_header_scripts'), 15);
        	
        }
        
        public function add_foundation_css(){
        	add_action( 'get_header', array( $this, 'onWpLoaded' ), 0 );
        	return PGU_FOUNDATION_DIR . 'template/foundationTemplate.php';
        }
        
        //head rewrite adopted from wordpress-bootstrap-css/tags/3.2.0-4/src/icwp-processor-css.php
	function onWpLoaded(){
		 ob_start( array( $this, 'onOutputBufferFlush' ) );
	}
	
	public function onOutputBufferFlush( $sContent ) {
	                return $this->rewriteHead( $sContent );
	}
	protected function rewriteHead( $sContents ) {
		$sIncludeLink = plugin_dir_url(__FILE__)."foundation/css/foundation.css";
		$sReplace = '${1}';
		$sReplace .= "\n".'<link rel="stylesheet" type="text/css" href="'.$sIncludeLink.'" />';
		$sRegExp = "/(<\bhead\b([^>]*)>)/i";
		return preg_replace( $sRegExp, $sReplace, $sContents, 1 );
	}
        
        
                /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'PGU_FOUNDATION_VER', '1.0.0' );
            // Plugin path
            define( 'PGU_FOUNDATION_DIR', plugin_dir_path( __FILE__ ) );
            // Plugin URL
            define( 'PGU_FOUNDATION_URL', plugin_dir_url( __FILE__ ) );
        }
        
        
         /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
        	
            // Include scripts
            //require_once PGU_FOUNDATION_DIR . 'includes/scripts.php';
            //require_once PGU_FOUNDATION_DIR . 'includes/functions.php';
            /**
             * @todo        The following files are not included in the boilerplate, but
             *              the referenced locations are listed for the purpose of ensuring
             *              path standardization in PGU extensions. Uncomment any that are
             *              relevant to your extension, and remove the rest.
             */
            // require_once PGU_FOUNDATION_DIR . 'includes/shortcodes.php';
            // require_once PGU_FOUNDATION_DIR . 'includes/widgets.php';
        }
        
        
        
        function enqueue_footer_scripts(){
		wp_enqueue_script('post-gallery-foundation-js', plugin_dir_url(__FILE__)."foundation/js/foundation/foundation.js");
		wp_enqueue_script('post-gallery-foundation-custom-js', plugin_dir_url(__FILE__).'js/foundationFooter.js');
	}
	
	function enqueue_header_scripts(){
		wp_enqueue_script("modernizr", plugin_dir_url(__FILE__)."foundation/js/vendor/modernizr.js");
	}

    
    } //end class PGU_Foundation
} //end PGU_Foundation class exist check



function PGU_Foundation_load() {

    if( ! class_exists( 'PostGalleryCustomPostType' ) ) {
        if( ! class_exists( 'PGU_Extension_Activation' ) ) {
            require_once 'includes/class.extension-activation.php';
        }
        $activation = new PGU_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
        $activation = $activation->run();
        return PGU_Foundation::instance();
    } else {
        return PGU_Foundation::instance();
    }

	//return PGU_Foundation::instance();
}

add_action( 'plugins_loaded', 'PGU_Foundation_load' );


function pgu_foundation_activate() {
	//$postGalleryCustomPostType = new PostGalleryCustomPostType();
	//$postGalleryCustomPostType->create_post_type();
	//global $wp_rewrite;
	//$wp_rewrite->flush_rules();
}


register_activation_hook( __FILE__, 'pgu_foundation_activate');

 