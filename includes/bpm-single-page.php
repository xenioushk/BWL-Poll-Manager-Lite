<?php

/* 
 * Single Page For BWL Poll Manager
 * Introduced from version 1.0.4
 * Date: 02-08-15
 */

function bpm_single_post_content_filter( $content ) {
    
    
    if ( ! is_singular('bwl_poll') ) {
        return $content;
    }

    if ( is_singular('bwl_poll') ) {
        
        global $post;

        $poll_id = $post->ID;
        
        return $content . do_shortcode('[bwl_poll id= "'.$poll_id.'" /]');
        
    }
    
}
    
add_filter('the_content', 'bpm_single_post_content_filter', 20, 1);