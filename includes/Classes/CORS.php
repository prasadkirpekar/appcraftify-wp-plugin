<?php

namespace AppCraftify\Classes;

class CORS{

    function __construct()
    {
        add_action('rest_api_init', 'add_cors_headers');
    }

    

    function add_cors_headers() {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control-Allow-Headers may need to include more headers based on your requirements
        header('Access-Control-Allow-Headers: Content-Type, api_key');
        
        // Access-Control-Allow-Methods may need to include more methods based on your requirements
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

        // If the request is an OPTIONS request, exit early with a 200 status for preflight requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            status_header(200);
            exit();
        }
    }
}