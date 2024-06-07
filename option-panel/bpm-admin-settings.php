<?php 

/***********************************************************************************************/
/* Menus */
/***********************************************************************************************/

function bpm_add_options_page() {
    
    add_submenu_page(
            'edit.php?post_type=bwl_poll',             //sub page to settings page
            __( 'Upgrade To Pro Version', 'bwl-poll'), // The Text to be display in bte browser bar.
                __( '<b class="bpm-upgrade" style="color:#ff8405;">Upgrade To Pro</b>', 'bwl-poll'), // The Text to be display in bte browser bar.
            'manage_options', // permission
            'bpm-welcome', //slug
            'bpm_options_display' //callback
            );  
    
}


function bpm_options_display() {

    require_once 'welcome-page.php';
}

add_action('admin_menu', 'bpm_add_options_page');