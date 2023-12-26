<?php
namespace AppCraftify\Classes;

class Ajax{

    //constructor
    public function __construct()
    {
        add_action('wp_ajax_AppCraftify_saveSettings', [$this, 'saveSettings']);
        add_action('wp_ajax_AppCraftify_getSettings', [$this, 'getSettings']);
        add_action('wp_ajax_AppCraftify_installAuthPluginInstall', [$this, 'authPluginReplacePlugin']);
    }

    public function saveSettings()
    {
        $nonce = $_POST['nonce'];
        if (!wp_verify_nonce($nonce, 'AppCraftify_nonce')) {
            die('Busted!');
        }
        $settings = $_POST['settings'];
        $settings['enabled'] = filter_var($settings['enabled'], FILTER_VALIDATE_BOOLEAN);
        $settings['apiKey'] = sanitize_textarea_field($settings['apiKey']);
        update_option('AppCraftify_settings', $settings);
        wp_send_json_success($settings);
    }

    public function getSettings()
    {
        $settings = get_option('AppCraftify_settings');
        wp_send_json_success($settings);
    }

    function authPluginReplacePlugin() {
        
        $plugin_slug = 'jwt-authentication-for-wp-rest-api/jwt-auth.php';
        
        $plugin_zip = 'https://downloads.wordpress.org/plugin/jwt-authentication-for-wp-rest-api.latest-stable.zip';
        
        if ( $this->authPluginIsPluginInstalled( $plugin_slug ) ) {
        
        $this->authPluginUpgradePlugin( $plugin_slug );
        $installed = true;
        } else {
        
        $installed = $this->authPluginInstallPlugin( $plugin_zip );
        }
        
        if ( !is_wp_error( $installed ) && $installed ) {
        
        $activate = activate_plugin( $plugin_slug );
        echo 1;
        } else {
            echo 0;
        }
    }
     
    function authPluginIsPluginInstalled( $slug ) {
        if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();
        
        if ( !empty( $all_plugins[$slug] ) ) {
        return true;
        } else {
        return false;
        }
    }
   
    function authPluginInstallPlugin( $plugin_zip ) {
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        wp_cache_flush();
        
        $upgrader = new \Plugin_Upgrader();
        $installed = $upgrader->install( $plugin_zip );
    
        return $installed;
    }
   
    function authPluginUpgradePlugin( $plugin_slug ) {
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        wp_cache_flush();
        
        $upgrader = new \Plugin_Upgrader();
        $upgraded = $upgrader->upgrade( $plugin_slug );
    
        return $upgraded;
    }

}

?>