<?php

namespace AppCraftify\Classes;

use AppCraftify\Classes\CORS;

class Ajax{

    var $helper = null;

    //constructor
    public function __construct()
    {
        add_action('wp_ajax_AppCraftify_saveSettings', [$this, 'saveSettings']);
        add_action('wp_ajax_AppCraftify_getSettings', [$this, 'getSettings']);
        add_action('wp_ajax_AppCraftify_installAuthPluginInstall', [$this, 'authPluginReplacePlugin']);
        add_action('wp_ajax_AppCraftify_isJWTAuthSecretKeyDefined', [$this, 'isJWTAuthSecretKeyDefined']);
        add_action('wp_ajax_AppCraftify_isSiteLinked', [$this, 'isSiteLinked']);
        add_action('wp_ajax_AppCraftify_isAppBuilt', [$this, 'isAppBuilt']);
        $this->helper = new \AppCraftify\Classes\Helper();
    }

    public function saveSettings()
    {
        $nonce = sanitize_text_field($_POST['nonce']);
        if (!wp_verify_nonce($nonce, 'AppCraftify_nonce')) {
            die('Busted!');
        }
        $settings['enabled'] = filter_var($_POST['settings']['enabled'], FILTER_VALIDATE_BOOLEAN);
        $settings['isAppBuilt'] = filter_var($_POST['settings']['isAppBuilt'], FILTER_VALIDATE_BOOLEAN);
        $settings['isSiteLinked'] = filter_var($_POST['settings']['isSiteLinked'], FILTER_VALIDATE_BOOLEAN);
        $settings['apiKey'] = sanitize_textarea_field($_POST['settings']['apiKey']);
        $settings['enableCORS'] = filter_var($_POST['settings']['enableCORS'], FILTER_VALIDATE_BOOLEAN);
        update_option('AppCraftify_settings', $settings);
        if($settings['enableCORS']) {
            (new CORS())->modifyHtaccess();
        } else {
            (new CORS())->restore();
        }
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
        
        if ( $this->helper->authPluginIsPluginInstalled( $plugin_slug ) ) {
        
            $this->helper->authPluginUpgradePlugin( $plugin_slug );
            $installed = true;
        } else {
        
            $installed = $this->helper->authPluginInstallPlugin( $plugin_zip );
        }
        ob_clean();
        ob_start();
        if ( !is_wp_error( $installed ) && $installed ) {
            wp_send_json_success();
        } else {
            ob_flush();
            wp_send_json_error();
        }
    }
     
    //function to check if JWT_AUTH_SECRET_KEY is constant defined or not
    function isJWTAuthSecretKeyDefined() {
        if (!defined('JWT_AUTH_SECRET_KEY')) {
            wp_send_json_success(false);
        } else {
            wp_send_json_success(true);
        }
    }

    //function to check if isSiteLinked is true
    function isSiteLinked() {
        $settings = get_option('AppCraftify_settings');
        if ($settings['isSiteLinked']) {
            wp_send_json_success(true);
        } else {
            wp_send_json_success(false);
        }
    }

    //function to check if isAppBuilt is true
    function isAppBuilt() {
        $settings = get_option('AppCraftify_settings');
        if ($settings['isAppBuilt']) {
            wp_send_json_success(true);
        } else {
            wp_send_json_success(false);
        }
    }

}

?>