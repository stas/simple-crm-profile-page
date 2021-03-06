<?php echo $content; ?>
<div id="message">
    <?php 
        $user_id = get_current_user_id();
        $updated_id = get_transient( 'scrm_pp_updated_id' );
        // Ask for login if not authenticated, or somebody is trying to do bad things
        if( !$user_id || ( $updated_id != false && $updated_id != $user_id ) ) {
            wp_loginout( get_permalink(), true );
            echo '</div>'; //Closing div for id="message"
            return;
        }
        // Check for messages
        if( $updated_id == $user_id )
            _e( 'User updated.' );
        
        // Hook form processing here
        do_action( 'scrm_pp_form' );
        do_action( 'personal_options_update', $user_id );
        $profile = get_userdata( $user_id );
    ?>
</div>
<form action="" method="post" id="scrm_pp_form">
    <?php wp_nonce_field( 'scrm_pp_form', 'scrm_pp_form_nonce' ); ?>
    
    <?php foreach ( $to_show as $key => $label ) : ?>
        <?php if ( !in_array( $key, $to_hide ) ) : ?>
        <div class="<?php echo $key ?>-field">
            <div><label for="<?php echo $key ?>"><?php echo $label ?></label></div>
            <div>
                <input type="text" name="<?php echo $key ?>" id="<?php echo $key ?>" value="<?php echo esc_attr($profile->$key) ?>" <?php $key == 'user_login' ? disabled( true ) : ''; ?> />
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <?php foreach ( _wp_get_user_contactmethods( $profile ) as $name => $desc ) : ?>
        <?php if ( !in_array( $name, $to_hide ) ) : ?>
        <div class="<?php echo $name ?>-field">
            <div>
                <label for="<?php echo $name; ?>"><?php echo apply_filters('user_'.$name.'_label', $desc); ?></label>
            </div>
            <div>
                <input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr($profile->$name) ?>" />
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <?php if ( !in_array( 'description', $to_hide ) ) : ?>
    <div class="description-field">
        <div><label for="description"><?php _e('Biographical Info'); ?></label></div>
        <div>
            <textarea name="description" id="description" rows="5" cols="30"><?php
                echo esc_attr( $profile->description );
            ?></textarea>
            <br />
            <em><?php _e('Share a little biographical information to fill out your profile. This may be shown publicly.'); ?></em>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ( !in_array( 'password', $to_hide ) ) : ?>
    <div class="password-field">
        <div><label for="pass1"><?php _e('New Password'); ?></label></div>
        <div>
            <input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" />
            <br/>
            <input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" />
            <em class="description"><?php _e("Type your new password again."); ?></em>
        </div>
    </div>
    <?php endif; ?>
    
    <?php do_action( 'show_user_profile', $profile ); ?>
    
    <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $profile->ID ); ?>" />
    <input type="submit" name="submit" id="submit" value="<?php _e('Update Profile') ?>" />
</form>