(function ($) {

    $(function () {
        
        //Meta Box Core Functions.
        
        if ( $("#add_new_row").length > 0 ) {
        
            $(document).on('click', '.bwl_cmb_upload_file', function () {

                    var bwl_cmb_uploader;

                    var $bpm_attachment_url = $("#"+$(this).data('parent_field') +'_'+ $(this).data('row_count')+"_url");

                    bwl_cmb_uploader = wp.media.frames.bwl_cmb_uploader = wp.media({
                        title: 'Choose File',
            //            library: {type: 'image'},
                        button: {text: 'Select'},
                        multiple: false
                    });

                    bwl_cmb_uploader.open();

                    bwl_cmb_uploader.on('select', function () {
                        var selection = bwl_cmb_uploader.state().get('selection');
                        selection.map(function (attachment) {

                            attachment = attachment.toJSON();
                            $bpm_attachment_url.attr("value", attachment.url);

                        });
                    });


                });

            $('.bwl_cmb_remove_file').on('click', function(){

                $("#"+$(this).data('parent_field')).attr("value","");

            });

            function cmb_get_random_val(min, max) {

                return Math.floor(Math.random() * (max - min + 1) + min);

              }


            function bwl_cmb_generate_repeat_field( $field_type, $field_name, $count_val, $label_text, $delete_text, $upload_text, $default_value  ) {
    //            console.log($default_value.toSource());
                var $repeat_row ='';

                if ($field_type == 'repeatable_select' ) {
    //                console.log("here");

                    var $select_options = "";

                    var $parse_default_value = $.parseJSON($default_value);

                        $.each($parse_default_value, function(index, element) {

                                 $select_options += '<option value="'+index+'">'+element+'</option>';

                        });



                    $repeat_row+= '<li class="bwl_cmb_repeat_row" data-row_count="'+$count_val+'">'+			
                                            '<span class="label">' + $label_text + '</span> '+
                                            '<select id="'+$field_name+'_'+$count_val+'_url" name="'+$field_name+'['+$count_val+']">'+
                                            '<option value="" selected="selected">- Select -</option>'+
                                            $select_options+
                                            '</select>'+
                                            '<div class="clear"></div>'+
                                            '<a class="delete_row" title="' + $delete_text + '">' + $delete_text + '</a>'+
                                        '</li>';

                } else if ($field_type == 'repeatable_text' ) {

                    $repeat_row+= '<li class="bwl_cmb_repeat_row" data-row_count="'+$count_val+'">'+			
                                            '<span class="label">' + $label_text + '</span> '+
                                            '<input id="'+$field_name+'_'+$count_val+'" name="'+$field_name+'['+$count_val+'][value]" type="text" class="" value="" />'+
                                            '<input name="'+$field_name+'['+$count_val+'][opt_id]" type="hidden" class="" value="'+cmb_get_random_val(99999, 9999999999)+'" />'+
                                            '<div class="clear"></div>'+
                                            '<a class="delete_row" title="' + $delete_text + '">' + $delete_text + '</a>'+
                                        '</li>';
                } else {

                    $repeat_row+= '<li class="bwl_cmb_repeat_row" data-row_count="'+$count_val+'">'+			
                                            '<span class="label">' + $label_text + '</span> '+
                                            '<input id="'+$field_name+'_'+$count_val+'_url" name="'+$field_name+'['+$count_val+']" type="text" class="img-path" value="" />'+
                                            '<input id="upload_'+$field_name+'_'+$count_val+'_button" type="button" class="button bwl_cmb_upload_file" value="' + $upload_text + '" data-parent_field="'+$field_name+'" data-row_count="'+$count_val+'"/>'+
                                            '<div class="clear"></div>'+
                                            '<a class="delete_row" title="' + $delete_text + '">' + $delete_text + '</a>'+
                                        '</li>';
                }



                return $repeat_row;


            }


            // Clone Rows.

            function bpm_get_new_row_id() {


                var new_row_id=0;
                var $bwl_cmb_repeat_field_container = $('.bwl_cmb_repeat_field_container');
                $bwl_cmb_repeat_field_container.find("li").each(function(){

                    if ( $(this).data('row_count') > new_row_id ) {
                        new_row_id =  $(this).data('row_count');
                    }

                });

                return new_row_id;


            }


            $("#add_new_row").click(function () {

                var $bwl_cmb_repeat_field_container = $(this).prev('.bwl_cmb_repeat_field_container');
                var $count_val = $bwl_cmb_repeat_field_container.find('li').length;
    //            console.log($count_val);

                if ($count_val != 0 ) {
                    $count_val = bpm_get_new_row_id() + parseInt(1);
                }

                var $field_type = $(this).data('field_type');
                var $field_name = $(this).data('field_name');

    //            console.log($field_type);
                var $label_text = $(this).data('label_text');
                var $delete_text = $(this).data('delete_text');
                var $upload_text = $(this).data('upload_text');
                var $default_value = $('#bwl_cmb_data_set').val();


                var $bwl_cmb_new_row_html = bwl_cmb_generate_repeat_field( $field_type, $field_name, $count_val, $label_text, $delete_text, $upload_text, $default_value );

    //            console.log($bwl_cmb_new_row_html);

                if ( $bwl_cmb_repeat_field_container.find('li').length == 0 ) {
                    $bwl_cmb_repeat_field_container.html( $bwl_cmb_new_row_html );
                } else {
                    $bwl_cmb_repeat_field_container.find('li:last-child').after( $bwl_cmb_new_row_html );
                }



            });


            // Remove Rows.

             $(document).on('click', '.delete_row', function () {
                $(this).parent().addClass('bwl_cmb_row_deleted').fadeOut(500, function () {
                    $(this).remove();
                });
            });

            // Sortable lists.

            $(".bwl_cmb_repeat_field_container").sortable({placeholder: "bwl-cmb-sort-highlight"});
        
        }
        
         //Background color.
              
        if($('.bgcolor')) {
                  
                  $('.bgcolor').each(function(){
                      
                      
//                      alert(" ");
                       var colorbox = $(this);
        
                            colorbox.ColorPicker({
                                onShow: function(colpkr) {
                                    jQuery(colpkr).slideDown(200);
                                    return false;
                                },
                                onHide: function(colpkr) {
                                    jQuery(colpkr).slideUp(200);
//                                    colorbox.attr("readonly","true");
                                    return false;
                                },
                                onChange: function(hsb, hex, rgb) {
                                    colorbox.val('#' + hex).css({
                                        'text-transform' : 'lowercase'
                                    });   
                                    
                                     colorbox.prev('.bgcolor_display').css({
                                        'background' : '#' + hex
                                    }); 

                                }
                            }); 
                      
                      
                  })
                  
              }
              
        //Conditional Fields.

        if( $('#bpm_answer_type').length > 0 ) {

            var $cont_bpm_maxiumum_answer = $('#cont_bpm_maxiumum_answer');
            
            // Default.
            
            if( $('#bpm_answer_type').val() == "bpm_single_answer" ) {
                
                $cont_bpm_maxiumum_answer.fadeOut('slow');
                
            }

            $('#bpm_answer_type').on('change', function(){

                    if( $(this).val()=="bpm_multiple_answer" ) {

                        $cont_bpm_maxiumum_answer.fadeIn();

                    } else {

                        $cont_bpm_maxiumum_answer.fadeOut('slow');

                    }


            });

             $cont_bpm_maxiumum_answer.on("keypress", function(e){

                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            });

        }

        // Theme Settings Conditional Field.

        if( $('.grp_bar').length > 0 ) {


            var $bpm_result_display_type = $('#bpm_result_display_type');


            function bpm_trigger_result_type( result_display_type ) {

                var $items_grp_bar = $('.grp_bar'),
                  $items_grp_chart = $('.grp_chart');


                if (result_display_type.val()=="bpm_chart") {
                    $items_grp_bar.fadeOut(function(){
                        $items_grp_chart.fadeIn();
                    });

                } else{
                     $items_grp_chart.fadeOut(function(){
                        $items_grp_bar.fadeIn();
                    });
                }
            }

            bpm_trigger_result_type($bpm_result_display_type);


            $bpm_result_display_type.on('change', function(){

                bpm_trigger_result_type($(this));

            })

        }


        // Advanced Settings Conditional Field.

         if( $('.grp_max_vote').length > 0 ) {

            var $bpm_allow_multiple_vote = $('#bpm_allow_multiple_vote');
            var $items_grp_max_vote = $('.grp_max_vote');

            function bpm_display_multiple_vote_field( bpm_allow_multiple_vote ) {


                if ( bpm_allow_multiple_vote.val() == 1 ) {

                        $items_grp_max_vote.show(); 

                } else{

                     $items_grp_max_vote.hide();
                }

            }

            bpm_display_multiple_vote_field( $bpm_allow_multiple_vote );

            $bpm_allow_multiple_vote.on('change', function(){

                bpm_display_multiple_vote_field($(this));

            });

             $items_grp_max_vote.on("keypress", function(e){

                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            });

        }


       //Add A validation For Maximum answer value

        if( $(".bwl_cmb_repeat_row").length && $("#publish").length ) {

            $("#publish").on("click", function(){


                var bpm_max_value = $('#bpm_maxiumum_answer').val();

                var bpm_options_counter = $(".bwl_cmb_repeat_row").length;

                if( bpm_max_value > bpm_options_counter && $('#bpm_answer_type').val() == "bpm_multiple_answer" ) {
                    alert("Maximum answer value should be less then no of poll options!");
                    return false;
                }


                // Start & End date requirements.

                if ( $("#bpm_poll_end_status").length == 1 && $("#bpm_poll_end_status").val() == 1 ) {

                    var $bpm_poll_start_date = $("#bpm_poll_start_date");
                    var $bpm_poll_end_date = $("#bpm_poll_end_date");

                    if (  $bpm_poll_start_date.val() == "" ) {

                        alert("Poll Start date required!");
                        $bpm_poll_start_date.focus();
                        return false;

                    } else  if (  $bpm_poll_end_date.val() == "" ) {

                        alert("Poll end date required!");
                        $bpm_poll_end_date.focus();
                        return false;

                    } else {

                    }


                }


            });

        }


        //Date Range Picker

        if ($("#bpm_poll_end_status").length && $("#bpm_poll_end_date").length) {

            var $bpm_poll_end_status = $("#bpm_poll_end_status"); // parent container.
            var $bpm_poll_start_date = $("#bpm_poll_start_date"); // parent container.
            var $bpm_poll_end_date = $("#bpm_poll_end_date"); // parent container.

            var $bpm_date_fields = $('.grp_vd');

                     $bpm_poll_start_date.datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        dateFormat: 'yy-mm-dd',
                        numberOfMonths: 1,
                        onSelect: function (selectedDate) {

                            $bpm_poll_end_date.datepicker("option", "minDate", selectedDate);

                        }
                    });

                    $bpm_poll_end_date.datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        dateFormat: 'yy-mm-dd',
                        numberOfMonths: 1,
                        onSelect: function (selectedDate) {

                            $bpm_poll_start_date.datepicker({maxDate: selectedDate});

                        }
                    });


                function bpm_date_range_setup( $bpm_poll_end_status ) {


                    if ( $bpm_poll_end_status.val()==1 ) {

                        $bpm_date_fields.fadeIn();

                    } else{

                         $bpm_date_fields.fadeOut();

                    }
                }

                bpm_date_range_setup( $bpm_poll_end_status );


                $bpm_poll_end_status.on('change', function(){

                    bpm_date_range_setup($(this));

                });


        }
            

    });
   

})(jQuery);