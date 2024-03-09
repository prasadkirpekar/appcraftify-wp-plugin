<?php

namespace AppCraftify\Classes;

if (!defined('ABSPATH')) {
    exit;
}

use AppCraftify\Classes\Helper;

use AppCraftify\Classes\CORS;
/**
 * Ajax Handler Class
 * @since 1.0.0
 */
class Activator
{
    function initSettings(){
        $settings = array();
        $settings['isAppBuilt'] = false;
        $settings['isSiteLinked'] = false;
        $settings['apiKey'] = Helper::generateRandomString();
        $settings['enabled'] = true;
        add_option('AppCraftify_settings', $settings);
        (new CORS())->modifyHtaccess();
    }

    function deactivateRestore(){
        (new CORS())->restore();
    }

}
