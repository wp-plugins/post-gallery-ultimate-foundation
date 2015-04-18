<?php
/**
 * Activation handler
 *
 * @package     PGU\ActivationHandler
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * PGU Extension Activation Handler Class
 *
 * @since       1.0.0
 */
class PGU_Extension_Activation {

    public $plugin_name, $plugin_path, $plugin_file, $has_pgu, $pgu_base;

    /**
     * Setup the activation class
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function __construct( $plugin_path, $plugin_file ) {
        // We need plugin.php!
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $plugins = get_plugins();

        // Set plugin directory
        $plugin_path = array_filter( explode( '/', $plugin_path ) );
        $this->plugin_path = end( $plugin_path );

        // Set plugin file
        $this->plugin_file = $plugin_file;

        // Set plugin name
        if( isset( $plugins[$this->plugin_path . '/' . $this->plugin_file]['Name'] ) ) {
            $this->plugin_name = str_replace( 'Post Gallery Ultimate - ', '', $plugins[$this->plugin_path . '/' . $this->plugin_file]['Name'] );
        } else {
            $this->plugin_name = __( 'This plugin', 'post-gallery-ultimate' );
        }

        // Is PGU installed?
        foreach( $plugins as $plugin_path => $plugin ) {
            if( $plugin['Name'] == 'Post Gallery Ultimate' ) {
                $this->has_pgu = true;
                $this->pgu_base = $plugin_path;
                break;
            }
        }
    }


    /**
     * Process plugin deactivation
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function run() {
        // Display notice
        add_action( 'admin_notices', array( $this, 'missing_pgu_notice' ) );
    }


    /**
     * Display notice if PGU isn't installed
     *
     * @access      public
     * @since       1.0.0
     * @return      string The notice to display
     */
    public function missing_pgu_notice() {
        if( $this->has_pgu ) {
            $url  = esc_url( wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $this->pgu_base ), 'activate-plugin_' . $this->pgu_base ) );
            $link = '<a href="' . $url . '">' . __( 'activate it', 'pgu-extension-activation' ) . '</a>';
        } else {
            $url  = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=post-gallery-ultimate' ), 'install-plugin_easy-digital-downloads' ) );
            $link = '<a href="' . $url . '">' . __( 'install it', 'pgu-extension-activation' ) . '</a>';
        }
        
        echo '<div class="error"><p>' . $this->plugin_name . sprintf( __( ' requires Post Gallery Ultimate! Please %s to continue!', 'pgu-extension-activation' ), $link ) . '</p></div>';
    }
}
