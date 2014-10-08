/* main.js */
if (matchMedia) {
    var mq = window.matchMedia("(min-width: 768px)");
}

//mouseover for menu
jQuery(function() {
    jQuery('ul.nav li.dropdown').hover(function() {
        if(mq.matches){
            jQuery('.dropdown-menu', this).fadeIn();
        }
    }, function() {
        if(mq.matches) {
            jQuery('.dropdown-menu', this).fadeOut('fast');
        }
    }); //hover
}); //jQuery
