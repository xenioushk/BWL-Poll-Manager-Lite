(function ($) {
    "use strict";

    $(function () {

        // Editor Part

        function bpm_add_sc_action() {

            var $bnm_parent_container = $("#bpm_editor_popup_content");

            var shortcode = "";

            $('#addShortCodebtn').on("click", function (e) {

                shortcode = '[bwl_poll';

                // Animation Type
                if ($bnm_parent_container.find('#bpm_shortcode').val().length != 0) {
                    shortcode += ' id="' + $bnm_parent_container.find('#bpm_shortcode').val() + '" ';
                }

                // Show Random Poll

                if ($bnm_parent_container.find('#bpm_poll_rand').is(':checked')) {

                    shortcode += ' poll_rand="' + 1 + '" ';

                } else {

                    shortcode += ' poll_rand="' + 0 + '" ';

                }

                // Show Options Randomly

                if ($bnm_parent_container.find('#bpm_opt_rand').is(':checked')) {

                    shortcode += ' opt_rand="' + 1 + '" ';

                } else {

                    shortcode += ' opt_rand="' + 0 + '" ';

                }

                // Order
                shortcode += ' /]';

                window.send_to_editor(shortcode);

                $('#bpm_editor_overlay').remove();

                return false;

            });

            $('#closeShortCodebtn, .btn_bpm_editor_close').on("click", function (event) {
                $('#bpm_editor_overlay').remove();
                return false;
            });

        }
        
        
        
        function handle_bpm_get_poll_basic_info( $poll_id) {

            return jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'HTML',
                data: {
                    action: 'bpm_get_poll_basic_info', // action will be the function name
                    poll_id: $poll_id
                }
            });

        }

        function handle_bpm_sc_content() {

            return jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'HTML',
                data: {
                    action: 'bpm_sc_content', // action will be the function name
                }
            });

        }
        
        /*-- Start TinyMCE Integration Code --*/

        tinymce.create('tinymce.plugins.bpm', {
            init: function (ed, url) {
                ed.addButton('bpm', {
                    title: 'BWL Poll Manager',
                    image: url + '/icons/bpm-editor.png',
                    onclick: function () {

                        if ($('#shortcode_controle').length) {

                            $('#shortcode_controle').remove();

                        } else {
                            
                            var bpm_sc_loading_icon = '<img src="'+url + '/icons/load_icon.gif" class="bpm_sc_load_icon"></img>';
                            
                            $('body').append('<div id="bpm_editor_overlay"><div id="bpm_editor_popup">'+bpm_sc_loading_icon+'</div></div>');
                            
                            $.when(handle_bpm_sc_content()).done(function (data) {
                                $('#bpm_editor_popup').html("").html(data).draggable({cursor: "move"});
                                bpm_add_sc_action();
                                
                                // Start Basic Info Container.
                                $("#bpm_editor_popup_buttons").after('<div id="bpm_basic_info"></div>');
                                
                                $("#bpm_shortcode").on("change",function(){
                                    
                                    var $poll_id = $(this).val();
                                    var $bpm_basic_info = $("#bpm_basic_info");
                                          $bpm_basic_info.html("");
                                    
                                    if( $poll_id == "" ) {
                                        return 1;
                                    }
                                    
                                    $bpm_basic_info.html("Loading Info ......");
                                    
                                    
                                    $.when(handle_bpm_get_poll_basic_info($poll_id)).done(function (data) {
                                        $bpm_basic_info.html("").html(data);
                                    });
                                    
                                })
                                
                                // End Basic Info Container.
                                
                                
                            });

                        }
                    }
                });
            },
            createControl: function (n, cm) {
                return null;
            }
        });

        tinymce.PluginManager.add('bpm', tinymce.plugins.bpm);

    });

}(jQuery));