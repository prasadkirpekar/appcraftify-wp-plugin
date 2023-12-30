<?php

namespace AppCraftify\Classes;

if (!defined('ABSPATH')) {
    exit;
}

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
        $settings['apiKey'] = $this->generateApiKey();
        $settings['enabled'] = true;
        return add_option('AppCraftify_settings', $settings);
    }

    //random alphanumeric 16 letter api key generator
    function generateApiKey() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $apiKey = '';
        $length = 32;

        for ($i = 0; $i < $length; $i++) {
            $apiKey .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $apiKey;
    }
}
