<?php
// Add to our admin_init function

add_action('quick_edit_custom_box', 'bpm_add_quick_edit', 10, 2);

function bpm_add_quick_edit($column_name, $post_type) {

    switch ($post_type) {

        case 'bwl_poll': // 

            switch ($column_name) {

                case 'bpm_total_options':
                    ?>

                    <fieldset class="inline-edit-col-right">
                        <div class="inline-edit-col">
                            <div class="inline-edit-group">
                                <label class="inline-edit-status alignleft">
                                    <span class="title"><?php _e('Reset Poll', 'bwl-poll'); ?></span>
                                    <select name="bpm_votes_reset" id="bpm_votes_reset">
                                        <option value="">- Select -</option>
                                        <option value="1"><?php _e('Yes', 'bwl-poll'); ?></option>
                                        <option value="0"><?php _e('No', 'bwl-poll'); ?></option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <?php
                    break;
            }

            break;
    }
}

// Add to our admin_init function

add_action('save_post_bwl_poll', 'bpm_save_quick_edit_data');

function bpm_save_quick_edit_data($post_id) {

    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    // OK, we're authenticated: we need to find and save the data

    $post = get_post($post_id);

    if (isset($_POST['bpm_votes_reset']) && ($post->post_type != 'revision')) {

        $bpm_votes_reset = sanitize_text_field( $_POST['bpm_votes_reset'] );

        if ($bpm_votes_reset == 1) {

            $poll_id = $post_id;

//            $all_poll_options = get_post_meta( $poll_id, 'bpm_options' );
            $all_poll_options = apply_filters('bpm_option_data', get_post_meta($poll_id, 'bpm_options'), $poll_id);

            if (sizeof($all_poll_options) > 0) {

                foreach ($all_poll_options as $poll_key => $poll_value) {

                    $unique_poll_value = $poll_value['opt_id'];

                    $options_id = 'bpm_opt_' . $poll_id . '_' . $unique_poll_value;

                    update_post_meta($poll_id, $options_id, 0);
                }
            }

            // We need to run another delete query in here.

            global $wpdb;

            /* --- delete data  --- */

            $bpm_voting_data_table = $wpdb->prefix . "bpm_vote_data"; // for deatils. each day info.

            $wpdb->delete(
                    $bpm_voting_data_table, array('postid' => $poll_id), array('%d') // Where Format 
            );

            wp_reset_query();
        }
    }

    return '';
}
