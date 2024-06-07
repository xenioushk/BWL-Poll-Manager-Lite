<?php

/* ---  Custom Meta Box Section --- */
include_once 'bpm_cmb_framework.php';

// Register Custom Meta Box For BWL Pro Related Post Manager

function bpm_custom_meta_init() {

    // Start Coding From here.

    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');

    wp_register_script('bpm-cmb-colorpicker', BWL_PM_PLUGIN_DIR . 'includes/bpm-cmb-framework/admin/js/colorpicker.js', array('jquery'), false, false);
    wp_register_script('bpm-cmb-admin-main', BWL_PM_PLUGIN_DIR . 'includes/bpm-cmb-framework/admin/js/bpm_cmb.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-draggable', 'jquery-ui-droppable'), false, false);
    wp_register_style('bpm-cmb-color-picker-style', BWL_PM_PLUGIN_DIR . 'includes/bpm-cmb-framework/admin/css/colorpicker.css', array(), false, 'all');
    wp_register_style('bpm-cmb-admin-style', BWL_PM_PLUGIN_DIR . 'includes/bpm-cmb-framework/admin/css/bpm_cmb.css', array(), false, 'all');

    wp_enqueue_style('bpm-cmb-color-picker-style');
    wp_enqueue_style('bpm-cmb-admin-style');
    wp_enqueue_script('bpm-cmb-colorpicker');
    wp_enqueue_script('bpm-cmb-admin-main');


    //Boolean Support.

    $bpm_boolean_support = array(
        '' => esc_html__('Select', 'bwl-poll'),
        '1' => esc_html__('Yes', 'bwl-poll'),
        '0' => esc_html__('No', 'bwl-poll')
    );


    //Default Poll Result Themes.

    $bpm_theme_lists = array(
        'basic' => 'Style 1 (BASIC)',
        'gradient' => 'Style 2 (GRADIENT)',
        'glossy' => 'Style 3 (GLOSSY)'
    );


    global $bpm_data;

    $bpm_custom_post_types = array('bwl_poll');

    foreach ($bpm_custom_post_types as $bpm_custom_post_types_key => $bpm_custom_post_types_value) {

        //@Description: Option Manager Settings
        //@Date: 10-05-16
        //@Since: version 1.0.4

        $bpm_related_custom_fields = array(
            'meta_box_id' => 'cmb_bpm_options', // Unique id of meta box.
            'meta_box_heading' => 'Poll Options Manager', // That text will be show in meta box head section.
            'post_type' => 'bwl_poll', // define post type. go to register_post_type method to view post_type name.        
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                'bpm_options' => array(
                    'title' => esc_html__('Add Poll Options ', 'bwl-poll'),
                    'id' => 'bpm_options',
                    'name' => 'bpm_options',
                    'type' => 'repeatable_text',
                    'value' => '',
                    'default_value' => 1,
                    'class' => 'widefat'
                )
            )
        );


        new BPM_Meta_Box($bpm_related_custom_fields);


        //@Description: Answer Type Settings
        //@Date: 10-05-16
        //@Since: version 1.0.4

        $bpm_answer_type_custom_fields = array(
            'meta_box_id' => 'cmb_bpm_answer_type_options', // Unique id of meta box.
            'meta_box_heading' => 'Answer Type Settings', // That text will be show in meta box head section.
            'post_type' => 'bwl_poll', // define post type. go to register_post_type method to view post_type name.        
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                'bpm_ans_type' => array(
                    'title' => esc_html__('Answer Type', 'bwl-poll'),
                    'id' => array('bpm_answer_type', 'bpm_maxiumum_answer'),
                    'name' => array('bpm_answer_type', 'bpm_maxiumum_answer'),
                    'type' => 'bpm_ans_type',
                    'value' => '',
                    'default_value' => '',
                    'class' => 'widefat'
                )
            )
        );


        new BPM_Meta_Box($bpm_answer_type_custom_fields);

        //@Description: Theme Settings
        //@Date: 10-05-16
        //@Since: version 1.0.4

        $bpm_theme_fields = array(
            array('id' => 'bpm_bg', 'name' => esc_html__('Poll BG', 'bwl-poll'), 'type' => 'colorpicker', 'default' => '#fafafaf'),
            array('id' => 'bpm_ques_bg', 'name' => esc_html__('Question BG', 'bwl-poll'), 'type' => 'colorpicker', 'default' => '#fafafa'),
            array('id' => 'bpm_ques_font_color', 'name' => esc_html__('Question text color', 'bwl-poll'), 'type' => 'colorpicker', 'cols' => 3, 'default' => '#2C2C2C'),
            array('id' => 'bpm_opt_font_color', 'name' => esc_html__('Option text color', 'bwl-poll'), 'type' => 'colorpicker', 'cols' => 3, 'default' => '#333333'),
            array('id' => 'bpm_border_color', 'name' => esc_html__('Border color', 'bwl-poll'), 'type' => 'colorpicker', 'default' => '#eeeeee'),
            array('id' => 'bpm_option_odd_bg', 'name' => esc_html__('Odd row bg', 'bwl-poll'), 'type' => 'colorpicker', 'default' => '#f2f2f2'),
            array('id' => 'bpm_option_even_bg', 'name' => esc_html__('Even row bg', 'bwl-poll'), 'type' => 'colorpicker', 'default' => '#fcfcfc'),
            array('id' => 'bpm_bottom_bar_bg', 'name' => esc_html__('Footer bar bg', 'bwl-poll'), 'type' => 'colorpicker', 'default' => '#fafafa')
        );

        $bpm_theme_custom_fields = array(
            'meta_box_id' => 'cmb_bpm_theme_options', // Unique id of meta box.
            'meta_box_heading' => 'Theme Settings', // That text will be show in meta box head section.
            'post_type' => 'bwl_poll', // define post type. go to register_post_type method to view post_type name.        
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                'bpm_theme_settings' => array(
                    'title' => esc_html__('Test', 'bwl-poll'),
                    'id' => $bpm_theme_fields,
                    'name' => $bpm_theme_fields,
                    'type' => 'bpm_themes',
                    'value' => '',
                    'default_value' => '',
                    'class' => 'widefat'
                )
            )
        );

        new BPM_Meta_Box($bpm_theme_custom_fields);

        //@Description: Result Display Settings
        //@Date: 10-05-16
        //@Since: version 1.0.4

        $bpm_bar_animation_fields = array(
//                array( 'id' => 'bpm_result_display_type',  'name' => __( 'Result Display Type' , 'bwl-poll' ), 'type' => 'select', 'options' => array( 'bpm_bar' => 'Bar', 'bpm_chart'=> 'Chart' ),  'default'=> 'bpm_bar', 'group'=> '' ),
            array('id' => 'bpm_result_display_type', 'name' => esc_html__('Result Display Type', 'bwl-poll'), 'type' => 'select', 'options' => array('bpm_bar' => 'Bar', 'bpm_chart_' => 'Chart (Pro Version)'), 'default' => 'bpm_bar', 'group' => ''),
            array('id' => 'bpm_sort_result_status', 'name' => esc_html__('Sort options by top vote', 'bwl-poll'), 'type' => 'select', 'options' => array('' => 'Select', '1' => 'Yes', '0' => 'No'), 'default' => 'bpm_bar', 'group' => ''),
            array('id' => 'bpm_bar_settings_title', 'name' => esc_html__('Bar Settings', 'bwl-poll'), 'type' => 'title', 'group' => 'grp_bar'),
            array('id' => 'bpm_bar_theme', 'name' => esc_html__('Available Bar Themes', 'bwl-poll'), 'type' => 'select', 'cols' => 9, 'options' => $bpm_theme_lists, 'allow_none' => true, 'sortable' => false, 'repeatable' => false, 'default' => 'basic', 'group' => 'grp_bar'),
            array('id' => 'bpml_poll_bar_bg', 'name' => esc_html__('Bar Background', 'bwl-poll'), 'type' => 'bgcolor', 'cols' => 3, 'default' => '#333333', 'group' => 'grp_bar'),
            array('id' => 'bpm_chart_settings_title', 'name' => esc_html__('Chart Settings', 'bwl-poll'), 'type' => 'title', 'group' => 'grp_chart'),
            array('id' => 'bpm_chart_barcolor', 'name' => esc_html__('Bar Color', 'bwl-poll'), 'type' => 'bgcolor', 'cols' => 4, 'default' => '#ef1e25', 'group' => 'grp_chart'),
            array('id' => 'bpm_chart_trackcolor', 'name' => esc_html__('Track Color', 'bwl-poll'), 'type' => 'bgcolor', 'cols' => 4, 'default' => '#f2f2f2', 'group' => 'grp_chart'),
            array('id' => 'bpm_chart_scalecolor', 'name' => esc_html__('Scale Color', 'bwl-poll'), 'type' => 'bgcolor', 'cols' => 4, 'default' => '#dfe0e0', 'group' => 'grp_chart'),
            array('id' => 'bpm_chart_linecap', 'name' => esc_html__('Line Cap Type', 'bwl-poll'), 'type' => 'select', 'cols' => 4, 'options' => array('round' => 'Round', 'square' => 'Square'), 'allow_none' => true, 'sortable' => false, 'repeatable' => false, 'default' => 'round', 'group' => 'grp_chart'),
            array('id' => 'bpm_chart_linewidth', 'name' => esc_html__('Line Cap Width', 'bwl-poll'), 'type' => 'select', 'cols' => 4, 'options' => array('3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9'), 'allow_none' => true, 'sortable' => false, 'repeatable' => false, 'default' => '4', 'group' => 'grp_chart'),
            array('id' => 'bpm_chart_animate_time', 'name' => esc_html__('Animation Time', 'bwl-poll'), 'type' => 'select', 'cols' => 4, 'options' => array('3000' => '3000', '4000' => '4000', '5000' => '5000', '6000' => '6000', '7000' => '7000', '8000' => '8000', '9000' => '9000'), 'allow_none' => true, 'sortable' => false, 'repeatable' => false, 'default' => '3000', 'group' => 'grp_chart')
        );

        $bpm_result_display_custom_fields = array(
            'meta_box_id' => 'cmb_result_display_options', // Unique id of meta box.
            'meta_box_heading' => 'Result Display Settings', // That text will be show in meta box head section.
            'post_type' => 'bwl_poll', // define post type. go to register_post_type method to view post_type name.        
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                'bpm_result_display_settings' => array(
                    'title' => '',
                    'id' => $bpm_bar_animation_fields,
                    'name' => $bpm_bar_animation_fields,
                    'type' => 'bpm_result_display',
                    'value' => '',
                    'default_value' => '',
                    'class' => 'widefat'
                )
            )
        );

        new BPM_Meta_Box($bpm_result_display_custom_fields);

        //@Description: Advanced Settings
        //@Date: 10-05-16
        //@Since: version 1.0.4

        $bpm_advanced_fields = array(
            array('id' => 'bpm_rtl_support', 'name' => esc_html__('RTL Support', 'bwl-poll'), 'type' => 'select', 'options' => $bpm_boolean_support, 'default' => '', 'group' => ''),
            array('id' => 'bpm_share_btn', 'name' => esc_html__('Display Share Button?', 'bwl-poll'), 'type' => 'select', 'options' => $bpm_boolean_support, 'default' => '', 'group' => '')
        );

        $bpm_advanced_custom_fields = array(
            'meta_box_id' => 'cmb_advanced_options', // Unique id of meta box.
            'meta_box_heading' => 'Advanced Settings', // That text will be show in meta box head section.
            'post_type' => 'bwl_poll', // define post type. go to register_post_type method to view post_type name.        
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                'bpm_advanced_settings' => array(
                    'title' => '',
                    'id' => $bpm_advanced_fields,
                    'name' => $bpm_advanced_fields,
                    'type' => 'bpm_advanced_settings',
                    'value' => '',
                    'default_value' => '',
                    'class' => 'widefat'
                )
            )
        );

        new BPM_Meta_Box($bpm_advanced_custom_fields);

        //@Description: Poll Result Settings
        //@Date: 10-05-16
        //@Since: version 1.0.4

        $bpm_result_fields = array(
            'meta_box_id' => 'cmb_bpm_result_settings', // Unique id of meta box.
            'meta_box_heading' => esc_html__('Poll Result Settings', 'bwl-kb'), // That text will be show in meta box head section.
            'post_type' => $bpm_custom_post_types_value, // define post type. go to register_post_type method to view post_type name.        
            'context' => 'side',
            'priority' => 'low',
            'fields' => array(
                'bpm_result' => array(
                    'title' => esc_html__('Results', 'bwl-kb'),
                    'id' => 'bpm_result',
                    'name' => 'bpm_result',
                    'type' => 'bpm_result_info',
                    'value' => '',
                    'default_value' => '',
                    'class' => 'widefat'
                )
            )
        );


        new BPM_Meta_Box($bpm_result_fields);
    }
}

// META BOX START EXECUTION FROM HERE.

add_action('admin_init', 'bpm_custom_meta_init');
