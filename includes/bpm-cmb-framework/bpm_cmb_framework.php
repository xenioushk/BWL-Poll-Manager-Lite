<?php

/* ---  Custom Meta Box Section --- */

class BPM_Meta_Box {

    function __construct($custom_fields) {

        $this->custom_fields = $custom_fields; //Set custom field data as global value.

        add_action('add_meta_boxes', array(&$this, 'bpm_metaboxes'));

        add_action('save_post_bwl_poll', array(&$this, 'save_bpm_meta_box_data'));
    }

    //Custom Meta Box.

    function bpm_metaboxes() {

        $bwl_cmb_custom_fields = $this->custom_fields;

//        echo "<pre style='text-align: center;>";
//        print_r($bwl_cmb_custom_fields);
//        echo "</pre>";
        // First parameter is meta box ID.
        // Second parameter is meta box title.
        // Third parameter is callback function.
        // Last paramenter must be same as post_type_name

        add_meta_box(
                $bwl_cmb_custom_fields['meta_box_id'], $bwl_cmb_custom_fields['meta_box_heading'], array(&$this, 'show_meta_box'), $bwl_cmb_custom_fields['post_type'], $bwl_cmb_custom_fields['context'], $bwl_cmb_custom_fields['priority']
        );
    }

    function show_meta_box($post) {

        $bwl_cmb_custom_fields = $this->custom_fields;

        foreach ($bwl_cmb_custom_fields['fields'] as $custom_field) :

            $field_value = "";

            if (!is_array($custom_field['id'])) {
                $field_value = get_post_meta($post->ID, $custom_field['id'], true);
            }
            ?>

            <?php if ($custom_field['type'] == 'text') : ?>

                <p class="bwl_cmb_row">
                    <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['title'] ?> </label>
                    <input type="<?php echo $custom_field['type'] ?>" id="<?php echo $custom_field['id'] ?>" name="<?php echo $custom_field['name'] ?>" class="<?php echo $custom_field['class'] ?>" value="<?php echo esc_attr($field_value); ?>"/>
                </p>

            <?php endif; ?>

            <?php if ($custom_field['type'] == 'bgcolor') : ?>

                <p class="bwl_cmb_row">
                    <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['title'] ?> </label>
                    <input type="text" id="<?php echo $custom_field['id'] ?>" name="<?php echo $custom_field['name'] ?>" class="<?php echo $custom_field['class'] ?> bgcolor" value="<?php echo esc_attr($field_value); ?>" style="width: 120px;"/>
                </p>

            <?php endif; ?>

            <?php if ($custom_field['type'] == 'checkbox') :
                ?>

                <p> 
                    <input type="checkbox" id="<?php echo $custom_field['id'] ?>" name="<?php echo $custom_field['name'] ?>" <?php checked($field_value, 'on', true); ?> />  
                    <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['title'] ?></label>  
                </p>  

            <?php endif; ?>

            <?php if ($custom_field['type'] == 'select') : ?>

                    <?php
                    $values = get_post_custom($post->ID);


                    $selected = isset($values[$custom_field['name']]) ? esc_attr($values[$custom_field['name']][0]) : $custom_field['default_value'];
                    ?>

                <p class="bwl_cmb_row">
                    <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['title'] ?> </label> 
                    <select name="<?php echo $custom_field['name'] ?>" id="<?php echo $custom_field['id'] ?>"> 

                        <option value="" selected="selected">- Select -</option>

                <?php foreach ($custom_field['value'] as $key => $value) : ?>
                            <option value="<?php echo $key ?>" <?php selected($selected, $key); ?> ><?php echo $value; ?></option> 
                <?php endforeach; ?>

                    </select>

                <?php if (isset($custom_field['desc']) && $custom_field['desc'] != "") { ?>
                        <i><?php echo $custom_field['desc']; ?></i>
                <?php } ?>
                </p> 

            <?php endif; ?>

            <?php if ($custom_field['type'] == 'repeatable_text') : ?>


                <p class="bwl_cmb_row bwl_cmb_db">
                    <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['title'] ?>: </label>

                        <?php if (isset($custom_field['desc']) && $custom_field['desc'] != ""): ?>
                        <small class="small-text"><?php echo $custom_field['desc']; ?></small>
                        <?php endif; ?>
                </p>
                <textarea id="bwl_cmb_data_set" style="display: none;"><?php echo json_encode($custom_field['default_value']); ?></textarea>

                    <?php
                    $field_value = apply_filters('bpm_option_data', get_post_meta($post->ID, $custom_field['id']), $post->ID);
//                 
//                    echo "<pre>";
//                    print_r( $field_value );
//                    echo "</pre>";
//                    return '';
//                    
//                    echo json_encode($custom_field['default_value']);
//                 return '';
                    ?>

                <ul class="bwl_cmb_repeat_field_container">

                    <?php
                    $i = 0;

//                        $field_value = apply_filters('filter_kbtfwc_content_data', get_post_meta($post->ID, $custom_field['id']) );
//                        
//                        echo $custom_field['id'];
//                        echo "<pre>";
//                        print_r($field_value);
//                        echo "</pre>";

                    if (!empty($field_value) && is_array($field_value)) {

                        foreach ($field_value as $db_save_key => $db_save_value) {


                            // Find Current Selected Field.
                            ?>

                            <li class="bwl_cmb_repeat_row" data-row_count="<?php echo $i; ?>">				
                                <span class="label"><?php echo isset($custom_field['label_text']) ? $custom_field['label_text'] : 'Option'; ?></span>
                                <input type="text" id="<?php echo $custom_field['id'] . '_' . $i; ?>" name="<?php echo $custom_field['name'] . '[' . $i . '][value]' ?>" value="<?php echo $db_save_value['value']; ?>"/>
                                <input type="hidden"name="<?php echo $custom_field['name'] . '[' . $i . '][opt_id]' ?>" value="<?php echo $db_save_value['opt_id']; ?>"/>
                        <!--                            <select id="<?php echo $custom_field['id'] . '_' . $i; ?>" name="<?php echo $custom_field['name'] . '[' . $i . ']' ?>"> 
                            
                                <option value="" selected="selected">- Select -</option>

                            <?php foreach ($custom_field['default_value'] as $default_key => $default_value) : ?>
                                        <option value="<?php echo $default_key ?>" <?php echo ($db_save_value == $default_key) ? 'selected=selected' : ''; ?> ><?php echo $default_value; ?></option> 
                            <?php endforeach; ?>

                                </select>-->


                         <!--<input id="<?php echo $custom_field['id'] . '_' . $i; ?>_url" class="img-path" type="text" name="<?php echo $custom_field['name'] . '[' . $i . ']' ?>" value="<?php if (!empty($get_bpm_attachment_url)) echo $get_bpm_attachment_url ?>" />-->
                         <!--<input id="upload_<?php echo $custom_field['id'] . '_' . $i; ?>_button" type="button" class="button bwl_cmb_upload_file" value="<?php _e('Upload', 'tie') ?>" data-parent_field="<?php echo $custom_field['id']; ?>" data-row_count="<?php echo $i; ?>"/>-->
                                <div class="clear"></div>
                                <a class="delete_row" title="<?php _e('Delete', 'bwl-kb') ?>"><?php _e('Delete', 'bwl-kb') ?></a>
                            </li>	

                            <?php
                            $i++;
                        }
                    }
                    ?>
                </ul>

                <input id="add_new_row" type="button" class="button" value="<?php echo isset($custom_field['btn_text']) ? $custom_field['btn_text'] : 'Add Option'; ?>" data-delete_text="<?php _e('Delete', 'bwl-kb') ?>"  data-upload_text="<?php _e('Upload', 'bwl-kb') ?>" data-field_type="<?php echo $custom_field['type'] ?>" data-field_name="<?php echo $custom_field['name'] ?>" data-label_text ="<?php echo isset($custom_field['label_text']) ? $custom_field['label_text'] : 'Option'; ?>">


            <?php endif; ?>    

            <?php if ($custom_field['type'] == 'bpm_ans_type') : ?>

                <?php
//                    $values = get_post_custom( $post->ID );
//                    $selected = isset( $values[$custom_field['name']] ) ? esc_attr( $values[$custom_field['name']][0] ) : $custom_field['default_value'];
//                    bpm_answer_type
//                    bpm_maxiumum_answer
//                echo "<pre>";
//                print_r( $custom_field['name'][0]);
//                echo "</pre>";
                $bpm_answer_type = get_post_meta($post->ID, $custom_field['name'][0], true);
//                        echo $bpm_answer_type;
//                        echo "<br>";

                $bpm_maxiumum_answer = get_post_meta($post->ID, $custom_field['name'][1], true);
//                        echo $bpm_maxiumum_answer;
//                        echo "<br>";
                $answer_type = array('bpm_single_answer' => esc_html__('Single Answer', 'bwl-poll'), 'bpm_multiple_answer' => esc_html__('Multiple Answer', 'bwl-poll'));

                $hide_class = "";

                if ($bpm_answer_type == "bpm_single_answer") {
                    $hide_class = "dn";
                }

//                        return '';
                ?>

                <div class="bwl_cmb_grid_row">
                    <div class="bwl-cmb-col-1-4" id="cont_<?php echo $custom_field['id'][0]; ?>">
                        <label for="<?php echo $custom_field['name'][0]; ?>"><?php echo $custom_field['title'] ?> </label> 
                        <select name="<?php echo $custom_field['name'][0]; ?>" id="<?php echo $custom_field['id'][0]; ?>">

                <?php foreach ($answer_type as $key => $value) : ?>
                                <option value="<?php echo $key ?>" <?php selected($bpm_answer_type, $key); ?> ><?php echo $value; ?></option> 
                <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="bwl-cmb-col-1-2 <?php echo $hide_class; ?>" id="cont_<?php echo $custom_field['id'][1]; ?>">
                        <label for="<?php echo $custom_field['id'][1]; ?>"> <?php _e('Maximum Answer', 'bwl-poll'); ?></label>
                        <input style="width: 32px;" type="text" id="<?php echo $custom_field['id'][1]; ?>" name="<?php echo $custom_field['name'][1]; ?>" class="<?php echo $custom_field['class'] ?>" value="<?php echo esc_attr($bpm_maxiumum_answer); ?>"/>

                    </div>
                </div><!-- /.bwl_cmb_grid_row -->



                <?php if (isset($custom_field['desc']) && $custom_field['desc'] != "") { ?>

                    <div class="bwl_cmb_grid_row">
                        <div class="bwl-cmb-col-1-1">
                            <i><?php echo $custom_field['desc']; ?></i>
                        </div>
                    </div>

                <?php } ?>

                <?php
//                echo example_shortcode( 
//                    array ( 'before' => 'This ', 'after' => '!' ), 
//                    'works' 
//                );
                ?>

            <?php endif; ?>

            <?php
            if ($custom_field['type'] == 'bpm_themes') :

                $bpm_theme_fileds = $custom_field['name'];
                ?>

                <div class="bwl_cmb_grid_row">

                <?php
                foreach ($bpm_theme_fileds as $key => $custom_field) :

                    $field_value = get_post_meta($post->ID, $custom_field['id'], true);
                    ?>

                        <div class="bwl-cmb-col-1-4">

                            <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['name'] ?> </label>

                            <span class="bgcolor_display" style="background: <?php echo $field_value; ?>;"></span>

                            <input type="text" id="<?php echo $custom_field['id'] ?>" name="<?php echo $custom_field['id'] ?>" class="bgcolor" value="<?php echo esc_attr($field_value); ?>"/>

                        </div>

                <?php endforeach; ?>

                </div><!-- /.bwl_cmb_grid_row -->



                <?php if (isset($custom_field['desc']) && $custom_field['desc'] != "") { ?>

                    <div class="bwl_cmb_grid_row">
                        <div class="bwl-cmb-col-1-1">
                            <i><?php echo $custom_field['desc']; ?></i>
                        </div>
                    </div>

                <?php } ?>

            <?php endif; ?>

            <?php if ($custom_field['type'] == 'bpm_result_display') : ?>

                <?php
                $bpm_result_display_fileds = $custom_field['name'];

//                        echo "<pre>";
//                        print_r($bpm_theme_fileds);
//                        echo "</pre>";
//                        
//                        return '';
//                    $values = get_post_custom( $post->ID );
//                    $selected = isset( $values[$custom_field['name']] ) ? esc_attr( $values[$custom_field['name']][0] ) : $custom_field['default_value'];
//                    bpm_answer_type
//                    bpm_maxiumum_answer
//                echo "<pre>";
//                print_r( $custom_field['name'][0]);
//                echo "</pre>";
//                        $bpm_answer_type = get_post_meta($post->ID, $custom_field['name'][0], true);
//                        echo $bpm_answer_type;
//                        echo "<br>";
//                        $bpm_maxiumum_answer = get_post_meta($post->ID, $custom_field['name'][1], true);
//                        echo $bpm_maxiumum_answer;
//                        echo "<br>";
//                        $answer_type = array( 'bpm_single_answer' => esc_html__('Single Answer', 'bwl-poll'), 'bpm_multiple_answer'=> esc_html__('Multiple Answer', 'bwl-poll') );
//                        $hide_class = "";
//                        if ( $bpm_answer_type == "bpm_single_answer" ) {
//                            $hide_class = "dn";
//                        }
//                        return '';
                ?>

                <!--                <p class="bwl_cmb_row">
                    
                </p>-->

                <div class="bwl_cmb_grid_row">

                <?php
                foreach ($bpm_result_display_fileds as $key => $custom_field) :

                    $field_value = get_post_meta($post->ID, $custom_field['id'], true);

//                            echo $field_value;
//                        echo "<pre>";
//                        print_r($custom_field['group']);
//                        echo "</pre>";
                    ?>



                    <?php if ($custom_field['type'] == 'bgcolor') : ?>

                            <div class="bwl-cmb-col-1-4 <?php echo $custom_field['group']; ?>">

                                <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['name'] ?> </label>

                                <span class="bgcolor_display" style="background: <?php echo $field_value; ?>;"></span>

                                <input type="text" id="<?php echo $custom_field['id'] ?>" name="<?php echo $custom_field['id'] ?>" class="bgcolor" value="<?php echo esc_attr($field_value); ?>"/>

                            </div>

                    <?php elseif ($custom_field['type'] == 'select') : ?>

                            <div class="bwl-cmb-col-1-4 <?php echo $custom_field['group']; ?>">

                                <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['name'] ?> </label>

                        <?php
                        $bpm_answer_type = $field_value;
                        $answer_type = $custom_field['options'];
                        //                                
                        //                                   echo "<pre>";
                        //                                    print_r($answer_type);
                        //                                    echo "</pre>";
                        ?>


                                <select name="<?php echo $custom_field['id']; ?>" id="<?php echo $custom_field['id']; ?>">

                        <?php foreach ($answer_type as $key => $value) : ?>
                                        <option value="<?php echo $key ?>" <?php selected($bpm_answer_type, $key); ?> ><?php echo $value; ?></option> 
                        <?php endforeach; ?>

                                </select>

                            </div>

                    <?php else : ?>

                            <div class="bwl-cmb-col-1-1 <?php echo $custom_field['group']; ?>">

                                <h2 class="bwl_cmb_meta_title"><?php echo $custom_field['name'] ?> </h2>

                            </div>

                        <?php endif; ?>




                    <?php endforeach; ?>

                </div><!-- /.bwl_cmb_grid_row -->



                    <?php if (isset($custom_field['desc']) && $custom_field['desc'] != "") { ?>

                    <div class="bwl_cmb_grid_row">
                        <div class="bwl-cmb-col-1-1">
                            <i><?php echo $custom_field['desc']; ?></i>
                        </div>
                    </div>

                <?php } ?>

            <?php endif; ?>

            <?php
            if ($custom_field['type'] == 'bpm_advanced_settings') :

                $bpm_advanced_settings_fileds = $custom_field['name'];
                ?>


                <div class="bwl_cmb_grid_row">

                        <?php
                        foreach ($bpm_advanced_settings_fileds as $key => $custom_field) :

                            $field_value = get_post_meta($post->ID, $custom_field['id'], true);
                            ?>



                            <?php if ($custom_field['type'] == 'bgcolor') : ?>

                            <div class="bwl-cmb-col-1-4 <?php echo $custom_field['group']; ?>">

                                <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['name'] ?> </label>

                                <span class="bgcolor_display" style="background: <?php echo $field_value; ?>;"></span>

                                <input type="text" id="<?php echo $custom_field['id'] ?>" name="<?php echo $custom_field['id'] ?>" class="bgcolor" value="<?php echo esc_attr($field_value); ?>"/>

                            </div>

                        <?php elseif ($custom_field['type'] == 'text') : ?>

                            <div class="bwl-cmb-col-1-4 <?php echo $custom_field['group']; ?>">

                                <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['name'] ?> </label>

                                <input type="text" id="<?php echo $custom_field['id'] ?>" name="<?php echo $custom_field['id'] ?>" value="<?php echo esc_attr($field_value); ?>" class="<?php echo isset($custom_field['class']) ? $custom_field['class'] : ''; ?>"/>

                            </div>

                    <?php elseif ($custom_field['type'] == 'select') : ?>

                            <div class="bwl-cmb-col-1-4 <?php echo $custom_field['group']; ?>">

                                <label for="<?php echo $custom_field['id'] ?>"><?php echo $custom_field['name'] ?> </label>

                        <?php
                        $bpm_answer_type = $field_value;
                        $answer_type = $custom_field['options'];
                        //                                
                        //                                   echo "<pre>";
                        //                                    print_r($answer_type);
                        //                                    echo "</pre>";
                        ?>


                                <select name="<?php echo $custom_field['id']; ?>" id="<?php echo $custom_field['id']; ?>">

                        <?php foreach ($answer_type as $key => $value) : ?>
                                        <option value="<?php echo $key ?>" <?php selected($bpm_answer_type, $key); ?> ><?php echo $value; ?></option> 
                        <?php endforeach; ?>

                                </select>

                            </div>

                    <?php else : ?>

                            <div class="bwl-cmb-col-1-1 <?php echo $custom_field['group']; ?>">

                                <h2 class="bwl_cmb_meta_title"><?php echo $custom_field['name'] ?> </h2>

                            </div>

                        <?php endif; ?>


                    <?php endforeach; ?>

                </div><!-- /.bwl_cmb_grid_row -->



                <?php if (isset($custom_field['desc']) && $custom_field['desc'] != "") { ?>

                    <div class="bwl_cmb_grid_row">
                        <div class="bwl-cmb-col-1-1">
                            <i><?php echo $custom_field['desc']; ?></i>
                        </div>
                    </div>

                <?php } ?>

                <?php endif; ?>

            <?php if ($custom_field['type'] == 'bpm_result_info') : ?>

                <p class="bwl_cmb_row">

                <?php
                $get_results = get_bpml_poll_result($post->ID);
//                    echo "<pre>";
//                    print_r($get_results);
//                    echo "</pre>";
//                        $field_value = apply_filters('bpm_option_data', get_post_meta($post->ID, 'bpm_options') );
//                 
//                    echo "<pre>";
//                    print_r( $field_value );
//                    echo "</pre>";

                if (count($get_results) > 0) {

                    $result_string = '<ul class="bpm-admin-poll-result">';

                    $result_string .= '<li>Total Votes: ' . $get_results['total_votes'] . '</li>';


                    foreach ($get_results as $data) {

                        if (!empty($data['option_title']['value'])) {

                            $opt_title = $data['option_title']['value'];
                            $total_votes = $data['total_votes'];
                            $percentage = $data['bar_width'];

                            $result_string .= '<li><strong>' . $opt_title . '</strong><br />' . $total_votes . ' Votes (' . $percentage . ' %)</li>';
                        }


//                            echo "<pre>";
//                            print_r($data);
//                            echo "</pre>";
//                            $option_data = apply_filters('bpm_data_filter', $opt_title, $opt_id );
                    }

                    $result_string .= '</ul>';

                    echo $result_string;
                } else {
                    echo "No Result Found!";
                }
                ?>

                </p>  

            <?php endif; ?>  

            <?php
        endforeach;
    }

