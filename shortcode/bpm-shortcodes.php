<?php

add_shortcode('bwl_poll', 'bwl_poll');
        
function bwl_poll($atts){
 
    
     extract(shortcode_atts(array(
        'id'     => '0',
        'poll_rand' => 0,
        'opt_rand' => 0  // Introduced in version 1.0.4
    ), $atts));
     
     

        wp_enqueue_script( 'bpm-jquery-remodal-script' );
        wp_enqueue_script( 'bpm-jquery-cookie-script' );
        wp_enqueue_script( 'bpm-jquery-toast-script' );
        wp_enqueue_script( 'bpm-jquery-tooltipster' );
        wp_enqueue_script( 'bpm-jquery-easing' );
        wp_enqueue_script( 'bpm-jquery-easypiechart' );
        wp_enqueue_script( 'bpm-custom-script' );
    
 
     
    // Check voting status.
     
     // 0= voting closed.
     // 1= open for vote.
     // 2= not started yet.
     
    $bpm_poll_voting_status = bpm_get_poll_voting_status( $id ); 
    
//    echo $bpm_poll_voting_status;
//    
//    die();
     
   // Random Poll
     
   if( $poll_rand == 1 ) {
       
       $all_polls_id = array();
        
        $args = array(
            'post_status' => 'publish',
            'post_type' => 'bwl_poll'
        );

        $loop = new WP_Query($args);

        if ($loop->have_posts()) :

            while ($loop->have_posts()) :

                $loop->the_post();

                $poll_id = get_the_ID();

                $all_polls_id[] = $poll_id;

            endwhile;

        endif;

       wp_reset_query();
       
       $total_polls = ( count($all_polls_id) == 0 ) ? 0 : count($all_polls_id) - 1;
       
       if ( $total_polls == 0 ) {
           
           $id = 0;
           
       } else {
       
            $rand_poll_index = rand( 0, $total_polls );

            $id = $all_polls_id[$rand_poll_index];
       
       }
       
   }
 
    $args = array(        
        'p'             => $id,
        'post_status'       => 'publish',
        'post_type'         => 'bwl_poll',
        'posts_per_page' => 1
    );
    
    $loop = new WP_Query($args);
    
    $bpm_rand_id =  wp_rand();
    
    $output = "";
    
    if ( $loop->have_posts() ) :
        
        while ( $loop->have_posts() ) :
        
            $loop->the_post();   
        
            $poll_id = get_the_ID();
            
            $poll_title = get_the_title();
            
            // Get Poll Theme Settings.
            
            $bpm_bg = get_post_meta(get_the_ID(), 'bpm_bg', TRUE);
            $bpm_border_color = get_post_meta(get_the_ID(), 'bpm_border_color', TRUE);
            
            if( $bpm_bg != "" && $bpm_border_color != "" ) {
                $custom_bpm_bg = 'style="background:'.$bpm_bg.'; border-color: ' . $bpm_border_color . ';"';
            } else if( $bpm_bg != "" ) {
                $custom_bpm_bg = 'style="background:'.$bpm_bg.';"';
            } else if( $bpm_border_color != "" ) {
                $custom_bpm_bg = 'border-color: ' . $bpm_border_color . ';"';
            } else {
                $custom_bpm_bg = "";
            }
            
            // Question Section Custom Stylesheet.
            
            $bpm_ques_bg = get_post_meta(get_the_ID(), 'bpm_ques_bg', TRUE);
            
            $bpm_ques_font_color = get_post_meta(get_the_ID(), 'bpm_ques_font_color', TRUE);
            
            if( $bpm_ques_bg != "" && $bpm_ques_font_color != "" ) {
                
                $custom_bpm_ques_bg = 'style="background:' . $bpm_ques_bg . '; color: '. $bpm_ques_font_color . ';"';
                
            } else if( $bpm_ques_bg != "") {
                
                $custom_bpm_ques_bg = 'style="background:' . $bpm_ques_bg . '"';
                
            } else if( $bpm_ques_font_color != "" ) {
                
                $custom_bpm_ques_bg = 'style="color: ' . $bpm_ques_font_color . ';"';
                
            } else {
                $custom_bpm_ques_bg = "";
            }
            
            // Question Options Font Color.
            
            $bpm_opt_font_color = get_post_meta(get_the_ID(), 'bpm_opt_font_color', TRUE);
            
            if( $bpm_opt_font_color != "" ) {
                
                $custom_bpm_opt_font_color = 'style="color: ' . $bpm_opt_font_color . ';"';
                
            } else {
                
                $custom_bpm_opt_font_color = "";
                
            }
            
            
            // Odd Row BG
            
            $bpm_option_odd_bg = get_post_meta(get_the_ID(), 'bpm_option_odd_bg', TRUE);
            
             if( $bpm_option_odd_bg !="" ) {
                 
                $custom_bpm_option_odd_bg = 'style="background:' . $bpm_option_odd_bg . ';"';
                
            } else {
                
                $custom_bpm_option_odd_bg = "";
                
            }

            // Even Row BG
            
            $bpm_option_even_bg = get_post_meta(get_the_ID(), 'bpm_option_even_bg', TRUE);
            
             if( $bpm_option_even_bg !="" ) {
                 
                $custom_bpm_option_even_bg = 'style="background:' . $bpm_option_even_bg . ';"';
                
            } else {
                
                $custom_bpm_option_even_bg = "";
                
            }
            
            // Bottom Bar Background.
            
            $bpm_bottom_bar_bg = get_post_meta(get_the_ID(), 'bpm_bottom_bar_bg', TRUE);
            
             if( $bpm_bottom_bar_bg !="" ) {
                 
                $custom_bpm_bottom_bar_bg = 'style="background:' . $bpm_bottom_bar_bg . ';"';
                
            } else {
                
                $custom_bpm_bottom_bar_bg = "";
                
            }
            
            //Bar Theme & Animation.
            
            $bpm_bar_theme = get_post_meta(get_the_ID(), 'bpm_bar_theme', TRUE);
            
            if( $bpm_bar_theme !="" ) {
                 
                 $get_theme_info = bpm_get_theme_info( $bpm_bar_theme );
                
            } else {
                
                 $get_theme_info = bpm_get_theme_info( 'basic' );
                
            }
            
            // Added in version 1.0.3
            // Default: radio.
            // But, user can choose checkbox.

            $bpm_answer_type = get_post_meta( $poll_id, 'bpm_answer_type', true );
            $bpm_input_type = "radio";

            if( isset($bpm_answer_type) && $bpm_answer_type !="" && $bpm_answer_type == "bpm_multiple_answer") {
                $bpm_input_type = "checkbox";
            }
            
            // Added in version 1.0.3
            // Default max no of option 2.
            
            $bpm_max_answer = get_post_meta( $poll_id, 'bpm_maxiumum_answer', true );
            
            
            $bpm_max_ans_value = 2;

            if( isset( $bpm_max_answer) && $bpm_max_answer !="" && is_numeric($bpm_max_answer) ) {
                $bpm_max_ans_value = $bpm_max_answer;
            }
            
            // GET POLL ANSWER OPTIONS.
            
            $poll_options = get_poll_answer_options( 
                                                                            $poll_id, //post id.
                                                                            $get_theme_info,  //selected theme info
                                                                            $custom_bpm_opt_font_color, 
                                                                            $bpm_rand_id,  // Question container ID.
                                                                            $bpm_input_type,  // raido or checkbox.
                                                                            $custom_bpm_option_odd_bg,  // Odd Option BG Color.
                                                                            $custom_bpm_option_even_bg, // Even Option BG Color.
                                                                            $opt_rand 
                                                                            );
            
            
            
            // GET POLL RESULT WINDOW
            // Before Displaying poll result text we need to check poll result display status.
            
            $bpm_hide_poll_result = get_post_meta( $poll_id, 'bpm_hide_poll_result', TRUE);
            
            
            $bpml_poll_result_text = get_poll_result_window( 
                                                                                            $poll_id, // post id.
                                                                                            $get_theme_info, // selected theme info
                                                                                            $custom_bpm_ques_bg, 
                                                                                            $custom_bpm_opt_font_color, 
                                                                                            $bpm_hide_poll_result 
                                                                                            );
            
//            echo $bpm_hide_poll_result;
//            return '';
            
            /*--- RTL Support (From Version: 1.0.0)  ---*/
            
            $bpm_rtl_support = get_post_meta(get_the_ID(), 'bpm_rtl_support', TRUE);
            
            if( $bpm_rtl_support == 1 ) {
                 
                 $bpm_rtl_support_class = " bpm-container-rtl-support";
                
            } else {
                
                 $bpm_rtl_support_class = "";
                
            }
            
            /*--- Edit Poll Options (From Version: 1.0.1)  ---*/
            
            $bpm_poll_edit_text = "";
            
//            if( current_user_can( 'edit_post', $poll_id ) ) {
//                $bpm_poll_edit_url = get_edit_post_link();
//                $bpm_poll_edit_text = '<a href="'.$bpm_poll_edit_url.'" target="_blank" title="Edit Poll - '.get_the_title().'" class="bpm_edit">' . esc_html__('Edit Poll', 'bwl-adv-faq') . '</a>';
//            }
            
            /*---  Introduce two buttons votes/result ---*/
            
            if ( $bpm_input_type == "checkbox" ) {
                
                $bpm_vote_now_btn = '<button class="btn-poll-vote-now" type="button" data-poll-container-id="'.$bpm_rand_id.'"  data-status="0" data-poll-id="' . $poll_id . '" data-theme="' . $get_theme_info['theme_class'] . '" data-animate-class="' . $get_theme_info['animate_class'] . '" data-max-answer="' . $bpm_max_ans_value . '">' . esc_html__('Vote Now', 'bwl-poll') . '</button> ';
                
            } else {
                
                $bpm_vote_now_btn = "";
                
            }
            
            

            $bpm_results_btn = '<button class="btn-poll-flip" type="button" data-poll-container-id="'.$bpm_rand_id.'" data-status="0" data-poll-id="' . $poll_id . '" data-theme="' . $get_theme_info['theme_class'] . '" data-animate-class="' . $get_theme_info['animate_class'] . '">' . esc_html__('Results', 'bwl-poll') . '</button> ';

            //@Description: Share Button
            //@Since: Version 1.0.4
            
            $bpm_share_btn_status = get_post_meta( $poll_id , 'bpm_share_btn', TRUE) ; // Added in version 1.0.4
            
            if ( $bpm_share_btn_status == 1 ) {
                
                $bpm_share = do_shortcode('[bpm_share id="'.$poll_id.'" /]');
                $bpm_poll_edit_text .=$bpm_share;
                
            }
            
            
            // Display a notification message, If poll voting status is close by admin.
            //@since : Version 1.0.2
            
             if( $bpm_poll_voting_status == 2 ) {
                
                // Hide Poll and show the result
                $bpm_poll_start_date = explode('-', get_post_meta( $poll_id , 'bpm_poll_start_date', TRUE) ); // Added in version 1.0.4
                
                $output .= '<div class="bpm-msg-container"><i class="fa fa-info-circle"></i> 
                                    ' . esc_html__('We will start collecting vote from ', 'bwl-poll') .'<strong>'. $bpm_poll_start_date[2].'-'.$bpm_poll_start_date[1].'-'.$bpm_poll_start_date[0] . '</strong> .'.esc_html__(' Thank You!', 'bwl-poll') . '
                                </div>
                                
                                <div class="bpm-container' . $bpm_rtl_support_class . '" ' . $custom_bpm_bg . '>
                    
                                <div class="bpm-question-container" id="bpm-question-' . $bpm_rand_id . '">

                                    <p class="bpm-poll-question" ' . $custom_bpm_ques_bg . '> ' . $poll_title . '</p>

                                </div> <!-- end .bpm-question-container  -->

                                <div class="result-container" id="bpm-result-' . $bpm_rand_id . '" style="display:block;">
                                    ' . $bpml_poll_result_text . '
                                </div> <!-- end .result-container  -->

                            </div>';
                
                
            } else if( $bpm_poll_voting_status == 0 ) {
                
                // Hide Poll and show the result
                
                $output .= '<div class="bpm-msg-container"><i class="fa fa-info-circle"></i> 
                                    ' . esc_html__('Poll voting has been closed !', 'bwl-poll') . '
                                </div>
                                
                                <div class="bpm-container' . $bpm_rtl_support_class . '" ' . $custom_bpm_bg . '>
                    
                                <div class="bpm-question-container" id="bpm-question-' . $bpm_rand_id . '">

                                    <p class="bpm-poll-question" ' . $custom_bpm_ques_bg . '> ' . $poll_title . '</p>

                                </div> <!-- end .bpm-question-container  -->

                                <div class="result-container" id="bpm-result-' . $bpm_rand_id . '" style="display:block;">
                                    ' . $bpml_poll_result_text . '
                                </div> <!-- end .result-container  -->

                            </div>';
                
                
            } else {
                
              //Parameter hints.  
             // $bpm_rand_id : it's the unique ID of question container.
            
            
             $output .= '<div class="bpm-container' . $bpm_rtl_support_class . '" ' . $custom_bpm_bg . '>
            
                    <div class="bpm-question-container" id="bpm-question-' . $bpm_rand_id . '">

                        <p class="bpm-poll-question" ' . $custom_bpm_ques_bg . '> ' . $poll_title . '</p>

                        ' . $poll_options .'

                    </div> <!-- end .bpm-question-container  -->

                    <div class="result-container" id="bpm-result-' . $bpm_rand_id . '">
                        ' . $bpml_poll_result_text . '
                    </div> <!-- end .result-container  -->

                    <div class="action-container"  id="action-container-' . $bpm_rand_id . '" ' . $custom_bpm_bottom_bar_bg . '>
                             ' . $bpm_vote_now_btn . $bpm_results_btn . $bpm_poll_edit_text . '
                    </div> <!-- end .action-container  -->

                </div>';
             
            }
            
        endwhile;
        
    else :
            
            $output .= "<p>". esc_html__('No Poll Available !', 'bwl-poll') . "</p>";
        
    endif;
    
    wp_reset_query(); 
    
    return $output;
    
}

