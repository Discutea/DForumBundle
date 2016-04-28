/*
 *
 * Détection ecran tactile
 * 
 */

$(document).ready(function () {
    var isTouchDevice = 'ontouchstart' in document.documentElement;
    if (isTouchDevice === false) {
        $('li[id^=post] ul').hide();
        $('li[id^=post]').hover(function() {
            $('ul', this).show();      
        }, function() { 
            $('ul', this).hide(); 
        });
    };
});