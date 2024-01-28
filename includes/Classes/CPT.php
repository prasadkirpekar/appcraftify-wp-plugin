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
                    'name' => __('User Addresses'),
                    'singular_name' => __('User Address')
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