function bpm_shuffle_assoc( $all_poll_options ) {
    
    $shuffled_array = array();

    $keys = array_keys($all_poll_options);
    shuffle($keys);
    
    foreach ($keys as $key){
        $shuffled_array[$key] = $all_poll_options[$key];
    }
    
    $all_poll_options = $shuffled_array;
    
    return $all_poll_options;
    
}

/*--- GET POLL ANSWER OPTIONS ---*/

function get_poll_answer_options( $poll_id, $get_theme_info, $custom_bpm_opt_font_color, $bpm_rand_id, $bpm_input_type, $custom_bpm_option_odd_bg, $custom_bpm_option_even_bg, $opt_rand ) {
    
    $poll_option_string = "";
        
//    $all_poll_options = get_post_meta( $poll_id, 'bpm_options' );
    
    $get_poll_options = apply_filters('bpm_option_data', get_post_meta( $poll_id, 'bpm_options'), $poll_id );
    
//    echo "<pre>";
//    print_r($get_poll_options);
//    echo "</pre>";
    
    // Shuffle poll options.
    if ( function_exists('bpm_shuffle_assoc') && $opt_rand == 1 ) {
        
        $get_poll_options = bpm_shuffle_assoc($get_poll_options);
        
    }
    
    
    if ( sizeof( $get_poll_options ) > 0 ) {

        $poll_option_string.= '<ul class="bpm-poll-options" ' . $custom_bpm_opt_font_color . ' data-poll-total-options="'.sizeof( $get_poll_options ).'">';

        $row_counter = 0; // Use to mark even/odd rows.
        
        foreach ( $get_poll_options as $poll_key => $poll_value ) {

//            $unique_poll_value = bpm_text_to_slug( $poll_value['value'] );    
            
            $unique_poll_value = $poll_value['opt_id']; // This ID is required to collect vote data.
            
            if ( $row_counter%2 == 1 && $custom_bpm_option_odd_bg !="" ){
                
                $bpm_row_class = $custom_bpm_option_odd_bg;
                
            } elseif ( $row_counter%2 == 0 && $custom_bpm_option_even_bg !="" ) {
                
                $bpm_row_class = $custom_bpm_option_even_bg;
                
            } else {
                
                $bpm_row_class = "";
                
            }

            $poll_option_string.='<li ' . $bpm_row_class . '><input name="poll-option" class="poll-option" id="' . $bpm_rand_id .'-' .$poll_key.'" type="'.$bpm_input_type.'" value="' . $unique_poll_value . '" data-poll-id="' . $poll_id . '" data-poll-container-id="' . $bpm_rand_id . '" data-theme="' . $get_theme_info['theme_class'] . '" data-animate-class="' . $get_theme_info['animate_class'] . '"> <label for="' . $bpm_rand_id .'-' .$poll_key.'">' . $poll_value['value'] . '</label></li>';
            
            $row_counter++;
        }

        $poll_option_string.= '</ul><!-- end .bpm-poll-options -->';
    }
    
//    echo $poll_option_string;
    
    return $poll_option_string;
}


