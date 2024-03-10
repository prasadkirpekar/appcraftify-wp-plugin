<?php

namespace AppCraftify\Classes;

class Helper
{
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

    static function generateRandomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $apiKey = '';
        $length = 32;
        for ($i = 0; $i < $length; $i++) {
            $apiKey .= $characters[wp_rand(0, strlen($characters) - 1)];
        }
        return $apiKey;
    }
}
?>