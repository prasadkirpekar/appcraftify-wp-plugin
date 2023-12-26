<?php

/**
 * Plugin Name: AppCraftify
 * Plugin URI: http://wpminers.com/
 * Description: A sample WordPress plugin to implement Vue with tailwind.
 * Author: Hasanuzzaman Shamim
 * Author URI: http://hasanuzzaman.com/
 * Version: 1.0.6
 * Text Domain: AppCraftify
 */
define('APPCRAFTIFY_URL', plugin_dir_url(__FILE__));
define('APPCRAFTIFY_DIR', plugin_dir_path(__FILE__));

define('APPCRAFTIFY_VERSION', '1.0.5');

// This will automatically update, when you run dev or production
define('APPCRAFTIFY_DEVELOPMENT', 'yes');

class AppCraftify {

    var $restRoutes = null;
    var $adminAjax = null;

    public function boot()
    {
        $this->defineConstants();
        $this->loadClasses();
        $this->initClasses();
        $this->registerHooks();
        $this->ActivatePlugin();
        $this->renderMenu();
        $this->disableUpdateNag();
        $this->loadTextDomain();
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
                25
            );
            $submenu['AppCraftify.php']['dashboard'] = array(
                'Dashboard',
                'manage_options',
                'admin.php?page=AppCraftify.php#/',
            );
            $submenu['AppCraftify.php']['contact'] = array(
                'Contact',
                'manage_options',
                'admin.php?page=AppCraftify.php#/contact',
            );
        });
    }

    // function to define global contstants
    public function defineConstants()
    {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        define( 'WOOCOMMERCE_ACTIVE', is_plugin_active( 'woocommerce/woocommerce.php' ));
    }

    /**
     * Main admin Page where the Vue app will be rendered
     * For translatable string localization you may use like this
     * 
     *      add_filter('AppCraftify/frontend_translatable_strings', function($translatable){
     *          $translatable['world'] = __('World', 'AppCraftify');
     *          return $translatable;
     *      }, 10, 1);
     */
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
            'options'=> get_option('AppCraftify_settings')
        ));

        wp_localize_script('AppCraftify-script-boot', 'AppCraftifyAdmin', $AppCraftify);

        echo '<div class="AppCraftify-admin-page" id="AppCraftify_app">
            <router-view></router-view>
        </div>';
    }

    /*
    * NB: text-domain should match exact same as plugin directory name (Plugin Name)
    * WordPress plugin convention: if plugin name is "My Plugin", then text-domain should be "my-plugin"
    * 
    * For PHP you can use __() or _e() function to translate text like this __('My Text', 'AppCraftify')
    * For Vue you can use $t('My Text') to translate text, You must have to localize "My Text" in PHP first
    * Check example in "renderAdminPage" function, how to localize text for Vue in i18n array
    */
    public function loadTextDomain()
    {
        load_plugin_textdomain('AppCraftify', false, basename(dirname(__FILE__)) . '/languages');
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


    /**
     * Activate plugin
     * Migrate DB tables if needed
     */
    public function ActivatePlugin()
    {
        register_activation_hook(__FILE__, function ($newWorkWide) {
            require_once(APPCRAFTIFY_DIR . 'includes/Classes/Activator.php');
            $activator = new \AppCraftify\Classes\Activator();
            $activator->migrateDatabases($newWorkWide);
        });
    }


    /**
     * Register Hooks here
     */
    public function registerHooks()
    {
        $options = get_option('AppCraftify_settings');
        add_action('init', [$this, "initClasses"]);
        if($options['enabled']) add_action('rest_api_init', [$this->restRoutes, "registerRoutes"]);
    }

    /**
     * Initialize classes
     */
    public function initClasses(){
        $this->restRoutes = new \AppCraftify\Classes\Rest\API();
        $this->adminAjax = new \AppCraftify\Classes\Ajax();
    }
}

(new AppCraftify())->boot();