    function save_bpm_meta_box_data($id) {

        global $post;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {

            return $id;
            
        } else {

            // Repeatable Poll Options Fields Data Saving In Here.
            // Introduced in version 1.0.4

            if (isset($_POST['bpm_options'])) {

                $bpm_options_count_prev_post_meta = get_post_meta($id, 'bpm_options');

                if (count($bpm_options_count_prev_post_meta) > 1) {
                    //remove old meta fields and then update data.
                    delete_post_meta($id, 'bpm_options');
                }

                /*
                 * Here we did some important thing to improve software performance.
                 * We convert the option slug in to option id
                 *                  
                 */

                // Get the Poll ID.
                $poll_id = $id;

                // Slug Conversion Status.
                // Checking slug to option ID conversion status.

                $bpm_opt_slug_conv_status = get_post_meta($id, 'bpm_opt_slug_conv_status', true);

                //If status is not equal to 1 then we are going to convert slug to id.
                // We get all the values from option panel then
                // using loop we get value and id.


                foreach ($_POST['bpm_options'] as $option) {

                    //Create New option ID: bpm_opt_poll_id_option_id
                    $bpm_new_options_id = 'bpm_opt_' . $poll_id . '_' . $option['opt_id'];

                    if ($bpm_opt_slug_conv_status != 1) {

                        //Get old slug value.  
                        $unique_poll_value = bpm_text_to_slug($option['value']);

                        $bpm_old_options_id = 'bpm_opt_' . $poll_id . '_' . $unique_poll_value;
                        //                      echo "<br>";
                        //Get option votes.
                        $bpm_opt_old_vote_count = get_post_meta($id, $bpm_old_options_id, true);


                        // Update exiting votes in to new slug.
                        update_post_meta($id, $bpm_new_options_id, $bpm_opt_old_vote_count);
                    }

                    // Check if meta id exisit or not.

                    if (get_post_meta($id, $bpm_new_options_id, true) == "") {
                        update_post_meta($id, $bpm_new_options_id, 0);
                    }
                }

                //Update conversion status in here.

                if ($bpm_opt_slug_conv_status != 1) {

                    update_post_meta($id, 'bpm_opt_slug_conv_status', 1);
                }
                
                
                //Sanitizing Option Data
                $bpm_sanitize_arr = $_POST['bpm_options'];
                
                array_walk($bpm_sanitize_arr, function(&$value, &$key) {
                    $value['value'] = sanitize_text_field($value['value']);
                    $value['opt_id'] = sanitize_text_field($value['opt_id']);

                });

                //Finally Insert poll options.
                update_post_meta($id, 'bpm_options', $bpm_sanitize_arr);
            }


            // Custom Build.
            // Update Answer Type Fileds.
            // @Since: Version 1.0.4

            if (isset($_POST['bpm_answer_type'])) {

                update_post_meta($id, 'bpm_answer_type', sanitize_text_field($_POST['bpm_answer_type']));
                update_post_meta($id, 'bpm_maxiumum_answer', ( isset($_POST['bpm_maxiumum_answer']) && is_numeric($_POST['bpm_maxiumum_answer']) ) ? sanitize_text_field($_POST['bpm_maxiumum_answer']) : 1 );
            }


            // Update Theme BG & Text Colors.
            // @Since: Version 1.0.4

            if (isset($_POST['bpm_bg'])) {
                update_post_meta($id, 'bpm_bg', sanitize_hex_color($_POST['bpm_bg']));
            }
            if (isset($_POST['bpm_ques_bg'])) {
                update_post_meta($id, 'bpm_ques_bg', sanitize_hex_color($_POST['bpm_ques_bg']));
            }
            if (isset($_POST['bpm_ques_font_color'])) {
                update_post_meta($id, 'bpm_ques_font_color', sanitize_hex_color($_POST['bpm_ques_font_color']));
            }
            if (isset($_POST['bpm_opt_font_color'])) {
                update_post_meta($id, 'bpm_opt_font_color', sanitize_hex_color($_POST['bpm_opt_font_color']));
            }
            if (isset($_POST['bpm_border_color'])) {
                update_post_meta($id, 'bpm_border_color', sanitize_hex_color($_POST['bpm_border_color']));
            }
            if (isset($_POST['bpm_option_odd_bg'])) {
                update_post_meta($id, 'bpm_option_odd_bg', sanitize_hex_color($_POST['bpm_option_odd_bg']));
            }
            if (isset($_POST['bpm_option_even_bg'])) {
                update_post_meta($id, 'bpm_option_even_bg', sanitize_hex_color($_POST['bpm_option_even_bg']));
            }
            if (isset($_POST['bpm_bottom_bar_bg'])) {
                update_post_meta($id, 'bpm_bottom_bar_bg', sanitize_hex_color($_POST['bpm_bottom_bar_bg']));
            }

            // Update Result Display Settings
            // @Since: Version 1.0.4

            if (isset($_POST['bpm_result_display_type'])) {
                update_post_meta($id, 'bpm_result_display_type', sanitize_text_field($_POST['bpm_result_display_type']));
            }
            if (isset($_POST['bpm_sort_result_status'])) {
                update_post_meta($id, 'bpm_sort_result_status', sanitize_text_field($_POST['bpm_sort_result_status']));
            }
            if (isset($_POST['bpm_bar_theme'])) {
                update_post_meta($id, 'bpm_bar_theme', sanitize_text_field($_POST['bpm_bar_theme']));
            }
            if (isset($_POST['bpml_poll_bar_bg'])) {
                update_post_meta($id, 'bpml_poll_bar_bg', sanitize_hex_color($_POST['bpml_poll_bar_bg']));
            }
            if (isset($_POST['bpm_chart_barcolor'])) {
                update_post_meta($id, 'bpm_chart_barcolor', sanitize_hex_color($_POST['bpm_chart_barcolor']));
            }
            if (isset($_POST['bpm_chart_trackcolor'])) {
                update_post_meta($id, 'bpm_chart_trackcolor', sanitize_hex_color($_POST['bpm_chart_trackcolor']));
            }
            if (isset($_POST['bpm_chart_scalecolor'])) {
                update_post_meta($id, 'bpm_chart_scalecolor', sanitize_hex_color($_POST['bpm_chart_scalecolor']));
            }
            if (isset($_POST['bpm_chart_linecap'])) {
                update_post_meta($id, 'bpm_chart_linecap', sanitize_text_field($_POST['bpm_chart_linecap']));
            }
            if (isset($_POST['bpm_chart_linewidth'])) {
                update_post_meta($id, 'bpm_chart_linewidth', sanitize_text_field($_POST['bpm_chart_linewidth']));
            }
            if (isset($_POST['bpm_chart_animate_time'])) {
                update_post_meta($id, 'bpm_chart_animate_time', sanitize_text_field($_POST['bpm_chart_animate_time']));
            }

            // Update Advanced Settings
            // @Since: Version 1.0.4

            if (isset($_POST['bpm_rtl_support'])) {
                update_post_meta($id, 'bpm_rtl_support', sanitize_text_field($_POST['bpm_rtl_support']));
            }
            if (isset($_POST['bpm_share_btn'])) {
                update_post_meta($id, 'bpm_share_btn', sanitize_text_field($_POST['bpm_share_btn']));
            }
            
        }
    }

}
