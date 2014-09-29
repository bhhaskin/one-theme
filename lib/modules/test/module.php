<?php
namespace oneTheme;

require_once get_template_directory() . '/lib/module.class.php';

class Test extends Module {
    use assetManager;

    public function init(){
        echo "I LIVE!";
        $this->loadAssets();
    }
}


new Test();
