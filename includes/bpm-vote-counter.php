<?php

function bwl_pm_set_ajax_url() {
    
    $bwl_pm_data = get_option('bwl_poll_options');
    
    
    /*---  Notification Feature has been added in version 1.0.3 ---*/
    
    $bpm_notification_bgcolor = '#E43536';
    
    if( isset( $bwl_pm_data['bpm_notification_bgcolor'] ) && $bwl_pm_data['bpm_notification_bgcolor'] !="" ) { 
        
         $bpm_notification_bgcolor = $bwl_pm_data['bpm_notification_bgcolor'];
        
    }
    
    $bpm_notification_textcolor = '#FFFFFF';
    
    if( isset( $bwl_pm_data['bpm_notification_textcolor'] ) && $bwl_pm_data['bpm_notification_textcolor'] !="" ) { 
        
         $bpm_notification_textcolor = $bwl_pm_data['bpm_notification_textcolor'];
        
    }
    
    /*--- Poll Modal Window Display Delay ---*/
    
    $bpm_modal_poll_delay = 0;
    
    if( isset( $bwl_pm_data['bwl_poll_conditinal_fields']['enabled'] ) && $bwl_pm_data['bwl_poll_conditinal_fields']['enabled'] == 'on' && isset( $bwl_pm_data['bwl_poll_conditinal_fields']['bpm_modal_poll_delay'] ) ) { 
        
        $bpm_modal_poll_delay = $bwl_pm_data['bwl_poll_conditinal_fields']['bpm_modal_poll_delay'];
                
    }
    
?>

    <script type="text/javascript">
        
       var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>',
              bpm_load_icon_url = '<?php echo BWL_PM_PLUGIN_DIR . 'images/loader.gif'; ?>',
              bpm_text_total_votes = '<?php _e('Total Votes', 'bwl-poll'); ?>',
              bpm_text_show_result = '<?php _e('Results', 'bwl-poll'); ?>',
              bpm_text_show_voting_panel = '<?php _e('Voting Panel', 'bwl-poll'); ?>',
              bpm_text_poll_result = '<?php _e('Poll Result', 'bwl-poll'); ?>',
              bpm_text_wait_msg = '<?php _e('Loading ...', 'bwl-poll'); ?>',
              bpm_text_notification = '<?php _e('Notification !', 'bwl-poll'); ?>',
              bpm_text_option = '<?php _e('option !', 'bwl-poll'); ?>',
              bpm_text_options = '<?php _e('options !', 'bwl-poll'); ?>',
              bpm_text_max_answer = '<?php _e('You can choose maximum', 'bwl-poll'); ?>'
              bpm_text_choose_one_option = '<?php _e('Please choose atleast one option!', 'bwl-poll'); ?>',
              bpm_notification_bgcolor = '<?php echo $bpm_notification_bgcolor; ?>',
              bpm_notification_textcolor = '<?php echo $bpm_notification_textcolor; ?>',
              bpm_modal_poll_delay = '<?php echo $bpm_modal_poll_delay; ?>';
       
    </script>

<?php

}

add_action('wp_head', 'bwl_pm_set_ajax_url');


function bpm_vote_count_checkpost( $poll_id )  {
    
        // Custom Database Table For Poll Manager.
    
       $bpm_vote_count_status = 1;
       
       if(isset( $_POST['choose_id'] ) && $_POST['choose_id'] > -1  ) {
           $msg = __(" Thanks For Your Vote ! ", 'bwl-poll');
       } else {
           $msg = "";
       }
       
       
       $bpm_current_date = date("Y-m-d");
       $ip = $_SERVER['REMOTE_ADDR'];
       
       // Here first we check if loggin is required or not.
             
       $bpm_login_required_status = get_post_meta( $poll_id, 'bpm_login_required', TRUE);
       
       if( $bpm_login_required_status == 1 ) {
           
           if ( ! is_user_logged_in() ) {

              $msg =  esc_html__(' Login required to submit vote!', 'bwl-poll');
           
                $bpm_vote_count_status = array(
                     'vote_count_status' => 0,
                     'msg' => $msg
                 );

                return $bpm_vote_count_status;
              
           }
           
       }
       
       
       global $wpdb;
       $bpm_voting_data_table=  $wpdb->prefix . "bpm_vote_data"; // for deatils. each day info.
       
       $bpm_total_submitted_votes = 0; // we assume that there is no vote submitted yet.
           
        $bpm_submissiom_counter_query = "SELECT COUNT(ID)as total_submissiom From ".$bpm_voting_data_table." WHERE DATE(vote_date)= '".$bpm_current_date."' AND postid=".$poll_id." AND voted_ip='".$ip."' order by ID DESC";
        $bpm_submissiom_counter_results = $wpdb->get_results($bpm_submissiom_counter_query, ARRAY_A);

//        echo "<pre>";
//        print_r($bpm_submissiom_counter_results);
//        echo "</pre>";
        
        if ( !empty($bpm_submissiom_counter_results) ) {
            $bpm_total_submitted_votes =$bpm_submissiom_counter_results[0]['total_submissiom'];
        }
        
      
       
       //Maxiumum Vote from an IP.
       
       $bpm_allow_multiple_vote = 0; // default we are not going to allow submit multiple votes from and IP.
       
        //We get option from database.
       $bpm_allow_multiple_vote = get_post_meta( $poll_id, 'bpm_allow_multiple_vote', TRUE);
       
       //If it's return 1 then we are going to pick the maximum number of votes allowed to submit a day.
       if ( $bpm_allow_multiple_vote == 1 ) {
           
           $bpm_allow_ip_max_vote = get_post_meta( $poll_id, 'bpm_allow_ip_max_vote', TRUE);
           
           // If we do not get any set value from database we will set the max no of vote equal to 1
           if ( $bpm_allow_ip_max_vote =="" ) {
               $bpm_allow_ip_max_vote = 1; 
           }
           
           // Now we are going to make a query in to database to check how many votes has been submitted from that single IP.
           
//           echo $bpm_total_submitted_votes;
//           echo "<br>";
//           
//           echo $bpm_allow_ip_max_vote;
//           
//           die();
           
           if ( $bpm_total_submitted_votes > $bpm_allow_ip_max_vote) {
               $bpm_vote_count_status = 0;
               $msg = esc_html__(' You\'re maximum vote submission quota has been exceeded!', 'bwl-poll');
           }
           
           
       } else {
           
           if ( $bpm_total_submitted_votes > 0) {
               $bpm_vote_count_status = 0;
               $msg = esc_html__(' You have already submitted your\'re vote !', 'bwl-poll');
           }
           
       }

        wp_reset_query();
        
        $bpm_vote_count_status = array(
            'vote_count_status' => $bpm_vote_count_status,
            'msg' => $msg
        );
       
       return $bpm_vote_count_status;
    
}

