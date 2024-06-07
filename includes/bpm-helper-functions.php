<?php

function bpm_slug_conversion( $poll_id ) {
    
   
    
}

function bpm_text_to_slug($string) {
 
    $get_string = apply_filters('bpm_option_data', $string);
    
    if( ! empty($get_string['value']) ) {
        $string = $get_string['value'];
    } else {
        $string = $get_string;
    }
    
    if ( ! preg_match('/[^A-Za-z0-9]/', $string)) // '/[^a-z\d]/i' should also work.
    {
      // string contains only english letters & digits
        return $string = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $string)));
    } else {
        return $string;
    }
    
    
}

/*--- Calculate Percentage ---*/

function bpm_calculate_percentage( $num_total=0, $num_amount=0  ) {
    
    if($num_amount == 0) {
        
        return 0;
        
    }
 
    $count1 = $num_amount / $num_total;
    $count2 = $count1 * 100;
    $count = number_format($count2, 2);
    return $count;
    
}

/*--- Theme Helpers  ---*/

function bpm_get_theme_info( $theme ) {
    
    $theme_info = array(
        'theme_class' => '',
        'animate_class' => ''
    );
    
    // Get Theme Details.
    
    if( $theme == 'gradient') {
        
        $theme_info = array(
            'theme_class' => 'gradient bpm_02',
            'animate_class' => ''
        );
    } else if( $theme == 'glossy') {
        
        $theme_info = array(
            'theme_class' => 'gradient gloss bpm_03',
            'animate_class' => ''
        );
    } else if( $theme == 'stripe') {
        
        $theme_info = array(
            'theme_class' => 'gradient stripe bpm_04',
            'animate_class' => ''
        );
    } else if( $theme == 'animated_stripe') {
        
        $theme_info = array(
            'theme_class' => 'gradient stripe bpm_05',
            'animate_class' => 'animate'
        );
    } else if( $theme == '3d_rotate') {
        
        $theme_info = array(
            'theme_class' => 'gradient pattern2 bpm_06',
            'animate_class' => 'animate'
        );
    } else {
        
        $theme_info = array(
            'theme_class' => 'bpm_01',
            'animate_class' => ''
        );
    }
    
    return $theme_info;
    
}