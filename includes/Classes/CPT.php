<?php

namespace AppCraftify\Classes;

class CPT
{
    function __construct()
    {
        add_action('init', [$this, 'createPostType']);
    }

    function createPostType()
    {

        register_post_type(
            'apperr_address',
            // CPT Options
            array(
                'labels' => array(
                    'name' => esc_html__('User Addresses', 'appcraftify'),
                    'singular_name' => esc_html__('User Address', 'appcraftify'),
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'apperr_address'),
                'show_in_rest' => false,
                'supports' => array('custom-fields'),

            )
        );
    }

}