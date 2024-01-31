<?php

namespace AppCraftify\Classes;

if (!defined('ABSPATH')) {
    exit;
}

use AppCraftify\Classes\Helper;

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
        return add_option('AppCraftify_settings', $settings);
    }

    //random alphanumeric 16 letter api key generator
    
}