/*--- GET POLL RESULT WINDOW ---*/


function get_poll_result_window( $poll_id, $get_theme_info, $custom_bpm_ques_bg, $custom_bpm_opt_font_color, $bpm_hide_poll_result ) {
   
    
    $bpml_poll_result = get_bpml_poll_result( $poll_id );
    
//    echo "<pre>";
//    print_r($bpml_poll_result);
//    echo "</pre>";
    
     
    
    $bpml_poll_result_text = "";
            
//    $bpml_poll_result_text .= '<p class="bpm-poll-results-title" ' . $custom_bpm_ques_bg . '>'. esc_html__('Poll Result', 'bwl-poll') .'</p>';
    
    if ( $bpm_hide_poll_result == 1 ) {
    
        $bpml_poll_result_text .= '<p>' . esc_html__('Thanks For Your Vote!','bwl-poll') . '</p>';
        
    } else {
        
        /*--- Count Total Votes ---*/

        $poll_total_votes = $bpml_poll_result['total_votes'];
        
        

        /*--- Bar Background ---*/
         
        $bpml_poll_bar_bg_style = "";
        
        $bpml_poll_bar_bg = get_post_meta(get_the_ID(), 'bpml_poll_bar_bg', TRUE);
        
        if ( $bpml_poll_bar_bg !="" ) {
            
            $bpml_poll_bar_bg_style = 'style="background: ' . $bpml_poll_bar_bg.'"; ';
            
        }
        
        /*--- Create Result Interface  ---*/

        $bpml_poll_result_text .= '<ul class="bpm-poll-results" ' . $custom_bpm_opt_font_color . '>';

        foreach( $bpml_poll_result as $poll_result ) {

            if( !empty($poll_result['option_title']['value']) ) {
            
                $bar_width = $poll_result['bar_width'];

                $poll_bar = '<div class="bar ' . $get_theme_info['theme_class'] . '" ' . $bpml_poll_bar_bg_style . '><span class="' . $get_theme_info['animate_class'] . '" style="width: '.$bar_width.'%; " data-bar_width = "'.$bar_width.'"></span></div>';

                $bpml_poll_result_text .= '<li>' . $poll_result['option_title']['value'] . ' : ' . $poll_result['total_votes'] . $poll_bar . '</li>';
            
            }

        };

        $bpml_poll_result_text .= '</ul> <!-- end .bpm-poll-results  -->';

        $bpml_poll_result_text .= '<p class="total-votes" ' . $custom_bpm_opt_font_color . '><i class="fa fa-bar-chart-o"></i> '. esc_html__('Total Votes: ', 'bwl-poll') . $poll_total_votes . '</p>';
        
    }
   
    return $bpml_poll_result_text;
    
}

