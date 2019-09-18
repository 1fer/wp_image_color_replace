<div class="wrap">
    <form method="post" action="options.php" id="icr-settings">

    <?php

        // This prints out all hidden setting fields
        settings_fields( 'icr_option_group' );

        do_settings_sections( 'color_replacer' );

        submit_button('Save changes', 'button button-primary', '', false, array(
            'name' => 'save-changes'
        ));
        submit_button('Create Images', 'button button-primary', '', false, array(
            'name' => 'create-images',
            'id'   => 'create-images'
        ));

    ?>


    </form>
</div>