<?php

namespace oneTheme;

require_once get_template_directory() . '/lib/module.class.php';

class AdminTheme extends Module {
    public function init(){
        wp_admin_css_color(
        'OCG',
        __('OCG'),
        admin_url("../wp-content/themes/one-theme/lib/modules/adminTheme/css/admin.css"),
        array('#335457', '#c0e2e3', '#f2e2b3', '#f9f3e0')
        );
        
        $this->delete();
    }
}

new AdminTheme();