/***********************************************************
* @Description: Checking Poll Voting Status. 
 * If end voting status checking is enabled and current date is greater than the end date, 
 * set by user then we return 0, other wise default value is 1.
* @Created At: 04-12-2014 
* @Last Edited AT: 04-12-2014
* @Created By: Mahbub
***********************************************************/

function bpm_get_poll_voting_status( $poll_id ) {
    
    //0= voteing time expired.
    //1= allowed to submit vote.
    //2= vote not started yet.
    
    $bpm_poll_end_status = get_post_meta( $poll_id , 'bpm_poll_end_status', TRUE);
    $bpm_poll_start_date = get_post_meta( $poll_id , 'bpm_poll_start_date', TRUE); // Added in version 1.0.4
    $bpm_poll_end_date = get_post_meta( $poll_id , 'bpm_poll_end_date', TRUE);
    $bpm_poll_current_date = date("Y-m-d"); //2016-05-12
    
    if( $bpm_poll_end_status == 1 && 
         $bpm_poll_start_date !="" && 
         $bpm_poll_end_date !="" && 
         ( strtotime( $bpm_poll_current_date ) >= strtotime( $bpm_poll_start_date ) && strtotime( $bpm_poll_current_date ) <= strtotime( $bpm_poll_end_date )  ) 
      ) {
        
        $bpm_poll_voting_status  = 1;
        
    } else if( $bpm_poll_end_status == 1 && $bpm_poll_start_date !="" && strtotime( $bpm_poll_current_date ) < strtotime( $bpm_poll_start_date )  ) {
         
         $bpm_poll_voting_status = 2;
         
     } else if( $bpm_poll_end_status == 1 && $bpm_poll_end_date !="" && strtotime( $bpm_poll_current_date ) > strtotime( $bpm_poll_end_date )  ) {
        
        $bpm_poll_voting_status = 0;
        
    } else {
         $bpm_poll_voting_status  = 1; // Always allowed to submit vote.
         
    }
    return $bpm_poll_voting_status;
//    return 'VS:' . $bpm_poll_voting_status . ' PES: '.$bpm_poll_end_status . ' PED: '.$bpm_poll_end_date . ' PCD: ' .$bpm_poll_current_date;
    
}


