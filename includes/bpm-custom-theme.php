<?php


if( !function_exists('bpm_custom_theme')) {
        
    function bwl_poll_custom_theme() {
        
        $bwl_pm_data = get_option('bwl_poll_options');
        
        /*---------------- Custom CSS -----*/
        
        $bwl_poll_custom_css_status = 0;
        
        $bwl_poll_custom_css = "";
        
        $bwl_poll_custom_css .= '<style type="text/css">';
        
        if( isset( $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['enabled'] ) && $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['enabled'] == 'on' && isset( $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_fonts'] ) && $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_fonts'] != 'none' ) { 
            
            $bwl_poll_custom_css .= "div.bpm-container{font-family: " . str_replace( "+", " " , $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_fonts'] )  . ";font-size: " . $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_title_fonts_size'] . "px;}";
            $bwl_poll_custom_css_status = 1; // change status value.
            
        }
        
         if( isset( $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['enabled'] ) && $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['enabled'] == 'on' && isset( $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_option_fonts'] ) && $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_option_fonts'] != 'none' ) { 
            
            $bwl_poll_custom_css .= "div.bpm-question-container .bpm-poll-options li{font-family: " . str_replace( "+", " " , $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_option_fonts'] )  . ";font-size: " . $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_option_fonts_size'] . "px;}";
            $bwl_poll_custom_css_status = 1; // change status value.
            
        }
        
         if( isset( $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['enabled'] ) && $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['enabled'] == 'on' && isset( $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_button_fonts'] ) && $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_button_fonts'] != 'none' ) { 
            
            $bwl_poll_custom_css .= "button.btn-poll-vote-now,button.btn-poll-flip, div.bpm-container a.bpm_edit{font-family: " . str_replace( "+", " " , $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_button_fonts'] )  . ";font-size: " . $bwl_pm_data['bwl_poll_fonts_conditinal_fields']['bpm_google_button_fonts_size'] . "px;}";
            $bwl_poll_custom_css_status = 1; // change status value.
            
        }
        
        if( isset( $bwl_pm_data['bwl_poll_custom_css'] ) && $bwl_pm_data['bwl_poll_custom_css']!="" ) {
          
            $bwl_poll_custom_css .= $bwl_pm_data['bwl_poll_custom_css'];
            $bwl_poll_custom_css_status = 1; // change status value.
            
        }
        
        $bwl_poll_custom_css .= '</style>';
        
        if( $bwl_poll_custom_css_status == 1 ) {
            
            echo $bwl_poll_custom_css;
            
        } else {
            
            echo ""; // echo "blank".
            
        }
        
    }
    
    
    add_action('wp_head', 'bwl_poll_custom_theme');
    
}