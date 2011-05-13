<?php
class SCRM_PP {
    public static $option_key = 'scrm_pp';
    
    /**
     * Static constructor
     */
    function init() {
        add_action( 'scrm_options_screen', array( __CLASS__, 'screen' ) );
        add_action( 'scrm_options_screen_updated', array( __CLASS__, 'screen_update' ) );
        add_action( 'scrm_pp_form', array( __CLASS__, 'process' ) );
        add_shortcode( 'scrm_pp', array( __CLASS__, 'pp' ) );
        add_action( 'login_form', array( __CLASS__, 'redirect_to' ) );
        add_filter( 'scrm_force_redirect_url', array( __CLASS__, 'admin_redirect_to' ) );
    }
    
    /**
     * Options screen
     */
    function screen() {
        $vars['path'] = SCRM_PP_ROOT . '/includes/templates/';
        $vars['permalink'] = get_option( self::$option_key, null );
        template_render( 'info', $vars );
    }
    
    /**
     * Options screen
     */
    function screen_update() {
        if( isset( $_POST['scrm_pp_nonce'] ) && wp_verify_nonce( $_POST['scrm_pp_nonce'], 'scrm_pp' ) ) {
            if( isset( $_POST['scrm_pp'] ) && !empty( $_POST['scrm_pp'] ) ) {
                $permalink = esc_url_raw( $_POST['scrm_pp']['permalink'] );
                update_option( self::$option_key, $permalink );
            }
        }
    }
    
    /**
     * WP-Admin Profile Page redirect handler
     */
    function admin_redirect_to( $url ) {
        $pp_permalink = get_option( self::$option_key, null );
        if( $pp_permalink )
            $url = $pp_permalink;
        return $url;
    }
    
    /**
     * Public Profile Page redirect handler
     */
    function redirect_to() {
        global $redirect_to;
        
        $pp_permalink = get_option( self::$option_key, null );
        if( $pp_permalink && !isset( $_GET['redirect_to'] ) ) {
            $redirect_to = $pp_permalink;
        }
    }
    
    /**
     * Public Profile Page handler
     */
    function pp( $atts, $content = null ) {
        $to_hide = shortcode_atts( array( 'hide' => null ), $atts );
        if( $to_hide['hide'] )
            $to_hide = explode( ',', $to_hide['hide'] );
        else
            $to_hide = array();
        
        $vars['path'] = SCRM_PP_ROOT . '/includes/templates/';
        $vars['to_hide'] = $to_hide;
        $vars['content'] = $content;
        template_render( 'profile_page', $vars );
    }
    
    /**
     * Public page form processor
     */
    function process() {
        // Cleanup old logs
        delete_transient( 'scrm_pp_updated_id' );
        
        if( isset( $_POST['scrm_pp_form_nonce'] ) && wp_verify_nonce( $_POST['scrm_pp_form_nonce'], 'scrm_pp_form' ) ) {
            $userdata = wp_get_current_user();
            $new_userdata = array();
            if( !isset( $_POST['user_id'] ) || ( $_POST['user_id'] != $userdata->ID ) )
                return;
            // Load initial data
            $new_userdata = get_object_vars( $userdata );
            
            if( isset( $_POST['pass1'] ) && isset( $_POST['pass2'] ) )
                if( $_POST['pass1'] != '' && ( $_POST['pass1'] == $_POST['pass2'] ) )
                    $new_userdata['user_pass'] = $_POST['pass1'];
                else // Skip password update if user doesn't want that
                    unset( $new_userdata['user_pass'] );
            
            // Form field names to check
            $map_names = array(
                'first_name' => 'first_name',
                'last_name' => 'last_name',
                'url' => 'user_url',
                'description' => 'description'
            );
            // Map contact method keys too
            $contact_keys = _wp_get_user_contactmethods( $userdata );
            foreach( $contact_keys as $name => $desc )
                $map_names[$name] = $name;
            
            // Fill the mapped names
            foreach( $map_names as $form_key => $userdata_key )
                if( isset( $_POST[$form_key] ) )
                    $new_userdata[$userdata_key] = $_POST[$form_key];
            
            set_transient( 'scrm_pp_updated_id', wp_update_user( $new_userdata ) );
        }
     }
}
?>