/*

 * @Description: Get Poll Results.
 * @Since: Version 1.0.0
 * @By: Mahbub Alam Khan
 *  */

function get_bpml_poll_result( $poll_id = "") {
    
    if( $poll_id == "" ) {
        
        $bpml_poll_result[] = array(
            'option_title' => '',
            'total_votes' => 0
        );
        
        return $bpml_poll_result;
        
    } 
    
    $get_poll_options = apply_filters('bpm_option_data', get_post_meta( $poll_id, 'bpm_options'), $poll_id );
    $bpml_poll_result= array();
    
    
    if ( ! empty( $get_poll_options ) ) {
        
        /*--- Count Total Vote Cast For This Poll ---*/
        
        $poll_total_votes = 0;
        
        foreach ($get_poll_options as $poll_key => $poll_value) {
            
//            $unique_poll_value = bpm_text_to_slug($poll_value);
              $unique_poll_value = $poll_value['opt_id'];
            
            $options_id = 'bpm_opt_' . $poll_id . '_' . $unique_poll_value;
            
            $get_option_votes = get_post_meta( $poll_id, $options_id , true);        
            
             if ( $get_option_votes == "" ) {
                $get_option_votes = 0;
            }
             
            $poll_total_votes +=$get_option_votes;
            
        }
        
        /*--- Generate JSON Data ---*/

        foreach ($get_poll_options as $poll_key => $poll_value) {
            
//            $unique_poll_value = bpm_text_to_slug($poll_value);
            $unique_poll_value = $poll_value['opt_id'];
            
            $options_id = 'bpm_opt_' . $poll_id . '_' . $unique_poll_value;
            
            $get_option_votes = get_post_meta( $poll_id, $options_id , true);        
            
             if ( $get_option_votes == "" ) {
                $get_option_votes = 0;
            }
            
            $bar_width = bpm_calculate_percentage( $poll_total_votes, $get_option_votes);
            
            $bpml_poll_result[] = array(
                'option_title' => $poll_value,
                'total_votes' => $get_option_votes,
                'bar_width' => $bar_width
            );
        }
        
        // Sort options by top vote.
        
        if (get_post_meta( $poll_id, 'bpm_sort_result_status' , true) == 1 ) {
            usort($bpml_poll_result, 'cust_sort');
        }
        
        $bpml_poll_result['total_votes']= $poll_total_votes;
        
        }
    
    return $bpml_poll_result;
}

        function cust_sort($a,$b) {
            return strtolower($a['total_votes']) < strtolower($b['total_votes']);
        }