/*

 * @Description: Add Vote counts.
 * @Since: Version 1.0.0
 * @Last Update: 08-05-2016 
 */


add_action('wp_ajax_bwl_pm_add_vote', 'bwl_pm_add_vote');
add_action( 'wp_ajax_nopriv_bwl_pm_add_vote', 'bwl_pm_add_vote' );

function bwl_pm_add_vote() {

     if ( isset($_REQUEST['cast_vote']) ) {

        // Retrieve user IP address
        
        $ip = $_SERVER['REMOTE_ADDR']; // Get user IP address.

        $poll_id = sanitize_text_field($_POST['poll_id']); // poll id.

        $choose_id = sanitize_text_field( $_POST['choose_id'] ); 
        
        $bpm_thanks_msg = "";
        
        // check login enable status.
       
        // Custom Table For Collect Voting Data.
       
        global $wpdb;
        
        $bpm_voting_data_table=  $wpdb->prefix . "bpm_vote_data"; // for deatils. each day info.
        
        //Get logged in user information.

         if ( is_user_logged_in() ) {

            $current_user = wp_get_current_user();
      
            $user_id = $current_user->ID;
            $get_user_roles = $current_user->roles;
            $user_roles = $get_user_roles[0];

        } else {

            $user_id = 0;
            $user_roles = "";

        }
        
       // If choose ID is false then we only need to display voting results.
        
        if ( $choose_id == -1 ) {
            
            //For result view and loggedin status checking.
            
            $vote_count_checkpoint_status = bpm_vote_count_checkpost( $poll_id );
            $bpm_thanks_msg = $vote_count_checkpoint_status['msg'];
             
        } else if ( $choose_id > -1 || is_array($choose_id) ) {
            
            if ( is_array( $choose_id ) ) {
                
                // For multiple answer votes.
                
                $choose_id_length = sizeof($choose_id);
                
                for( $i=0; $i < $choose_id_length; $i++ ) {
                    
                    $options_id = 'bpm_opt_' . $poll_id . '_' . $choose_id[$i];
                    
                    $opt_id = $choose_id[$i]; // require to store poll each vote data 
             
                    $meta_IP = get_post_meta($poll_id, "bpm_voted_ip");  // Get voters'IPs for the current post 

                    if (!empty( $meta_IP )) {

                        $bpm_voted_ip = $meta_IP[0];

                    } else {

                        $bpm_voted_ip = array();

                    }

                    $get_option_votes = get_post_meta( $poll_id, $options_id, true);

                    if ( $get_option_votes == "" ) {

                        $get_option_votes = 0;

                    }
                    
                    $vote_count_checkpoint_status = bpm_vote_count_checkpost( $poll_id );

                    if( $vote_count_checkpoint_status['vote_count_status'] == 1 ) { // Updated in version 1.0.4

                        $bpm_voted_ip[$ip] = time();  

                        update_post_meta($poll_id, $options_id, ++$get_option_votes);

                        update_post_meta($poll_id, "bpm_voted_ip", $bpm_voted_ip);
                       
                        
                        // Insert Each Like Vote Entry.
                        $wpdb->insert(
                          $bpm_voting_data_table,
                          array(
                             'postid'=>$poll_id,
                             'opt_id'=>$opt_id,
                             'vote'=> 1, // default is 1.
                             'voted_ip' => $_SERVER['REMOTE_ADDR'],
                             'user_id'=> $user_id, // like=1, dislike=2
                             'vote_date'=> date("Y-m-d H:i:s")
                           ),
                          array ('%d','%d','%d','%s','%d','%s')
                       );
                        

                     }
                    
                }
                
                $bpm_thanks_msg = $vote_count_checkpoint_status['msg'];
                
                
            } else {
                
                // For single asnwer votes.
                
                $options_id = 'bpm_opt_' . $poll_id . '_' . $choose_id;
                
                $opt_id = $choose_id; // require to store poll each vote data 
             
                $meta_IP = get_post_meta( $poll_id, "bpm_voted_ip" );  // Get voters'IPs for the current post 

                if (!empty( $meta_IP )) {

                    $bpm_voted_ip = $meta_IP[0];

                } else {

                    $bpm_voted_ip = array();

                }
                
//                echo "<pre>";
//                print_r($bpm_voted_ip);
//                echo "</pre>";

                $get_option_votes = get_post_meta( $poll_id, $options_id, true);

                if ( $get_option_votes == "" ) {

                    $get_option_votes = 0;

                }
                
                $vote_count_checkpoint_status = bpm_vote_count_checkpost( $poll_id );

                if( $vote_count_checkpoint_status['vote_count_status'] == 1 ) { // Updated in version 1.0.4

                    $bpm_voted_ip[$ip] = time();  

                    update_post_meta($poll_id, $options_id, ++$get_option_votes);

                    update_post_meta($poll_id, "bpm_voted_ip", $bpm_voted_ip);
                    
                    // Insert Each Like Vote Entry.
                    $wpdb->insert(
                      $bpm_voting_data_table,
                      array(
                         'postid'=>$poll_id,
                         'opt_id'=>$opt_id,
                         'vote'=> 1, // default is 1.
                         'voted_ip' => $_SERVER['REMOTE_ADDR'],
                         'user_id'=> $user_id, // like=1, dislike=2
                         'vote_date'=> date("Y-m-d H:i:s")
                       ),
                      array ('%d','%d','%d','%s','%d','%s')
                   );

                 }
                 
              }

              $bpm_thanks_msg = $vote_count_checkpoint_status['msg'];
             
        }

        $bpm_hide_poll_result = get_post_meta( $poll_id, 'bpm_hide_poll_result', TRUE);
        
        //If current user role is administrator then we are going to show the results.
        
        if ( $user_roles == "administrator" ) {
            $bpm_hide_poll_result = "";
//            $bpm_thanks_msg = "";
        }
        
        $data['status'] = 1;
        
        if ( $bpm_hide_poll_result == "" ) {
            
            $data['bpm_hide_poll_result'] = $bpm_hide_poll_result;
            $data['bpml_poll_result'] = get_bpml_poll_result( $poll_id );
            $data['bpm_ques_bg'] =  get_post_meta( $poll_id, 'bpm_ques_bg', TRUE);
            $data['bpm_ques_font_color'] =  get_post_meta( $poll_id, 'bpm_ques_font_color', TRUE);
            
            $bpm_result_display_type = "bpm_bar";
            
            if ( get_post_meta( $poll_id, 'bpm_result_display_type', TRUE ) == "bpm_chart"  ) {
                
                $bpm_result_display_type = "bpm_chart";
                $data['bpm_result_display_type'] = $bpm_result_display_type;
                $data['bpm_chart_barcolor'] =  get_post_meta( $poll_id, 'bpm_chart_barcolor', TRUE);
                $data['bpm_chart_trackcolor'] =  get_post_meta( $poll_id, 'bpm_chart_trackcolor', TRUE);
                $data['bpm_chart_scalecolor'] =  get_post_meta( $poll_id, 'bpm_chart_scalecolor', TRUE);
                $data['bpm_chart_linecap'] =  get_post_meta( $poll_id, 'bpm_chart_linecap', TRUE);
                $data['bpm_chart_linewidth'] =  get_post_meta( $poll_id, 'bpm_chart_linewidth', TRUE);
                $data['bpm_chart_animate_time'] =  get_post_meta( $poll_id, 'bpm_chart_animate_time', TRUE);
                
            } else {
                 $data['bpm_result_display_type'] = $bpm_result_display_type;
                 $data['bpml_poll_bar_bg'] = get_post_meta( $poll_id, 'bpml_poll_bar_bg', TRUE );
            }
            
            $data['msg'] = $bpm_thanks_msg;
            
            
        } else {
           
            $data['msg'] = esc_html__(' Result is hidden by administrator!', 'bwl-poll');
            $data['add_msg'] = esc_html__('Only administrator can view this poll results !', 'bwl-poll');
            $data['bpm_hide_poll_result'] = 1;
            
        }

        echo json_encode($data);
        
    }

    die();
    
}