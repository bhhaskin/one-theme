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

//Button Rollover 

jQuery(function() {
    jQuery('.rollover').hover(function() {
        var currentImg = jQuery(this).attr('src');
        jQuery(this).attr('src', jQuery(this).attr('hover'));
        jQuery(this).attr('hover', currentImg);
    }, function() {
        var currentImg = jQuery(this).attr('src');
        jQuery(this).attr('src', jQuery(this).attr('hover'));
        jQuery(this).attr('hover', currentImg);
    });
});


jQuery(document).ready(function() {
 jQuery(".various").fancybox({
   maxWidth	: 800,
   maxHeight	: 600,
   fitToView	: false,
   width		: '70%',
   height		: '70%',
   autoSize	: false,
   closeClick	: false,
   openEffect	: 'none',
   closeEffect	: 'none'
 });
});
