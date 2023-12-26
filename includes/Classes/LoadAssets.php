<?php

namespace AppCraftify\Classes;

class LoadAssets
{
    public function admin()
    {
        //Vite::enqueueScript('AppCraftify-script-boot', 'admin/start.js', array('jquery'), APPCRAFTIFY_VERSION, true);
        if (defined('APPCRAFTIFY_DEVELOPMENT') && APPCRAFTIFY_DEVELOPMENT !== 'yes') {
            wp_enqueue_script('AppCraftify-script-boot', APPCRAFTIFY_URL . 'assets/js/start.js', array('jquery'), APPCRAFTIFY_VERSION, false);
        } else {
            add_filter('script_loader_tag', function ($tag, $handle, $src) {
                return $this->addModuleToScript($tag, $handle, $src);
            }, 10, 3);
            wp_enqueue_script('AppCraftify-script-boot', 'http://localhost:8880/' . 'src/admin/start.js', array('jquery'), APPCRAFTIFY_VERSION, true);
        }
    }

    private function addModuleToScript($tag, $handle, $src)
    {
        
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        
        return $tag;
    }
  
}
