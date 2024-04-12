<?php

namespace AppCraftify\Classes;

class CORS{

    private $filesystem;

    function __construct()
    {
        add_action('rest_api_init', [$this, 'add_cors_headers']);
        if ( null === $this->filesystem ) {
			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}

			$this->filesystem = $wp_filesystem;
		}
    }

    

    function add_cors_headers() {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
			$sanitized_origin = sanitize_url($_SERVER['HTTP_ORIGIN']);
            header("Access-Control-Allow-Origin: {$sanitized_origin}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        header('Access-Control-Allow-Headers: Content-Type, api_key');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            status_header(200);
            exit();
        }
    }

    public function modifyHtaccess() {
		$lines = array(
			'<IfModule mod_headers.c>',
			'<FilesMatch "\.(avifs?|bmp|cur|gif|ico|jpe?g|jxl|a?png|svgz?|webp)$">',
			'Header set Access-Control-Allow-Origin: http://app.appcraftify.com',
			'Header set Access-Control-Allow-Origin: https://app.appcraftify.com env=HTTPS',
			'</FilesMatch>',
			'</IfModule>',
		);
		$this->write( $lines );
	}

    private function write( array $lines ): void {
		// Ensure get_home_path() is declared.
		if ( ! function_exists( 'get_home_path' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		if ( ! function_exists( 'insert_with_markers' ) || ! function_exists( 'got_mod_rewrite' ) ) {
			require_once ABSPATH . 'wp-admin/includes/misc.php';
		}

		$htaccess_file = get_home_path() . '.htaccess';

		if ( got_mod_rewrite() ) {
			insert_with_markers( $htaccess_file, "APPCRAFTIFY", $lines );
		}
	}

    public function restore() {
		$lines = array( '' );
		$this->write( $lines );
	}

}