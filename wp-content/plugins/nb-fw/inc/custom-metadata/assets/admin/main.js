(function ($) {

    $(document).ready(function () {
        var $globalSetting = $('#nbcore_global_setting');

        var $elmTab = $globalSetting.closest('.cmb-tabs').find('.cmb-tab-item').not('.cmb-tab-global');
        var $elmBox = $globalSetting.closest('#addtag, #edittag').find('.cmb-row').not('.cmb2-id-nbcore-global-setting');

        if($elmTab.length) {
            toogleTabs($globalSetting, $elmTab);
        } else if($elmBox.length) {
            toggleSettings($globalSetting, $elmBox);
        }

        $globalSetting.on('click', function () {
            if ($elmTab.length) {
                toogleTabs($(this), $elmTab);
            } else if ($elmBox.length) {
                toggleSettings($(this), $elmBox);
            }
        });
    });

    var toggleSettings = function (trigger, elm) {
        if (!trigger.is(':checked')) {
            // checkDepen(elm);
            $.each(elm, function (k, v) {
                $(v).find('input, select').attr('disabled', 'disabled');
                $(v).addClass('cmb-disabled');
            });

        } else {
            // checkDepen(elm);
            $.each(elm, function (k, v) {
                $(v).find('input, select').attr('disabled', false);
                $(v).removeClass('cmb-disabled');
            });
        }
    };

    var toogleTabs = function(trigger, elm) {
        if (!trigger.is(':checked')) {
            elm.hide();
        } else {
            elm.show()
        }
    }

})(jQuery);
