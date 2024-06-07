<?php

 /*---  Custom Column Section ---*/

// After manage text we need to add "custom_post_type" value.

add_filter('manage_bwl_poll_posts_columns', 'bpm_custom_column_header' );

function bpm_custom_column_header( $columns ) {
    
//    $columns = array();
    
     $columns['cb'] = 'cb';
     
     $columns['title'] = esc_html__('Question', 'bwl-poll');
     
     $columns['bpm_shortcodes'] = esc_html__('Shortcode', 'bwl-poll');
     
     $columns['bpm_answer_type'] = esc_html__('Poll Type', 'bwl-poll');
     
     $columns['bpm_total_options'] = esc_html__('Options', 'bwl-poll');
     
     $columns['bpm_total_votes'] = esc_html__('Votes', 'bwl-poll');
     
     $columns['bpm_bar_theme'] = esc_html__('Theme', 'bwl-poll');
     
     $columns['bpm_poll_end_status'] = esc_html__('Poll Status', 'bwl-poll');
     
     $columns['date'] = esc_html__('Date', 'bwl-poll');
    
     return $columns;
     
 }

 // After manage text we need to add "custom_post_type" value.
 
add_action('manage_bwl_poll_posts_custom_column', 'bpm_display_custom_column', 10, 1);
 
function bpm_display_custom_column( $column ) {

    // Add A Custom Image Size For Admin Panel.
    
    global $post;
    
    switch ( $column ) {
    
        case 'bpm_shortcodes':

                echo '<div id="bpm_shortcodes-' . $post->ID . '" ><code>[bwl_poll id= "' . $post->ID . '" /]</code></div>';

        break;
    
        case 'bpm_answer_type':

                $bpm_answer_type = ( get_post_meta( $post->ID, 'bpm_answer_type', TRUE ) ==  "bpm_multiple_answer") ? esc_html__('Multiple Answer', 'bwl-poll') : esc_html__('Single Answer', 'bwl-poll');
            
                echo '<div id="bpm_answer_type-' . $post->ID . '" >' . $bpm_answer_type . '</div>';

        break;
    
        case 'bpm_total_options':
                $all_poll_options = apply_filters('bpm_option_counter', get_post_meta( $post->ID, 'bpm_options') );
//            echo "<pre>";
//            print_r($all_poll_options);
//            echo "</pre>";
                echo '<div id="bpm_total_options-' . $post->ID . '" >' . count($all_poll_options) . '</div>';
        break;
    
        case 'bpm_total_votes':
            
                $poll_id = $post->ID;
                
                $all_poll_options = apply_filters('bpm_option_data', get_post_meta( $poll_id, 'bpm_options'), $poll_id );
                
                $poll_total_votes = 0;

                if (sizeof($all_poll_options) > 0) {

                    foreach ($all_poll_options as $poll_key => $poll_value) {

                        $unique_poll_value = $poll_value['opt_id'];
            
                        $options_id = 'bpm_opt_' . $poll_id . '_' . $unique_poll_value;

                        $get_option_votes = get_post_meta( $poll_id, $options_id , true);        

                         if ( $get_option_votes == "" ) {
                            $get_option_votes = 0;
                        }

                        $poll_total_votes +=$get_option_votes;
                            
                    }

                }

                echo '<div id="bpm_total_votes-' . $post->ID . '" >' . $poll_total_votes . '</div>';

        break;
        
        case 'bpm_bar_theme':

                $bpm_bar_theme = get_post_meta( $post->ID, 'bpm_bar_theme', TRUE );
                                echo '<div id="bpm_bar_theme-' . $post->ID . '" >' . ucfirst( str_replace( '_', ' ', $bpm_bar_theme) ) . '</div>';

        break;
    
        case 'bpm_poll_end_status':
            
                $bpm_poll_end_status = ( bpm_get_poll_voting_status( $post->ID ) ==  1 ) ? esc_html__('Open', 'bwl-poll') : esc_html__('Closed', 'bwl-poll');
                
                echo '<div id="bpm_poll_end_status-' . $post->ID . '" >' . $bpm_poll_end_status . '</div>';

        break;
    
    
            
    }
    
}