<?php

/**
 * =============================================================================
 * Image color replacer Call Backs class
 * =============================================================================
 * 
 * @subpackage Icr
 * 
 * @author Panevnyk Roman <panevnyk.roman@gmail.com>
 * @since 1.0
 */
class Call_backs{

    /**
     * -------------------------------------------------------------------------
     * Print the Section text
     * -------------------------------------------------------------------------
     * @method printSectionInfo
     * @access public
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public static function print_section_info() {

        print 'Enter your settings below:';

    }

    /**
     * -------------------------------------------------------------------------
     * Get the settings option array and print one of its values
     * -------------------------------------------------------------------------
     * @method create_input
     * @access public
     * @param Object $data
     * @param String $name
     * @param String $desciption
     * @param Mixed  $default
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public static function create_input($data) {

        $class = '';
        if($data['name'] == 'input_color' || $data['name'] == 'output_color'){
            $class = "color-input";
        }

        printf(
            '<input type="text" id="'.$data['name'].'" 
                    name="icr_option['.$data['name'].']" 
                    class="regular-text '.$class.' code" value="%s" /><p>%s</p>',
            isset($data['options']->options[$data['name']]) ? 
            esc_attr($data['options']->options[$data['name']]):$data['default'],
            $data['desciption']
        );

    }
    
    /**
     * -------------------------------------------------------------------------
     * Method for uploading images
     * -------------------------------------------------------------------------
     * @method image_uploader_field
     * @access public
     * @param Object $data
     * @param String $name
     * @param String $desciption
     * @param Mixed  $default
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    private static function image_uploader_field( $name, $value = '') {

        $image      = ' button">Upload image';
        $image_size = 'full'; 
        $display    = 'none';
     
        if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
     
            // $image_attributes[0] - image URL
            // $image_attributes[1] - image width
            // $image_attributes[2] - image height
     
            $image = '"><img src="' . $image_attributes[0] . '" 
                             style="max-width:95%;display:block;" />';
            $display = 'inline-block';
     
        }
     
        return '
        <div>
            <a href="#" class="upload_image_button' . $image . '</a>
            <input type="hidden" name="icr_option[' . $name . ']" 
                   id="' . $name . '" 
                   value="' . esc_attr( $value ) . '" />
            <a href="#" class="remove_image_button" 
               style="display:inline-block;display:' . $display . '">
               Remove image
            </a>
        </div>';
    }


    /**
     * -------------------------------------------------------------------------
     * Method for uploading images
     * -------------------------------------------------------------------------
     * @method image_uploader_field
     * @access public
     * @param Object $data
     * @param String $name
     * @param String $desciption
     * @param Mixed  $default
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public static function print_box( $data ) {

        $option = isset($data[0]->options['image_box']) ? $data[0]->options['image_box'] : 0;

        echo self::image_uploader_field(
            'image_box', $option
        );

    }

}