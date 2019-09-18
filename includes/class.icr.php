<?php

if ( ! class_exists( 'Call_backs' ) ) {
    require_once( ICR_INC . 'class.call_backs.php' );
}

if ( ! class_exists( 'Command' ) ) {
    require_once( ICR_INC . 'class.command.php' );
}

/**
 * =============================================================================
 * Image color replacer page class
 * =============================================================================
 * @subpackage Icr_page
 * 
 * @author Panevnyk Roman <panevnyk.roman@gmail.com>
 * @since 1.0
 */
class Icr_page {

    // Holds the values to be used in the fields callbacks
    public $options;

    const ICOLOR = "blue";
    const OCOLOR = "red";
    const FUZZVAL = 40;
    const GAIN = 100;
    const THRESH = 0;
    const BRIGHTNESS = 0;
    const SATURATION = 0;

    /**
     * -------------------------------------------------------------------------
     * Lets Start Up
     * -------------------------------------------------------------------------
     * @description Press here all action and filters if needed
     * @method __construct
     * @access public
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public function __construct() {

        add_action('admin_menu',              array($this, 'add_plugin_page'));
        add_action('admin_init',              array($this, 'page_init'));
        add_action('admin_notices',           array($this, 'icr_notificate'));
        add_action('admin_enqueue_scripts',   array($this, 'scripts'));
        add_action('wp_ajax_create_image',    array($this, 'create_image'));

        $this->options = get_option('icr_option');

    }


    /**
     * -------------------------------------------------------------------------
     * Method to create filtered image
     * -------------------------------------------------------------------------
     * @method scripts
     * @access public
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public function create_image(){

        $command = new Command($this->options);

        $command->fire();

        echo ICR_URL.'includes/'.$this->options['output_image'].'.jpg';

        wp_die();

    }

    /**
     * -------------------------------------------------------------------------
     * Method to include all scripts
     * -------------------------------------------------------------------------
     * @method scripts
     * @access public
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public function scripts() {
        if (!did_action('wp_enqueue_media')){
            wp_enqueue_media();
        }
     
        wp_enqueue_script(
            'huebee', ICR_JS . 'huebee.min.js'
        );

        wp_enqueue_script(
            'functions', ICR_JS . 'functions.js', 
            array('jquery'),
            null, 
            false 
        );

        wp_enqueue_style(
            'style', ICR_CSS . 'style.css'
        );

        wp_localize_script( 'functions', 'icr', 
            array(
                'url' => admin_url('admin-ajax.php')
            )
        );
    }

    /**
     * ------------------------------------------------------------------------- 
     * Add options page
     * -------------------------------------------------------------------------
     * @description Create Page in admin menu
     * @method add_plugin_page
     * @access public
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public function add_plugin_page() {

        add_menu_page(
            'Image color replacer', 
            'Color replacer', 
            'manage_options', 
            'color_replacer',
            array( $this, 'create_admin_page' )
        );

    }

    /**
     * ------------------------------------------------------------------------- 
     * Options page callback
     * -------------------------------------------------------------------------
     * @description Include page forms
     * @method create_admin_page
     * @access public
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public function create_admin_page() {
                
        require_once( ICR_TEMPLATES . 'settings.php' );

    }

    /**
     * -------------------------------------------------------------------------
     * Register and add settings
     * -------------------------------------------------------------------------
     * @description Include page forms
     * @method pageInit
     * @access public
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public function page_init() {

        register_setting(
            'icr_option_group',                          // Option group
            'icr_option',                                // Option name
            array( $this, 'sanitize' )                   // Sanitize
        );

        add_settings_section(
            'icr_settings_section',                      // ID
            __('Image Color Replacer Settings', 'icr'),  // Title
            array('Call_backs', 'print_section_info'),   // Callback
            'color_replacer'                             // Page
        );  


        // Array of ID and Call Backs
        $setting_fields = array(

            // -i) get icolor
            'input_color'    => array( 
                'call_back'     => 'create_input',
                'description'   => __('Is the color in the input image to
                     be changed. Any valid opaque IM color is allowed.
                     The default=blue 
                     <br>Hold <b>Shift</b> on image to pick-up color', 'icr'),
                'default'       => self::ICOLOR
            ),       
            // -o) get ocolor    
            'output_color'      => array(
                'call_back'     => 'create_input',
                'description'   => __('Is the replacement color for the
                     output image. Any valid opaque IM color is allowed. 
                     The default=red', 'icr'),
                'default'       => self::OCOLOR
            ),          
            // -f) get fuzzval 
            'fuzzval'       => array(
                'call_back'     => 'create_input',
                'description'   => __('Fuzzval on each side of the old color for
                     the range of input hues to be recolored. 
                     Values are 0<=float<=180 degrees. The default=40.', 'icr'),
                'default'       => self::FUZZVAL
            ),   
            // -t) get thresh
            'thresh'        => array(
                'call_back'     => 'create_input',
                'description'   => __('Value in percent for forcing 
                    low saturation colors to zero saturation, i.e. converts near 
                    gray (white through black) to pure gray. Values are 
                    floats>=0. The default=0.', 'icr'),
                'default'       => self::THRESH
            ),   
            // -s) get saturation
            'saturation'    => array(
                'call_back'     => 'create_input',
                'description'   => __('Is the percent additional 
                    change in saturation. Values are -100<=integer<=100. 
                    The default=0 (no change).', 'icr'),
                'default'       => self::SATURATION
            ),    
             // -b) get brightness
            'brightness'    => array(
                'call_back'     => 'create_input',
                'description'   => __('Is the percent additional 
                    change in brightness. Values are integer>=-100. 
                    The default=0 (no change).', 'icr'),
                'default'       => self::BRIGHTNESS
            ),  
            // -g) get gain        
           'gain'          => array(
               'call_back'     => 'create_input',
               'description'   => __('Gain on color conversion. Values are 
                   integers>=0. 
                   The default=100.', 'icr'),
               'default'       => self::GAIN
           ),       
            // -o) output image name
            'output_image'    => array(
                'call_back'     => 'create_input',
                'description'   => __('Output image name.', 'icr'),
                'default'       => 'ifer'
            )
        );

        add_settings_field(
            'image_box',                                    // ID
            'Select image',                                 // Title
            array('Call_backs', 'print_box'),               // Callback
            'color_replacer',                               // Page
            'icr_settings_section',                         // Section    
            array($this)                                    // Arguments                             
        );

        foreach($setting_fields as $id => $setting){

            $title = ucfirst(str_replace('_', ' ', $id));

            add_settings_field(
                $id,                                        // ID
                __($title, 'icr'),                          // Title
                array('Call_backs', $setting['call_back']), // Callback
                'color_replacer',                           // Page
                'icr_settings_section',                     // Section    
                array(                                      // Arguments 
                    'options'    => $this, 
                    'name'       => $id, 
                    'desciption' => $setting['description'], 
                    'default'    => $setting['default']
                )                             
            );

        }

    }

    /**
     * -------------------------------------------------------------------------
     * Notification on Berni Settings page
     * -------------------------------------------------------------------------
     * @description Event on save fields
     * @method icr_notificate
     * @access public
     * 
     * @return Mixed
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public function icr_notificate(){
            

        if(isset($_GET['page']) && $_GET['page'] == 'color_replacer' && 
          (isset($_GET['settings-updated']) && $_GET['settings-updated'] == true)){

            add_settings_error(
                'icr-notices', 
                'updated', 
                __('Settings saved.', 'icr'), 
                'updated' 
            );

        }
            
        settings_errors('icr-notices');
            
    }

    /**
     * -------------------------------------------------------------------------
     * Sanitize each setting field as needed
     * -------------------------------------------------------------------------
     * @description Save fields
     * @method sanitize
     * @access public
     * @param Array $input Contains all settings fields as array keys
     * 
     * @return Mixed
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public function sanitize( $input ) {

        $new_input = array();

        foreach($input as $key => $value){
            $new_input[$key] = sanitize_text_field($value);
        }

        return $new_input;
        
    }

}

if(is_admin()){
    new Icr_page();
}