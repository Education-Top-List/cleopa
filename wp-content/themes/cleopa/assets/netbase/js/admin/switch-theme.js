(function( $ ) {

    $(document).ready(function() {
        var slug = 'install';

        var switchThemeModal = {
            init: function() {
                $('#wpfooter').append('<div id="switch-theme-popup"><p>test</p><a id="popup-yes">yes</a><a id="popup-no">no</a></div>');

                $(document).on('click', '#popup-yes', this.installFramework);
            },
            ajaxCallback: function(response) {
                console.log(response);
                if(typeof response === "object" && typeof response.message !== "undefined"){
                    if(typeof response.url !== "undefined"){
                        // we have an ajax url action to perform.
                        jQuery.post(response.url, response, function(response2) {
                            if(slug === 'install') {
                                slug = 'active';
                                switchThemeModal.installFramework()
                            } else {
                                switchThemeModal.complete_notice()
                            }
                        })
                            .fail(switchThemeModal.ajaxCallback)
                    }
                }else{
                    switchThemeModal.error_notice();
                }
            },
            installFramework: function() {
                console.log(slug);
                console.log('installing');
                jQuery.post(nbt.ajax_url, {
                    action: "nbt_install_framework",
                    wpnonce: nbt.wp_nonce,
                    slug: slug
                }, switchThemeModal.ajaxCallback)
                    .fail(switchThemeModal.ajaxCallback);
            },
            complete_notice: function() {
                console.log('done');
            },
            error_notice: function() {
                console.log('error');
            }
        };

        switchThemeModal.init();
    });

})( jQuery );
