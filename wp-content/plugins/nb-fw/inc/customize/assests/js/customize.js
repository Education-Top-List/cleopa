(function( $ ) {

    $(document).ready(function() {
        
        // console.log($('.wp-full-overlay-sidebar-content #customize-theme-controls').html());
        $("<div id='customize-info' class='accordion-section customize-info' style='margin-top: 15px;'><div class='accordion-section-title'><span class='preview-notice preview-notice-load-css'></span><button type='button' class='customize-help-toggle dashicons dashicons-admin-tools' aria-expanded='false'><span class='screen-reader-text'>Help</span></button></div><div class='customize-panel-description customize-panel-setting-load-css' style='display: none; text-align: center;'><button type='button' class='button change-type-load' aria-label='Change type load' rel=''></button></div></div>").insertAfter('.wp-full-overlay-sidebar-content #customize-theme-controls');
        
        loadCssCustomize();

        $('.change-type-load').on( 'click', function(event) {
            event.preventDefault();
            $.ajax({
                type : "post",
                dataType : "json",
                data : {
                    action: "check_load_css",
                    type_current: $(this).attr('rel'),
                },
                url : object_name.ajaxurl,
                context: this,
                beforeSend: function() {
                    spinner('visible');
                    $(this).prop("disabled",true);
                },
                success: function(response) {
                    if(response.success) {
                        changeButton(response);
                    } else {
                        alert(response.alert);
                    }
                    $(this).prop("disabled", false);
                    spinner('hidden');
                },
                error: function( jqXHR, textStatus, errorThrown ) {
                    console.log( 'The following error occured: ' + textStatus, errorThrown );
                }
            });
        } );

        $('button.customize-help-toggle.dashicons.dashicons-admin-tools').on( 'click', function() {
            $( ".customize-panel-setting-load-css" ).slideToggle(200);
        });

    });

    function loadCssCustomize() {
        $.ajax({
            type : "post",
            dataType : "json",
            data : {
                action: "check_load_css",
            },
            url : object_name.ajaxurl,
            context: this,
            beforeSend: function() {
                spinner('visible');
            },
            success: function(response) {
                if(response.success) {
                    changeButton(response);
                } else {
                    alert(response.alert);
                }
                spinner('hidden');
            },
            error: function( jqXHR, textStatus, errorThrown ) {
                console.log( 'The following error occured: ' + textStatus, errorThrown );
            }
        });
    }

    function spinner(visibility) {
        if(visibility=='hidden') {
            $('.wp-full-overlay #customize-controls #customize-header-actions .spinner').removeAttr('style');
        } else {
            $('.wp-full-overlay #customize-controls #customize-header-actions .spinner').css({
                'visibility': visibility
            });
        }
    }

    function changeButton(rel) {
        if(rel.result==-1) {
            alert('There was an error');
        } else {
            $('.preview-notice-load-css').html(rel.status);
            $('.change-type-load').text(rel.button);
            $('.change-type-load').attr({
                'rel': rel.result,
            });
        }
    }

})( jQuery );