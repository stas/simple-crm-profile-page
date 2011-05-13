<form action="" method="post">
    <?php wp_nonce_field( 'scrm_pp', 'scrm_pp_nonce' ); ?>
    <div class="postbox">
        <h3 class="hndle" ><?php _e( 'Public Profile Page Info','scrm_pp' )?></h3>
        <div class="inside">
            <div class="scrm-field-form">
                <p class="form-field">
                    <?php _e( 'Just create a page and include the shortcode <code>[scrm_pp]Some text[/scrm_pp]</code> in content area.','scrm_pp' )?>
                    <?php _e( 'Paste the permalink of the page below.','scrm_pp' )?>
                </p>
                <p class="form-field">
                    <?php _e( 'You can also add <code>[scrm_pp hide="PARAM"]</code> to hide specific fields.','scrm_pp' )?>
                </p>
                <p class="form-field">
                    <?php _e( 'Example:','scrm_pp' )?>
                    <code>[scrm_pp hide="aim,yim,jabber,twitter,password"]</code>
                </p>
                <p class="form-field">
                    <?php _e( 'Simple CRM will handle after login redirects and profile updates.','scrm_pp' )?>
                </p>
                <p class="form-field">
                    <label for="scrm_pp_permalink">
                        <strong><?php _e( 'Public Profile Page Permalink','scrm_pp' )?></strong>
                    </label>
                    <br />
                    <input id="scrm_pp_page" name="scrm_pp[permalink]" type="text" value="<?php echo $permalink; ?>"/>
                </p>
            </div>
            <p>
                <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' )?>"/>
            </p>
        </div>
    </div>
</form>