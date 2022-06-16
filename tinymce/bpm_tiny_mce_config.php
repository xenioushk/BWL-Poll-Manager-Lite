<?php

/**
* @Description: Shortcode Editor Button
* @Created At: 08-04-2013
* @Last Edited AT: 26-06-2013
* @Created By: Mahbub
**/

add_action('admin_init', 'bpm_tinymce_shortcode_button');

function bpm_tinymce_shortcode_button() {
    
    if ( current_user_can('edit_posts') && current_user_can('edit_pages') && get_user_option( 'rich_editing' ) == 'true') {
        add_filter('mce_external_plugins', 'add_bpm_shortcode_plugin');
        add_filter('mce_buttons', 'register_bpm_shortcode_button');
    }
}

function register_bpm_shortcode_button( $buttons ) {
    array_push($buttons, "bpm");
    return $buttons;
}

function add_bpm_shortcode_plugin( $plugin_array ) {
    $plugin_array['bpm'] = BWL_PM_PLUGIN_DIR . 'tinymce/bpm_tinymce_button.js';
    return $plugin_array;
}


// Shortcode Layout.

add_action('wp_ajax_bpm_get_poll_basic_info', 'bpm_get_poll_basic_info');
add_action( 'wp_ajax_nopriv_bpm_get_poll_basic_info', 'bpm_get_poll_basic_info' );

function bpm_get_poll_basic_info() {
    
    $poll_id = $_REQUEST['poll_id'];

    $output = '<ul class="bpm_poll_info">';

    $bpm_answer_type = ( get_post_meta($poll_id, 'bpm_answer_type', TRUE) == "bpm_multiple_answer") ? esc_html__('Multiple', 'bwl-poll') : esc_html__('Single', 'bwl-poll');
    $bpm_result_display_type = ( get_post_meta($poll_id, 'bpm_result_display_type', TRUE) == "bpm_chart") ? esc_html__('Chart', 'bwl-poll') : esc_html__('Bar', 'bwl-poll');
    $bpm_bar_theme = get_post_meta($poll_id, 'bpm_bar_theme', TRUE);
    
    $bpm_rtl_support = ( get_post_meta($poll_id, 'bpm_rtl_support', TRUE) == "1") ? esc_html__('Yes', 'bwl-poll') : esc_html__('No', 'bwl-poll');
    $bpm_share_btn = ( get_post_meta($poll_id, 'bpm_share_btn', TRUE) == "1") ? esc_html__('Yes', 'bwl-poll') : esc_html__('No', 'bwl-poll');
    
    $output .= '<li><b>Answer Type:</b> ' . $bpm_answer_type . '</li>';
    $output .= '<li><b>Result Type:</b> ' . $bpm_result_display_type . '</li>';
    $output .= '<li><b>Theme:</b> ' . $bpm_bar_theme . '</li>';    
    $output .= '<li><b>RTL:</b> ' . $bpm_rtl_support . '</li>';
    $output .= '<li><b>Share Box:</b> ' . $bpm_share_btn . '</li>';
 
    $output .= '</ul>';

    echo $output;
    die();
    
}

add_action('wp_ajax_bpm_sc_content', 'bpm_sc_content');
add_action( 'wp_ajax_nopriv_bpm_sc_content', 'bpm_sc_content' );


function bpm_sc_content() {
    
        $args = array(
            'post_status' => 'publish',
            'post_type' => 'bwl_poll'
        );

        $loop = new WP_Query($args);
    
    ?>

    <h3>
        <?php _e('BWL Poll Manager Shortcode Editor', 'bwl-poll'); ?>
        <span class="btn_bpm_editor_close">X</span>
     </h3>

    <div id="bpm_editor_popup_content">

        <div class="row">
            <label for="bpm_shortcode">Available Polls</label>
            <select id="bpm_shortcode" name="bpm_shortcode" class="widefat">

                    <option value=""><?php _e(' - Select - ', 'bwl-poll'); ?></option>
                    <?php

                    if ($loop->have_posts()) :

                        while ($loop->have_posts()) :

                            $loop->the_post();

                            $poll_id = get_the_ID();
                            
                            ?>

                            <option value="<?php echo $poll_id ?>"><?php the_title(); ?></option>


                            <?php
                            
                        endwhile;

                    endif;

                    wp_reset_query();
                    ?>

            </select>
        </div>

        <div class="row">

            <label for="bpm_poll_rand">Show Random Poll</label>
            <input type="checkbox" id="bpm_poll_rand" name="bpm_poll_rand" value="1" class="bpm_checkbox"/>

        </div> <!-- end row  -->

        <div class="row">

            <label for="bpm_opt_rand">Random Options</label>
            <input type="checkbox" id="bpm_opt_rand" name="bpm_opt_rand" value="1" class="bpm_checkbox"/>

        </div> <!-- end row  -->


        <div id="bpm_editor_popup_buttons" style="clear: both; ">
            <input id="addShortCodebtn" name="addShortCodebtn" class="button-primary" type="button" value="Add Shortcode" />
            <input id="closeShortCodebtn" name="closeShortCodebtn" class="button" type="button" value="Close" />
        </div>

    </div>

<?php    
    
    die();
    
}