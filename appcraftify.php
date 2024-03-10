<?php

/**
 * Plugin Name: AppCraftify
 * Plugin URI: https://AppCraftify.com/
 * Description: AppCraftify is an app builder plugin for WordPress.
 * Author: Prasad Kirpekar
 * Author URI: mailto:prasadkirpekar@outlook.com
 * Version: 0.1.0
 * Text Domain: AppCraftify
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
define('APPCRAFTIFY_URL', plugin_dir_url(__FILE__));
define('APPCRAFTIFY_DIR', plugin_dir_path(__FILE__));

define('APPCRAFTIFY_VERSION', '1.0.5');

// This will automatically update, when you run dev or production
define('APPCRAFTIFY_DEVELOPMENT', 'yes');

class AppCraftify {

    var $restRoutes = null;
    var $adminAjax = null;
    var $helper = null;
    var $cors =  null;
    var $cpt = null;
    var $auth = null;

    public function boot()
    {
        $this->defineConstants();
        $this->loadClasses();
        $this->initClasses();
        $this->registerHooks();
        $this->ActivatePlugin();
        $this->renderMenu();
        $this->disableUpdateNag();
    }

    public function loadClasses()
    {
        require APPCRAFTIFY_DIR . 'includes/autoload.php';
    }

    public function renderMenu()
    {
        add_action('admin_menu', function () {
            if (!current_user_can('manage_options')) {
                return;
            }
            global $submenu;
            add_menu_page(
                'AppCraftify',
                'AppCraftify',
                'manage_options',
                'AppCraftify.php',
                array($this, 'renderAdminPage'),
                'dashicons-editor-code',
                90
            );
        });
    }

    // function to define global contstants
    public function defineConstants()
    {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        define( 'APPCRAFTIFY_WOOCOMMERCE_ACTIVE', is_plugin_active( 'woocommerce/woocommerce.php' ));
    }

    public function renderAdminPage()
    {
        $loadAssets = new \AppCraftify\Classes\LoadAssets();
        $loadAssets->admin();

        $translatable = apply_filters('AppCraftify/frontend_translatable_strings', array(
            'hello' => __('Hello', 'AppCraftify'),
        ));

        $AppCraftify = apply_filters('AppCraftify/admin_app_vars', array(
            'assets_url' => APPCRAFTIFY_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php'),
            'i18n' => $translatable,
            'AppCraftify_nonce' => wp_create_nonce('AppCraftify_nonce'),
            'options'=> get_option('AppCraftify_settings'),
            'isJWTInstalled' => $this->helper->authPluginIsPluginInstalled('jwt-authentication-for-wp-rest-api/jwt-auth.php') && defined('JWT_AUTH_SECRET_KEY'),
            'isWooInstalled' => $this->helper->authPluginIsPluginInstalled('woocommerce/woocommerce.php'),
            'siteUrl'=> site_url()
        ));

        wp_localize_script('AppCraftify-script-boot', 'AppCraftifyAdmin', $AppCraftify);

        echo '<div class="AppCraftify-admin-page" id="AppCraftify_app">
            <router-view></router-view>
        </div>';
    }

    /**
     * Disable update nag for the dashboard area
     */
    public function disableUpdateNag()
    {
        add_action('admin_init', function () {
            $disablePages = [
                'AppCraftify.php',
            ];

            if (isset($_GET['page']) && in_array($_GET['page'], $disablePages)) {
                remove_all_actions('admin_notices');
            }
        }, 20);
    }


    public function ActivatePlugin()
    {
        register_activation_hook(__FILE__, function ($newWorkWide) {
            require_once(APPCRAFTIFY_DIR . 'includes/Classes/Activator.php');
            $activator = new \AppCraftify\Classes\Activator();
            $activator->initSettings();
        });
    }


    /**
     * Register Hooks here
     */
    public function registerHooks()
    {
        $options = get_option('AppCraftify_settings');
        add_action('init', [$this, "initClasses"]);
        if($options['enabled']){
            add_action('rest_api_init', [$this->restRoutes, "registerRoutes"]);
            add_filter('rest_post_query', [$this->restRoutes, "wpPostOnlyTitles"], 10, 2);
        } 
    }

    /**
     * Initialize classes
     */
    public function initClasses(){
        $this->restRoutes = new \AppCraftify\Classes\Rest\API();
        $this->adminAjax = new \AppCraftify\Classes\Ajax();
        $this->helper = new \AppCraftify\Classes\Helper();
        $this->cors = new \AppCraftify\Classes\CORS();
        $this->cpt = new \AppCraftify\Classes\CPT();
        $this->auth = new \AppCraftify\Classes\UserAuth();
    }

}


if ( ! function_exists( 'app_fs' ) ) {
    // Create a helper function for easy SDK access.
    function app_fs() {
        global $app_fs;

        if ( ! isset( $app_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $app_fs = fs_dynamic_init( array(
                'id'                  => '14783',
                'slug'                => 'appcraftify',
                'type'                => 'plugin',
                'public_key'          => 'pk_723a49e434129730f0032d536e99d',
                'is_premium'          => true,
                'premium_suffix'      => 'Pro',
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'menu'                => array(
                    'slug'           => 'AppCraftify.php',
                    'support'        => false,
                ),
            ) );
        }

        return $app_fs;
    }

    // Init Freemius.
    app_fs();
    // Signal that SDK was initiated.
    do_action( 'app_fs_loaded' );
    (new AppCraftify())->boot();
}



