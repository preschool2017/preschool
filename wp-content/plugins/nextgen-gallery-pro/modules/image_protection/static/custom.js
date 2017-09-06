(function($) {
    var protect_image = function(event){
        var target = event.target || event.srcElement;
        target = $(target);
        if (target.data('ngg-protect')             // image is directly 'protected'
            ||  target.parents('a').data('ngg-protect') // parent anchor is 'protected'
            ||  target.attr('id') == 'fancybox-img'    // Fancybox
            ||  target.attr('id') == 'TB_Image'        // Thickbox
            ||  target.attr('id') == 'shTopImg'        // Shutter, Shutter 2
            ||  target.attr('id') == 'lightbox-image'  // 'Lightbox'
            ||  target.hasClass('highslide-image')    // Highslide
            ||  target.parents('.ngg-albumoverview').length == 1
            ||  target.parents('.ngg-pro-album').length == 1
            ||  photocrati_image_protection_global.enabled == '1')
        {
            event.preventDefault();
        }
    };

    $(function(){
        $('a,img,picture,figure,figcaption').each(function () {
        	try {
        		$(this).pressure({
		          endDeepPress: protect_image,
		          startDeepPress: protect_image
        		});
        	}
        	catch (ex) { }
        });
    });

    $(document).bind('contextmenu dragstart', protect_image);
}(jQuery));