/*
 * @Description: Post Share Button.
 * @Created by: Md Mahbub Alam Khan
 * @Since: 1.0.7
 * @Created at: 20-02-2015
 * @Last Update: 20-02-2015
 *  */

add_shortcode('bpm_share', 'bpm_share');

function bpm_share( $atts ) {
    
    extract(shortcode_atts(array(
        'post_id' => ''
    ), $atts));

    $bpvm_post_title =  str_replace(' ', '+', get_the_title( $post_id ));
    $bpvm_post_url = get_permalink( $post_id );
    
    return '<span class="bpm-share-links">

                        <a class="btn-share bpm_share" href="https://twitter.com/share?url='.$bpvm_post_url.'&amp;text='.$bpvm_post_title.'" title="Tweet It">
                           <i class="fa fa-twitter"></i>
                        </a>

                        <a class="btn-share bpm_share" href="http://www.facebook.com/sharer.php?u='.$bpvm_post_url.'" title="Share at Facebook">
                             <i class="fa fa-facebook"></i>
                        </a>

                        <a class="btn-share bpm_share" href="http://plus.google.com/share?url='.$bpvm_post_url.'" title="Share at Google+">
                           <i class="fa fa-google-plus"></i>
                        </a>

                        <a class="btn-share" href="mailto:?subject='.$bpvm_post_title.'&amp;body='.$bpvm_post_url.'" title="Share via Email">
                             <i class="fa fa-envelope-o"></i>
                        </a>

            </span>';
    
    

//                        <a class="btn-share bpm_share" href="http://pinterest.com/pin/create/button/?url='.$bpvm_post_url.'" title="Share at Pinterest">
//                            <i class="fa fa-pinterest"></i>
//                        </a>
//
//                        <a class="btn-share bpm_share" href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.$bpvm_post_url.'" title="Share at LinkedIn">
//                             <i class="fa fa-linkedin"></i>
//                        </a>
    
}