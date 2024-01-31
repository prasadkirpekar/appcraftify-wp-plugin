<?php

namespace AppCraftify\Classes;

class UserAuth{

    function __construct()
    {
        if(APPCRAFTIFY_WOOCOMMERCE_ACTIVE){
            add_action('template_redirect', [$this, 'performLogin']);
        }
    }

    function performLogin(){
        if(is_user_logged_in()){
            return;
        }
        if(!is_checkout()) return;
        echo $_REQUEST['magic_code'];
        $code = $_REQUEST['magic_code'];
        $users = get_users(array(
            'meta_key' => 'appcraftify_magic_code',
            'meta_value' => $code
        ));
        if(count($users)!=1) return;
        $user = $users[0];
        if (user_can($user->ID, 'manage_options')) return;
        if ($user) {
            wp_set_auth_cookie($user->ID);
            delete_user_meta($user->ID, "appcraftify_magic_code");
            wp_redirect(remove_query_arg('magic_code'));
            exit;
        } else {
            wp_die('User not found.');
        }
    }

}