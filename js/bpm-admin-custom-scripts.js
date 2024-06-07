(function ($) {

    $(function () {


        var $bpm_background_settings = $("#background-settings"); // parent container.
        var $bpm_font_color_settings = $("#font-color-settings"); // parent container.
        var $bpm_result_display_settings = $("#result-display-settings"); // parent container.
        var $bpm_advanced_settings = $("#advanced-settings"); // parent container.
        var $bpm_answer_type_settings = $("#answer-type-settings"); // parent container.


        if ($bpm_result_display_settings.length == 1) {

            // remove permalink field.

//           $('#edit-slug-box').parents('.inside').hide();

//           var $bpm_all_settings_field = $([]).add($bpm_background_settings).add($bpm_font_color_settings).add($bpm_result_display_settings).add($bpm_advanced_settings);

//           $bpm_all_settings_field.addClass('closed');


            var $bpm_result_display_type = $("#bpm_result_display_type");

//           alert("bpm_result_display_type" + $bpm_result_display_type.length);
            // Do all the code in here.




            var bpm_bar_settings_title = $("#bpm_bar_settings_title");
            var bpm_bar_theme = $("#bpm_bar_theme");
            var bpml_poll_bar_bg = $("#bpml_poll_bar_bg");

            var $bpm_bar_settings_container = $([]).add(bpm_bar_settings_title.parents('div.cmb-row:first'))
                    .add(bpm_bar_theme.parents('div.cmb-row:first'))
                    .add(bpml_poll_bar_bg.parents('div.cmb-row:first'));


            var bpm_chart_settings_title = $("#bpm_chart_settings_title");
            var bpm_chart_barcolor = $("#bpm_chart_barcolor");
            var bpm_chart_linecap = $("#bpm_chart_linecap");

            var $bpm_chart_settings_container = $([]).add(bpm_chart_settings_title.parents('div.cmb-row:first'))
                    .add(bpm_chart_barcolor.parents('div.cmb-row:first'))
                    .add(bpm_chart_linecap.parents('div.cmb-row:first'));


        }



    });


})(jQuery); // jQuery Wrapper!