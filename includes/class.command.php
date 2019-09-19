<?php

/**
 * =============================================================================
 * Command class
 * =============================================================================
 * 
 * @subpackage Icr
 * 
 * @author Panevnyk Roman <panevnyk.roman@gmail.com>
 * @since 1.0
 *
 * Do processing : 
 * 
 * - Convert to depth 8
 * - Separate hsl channels
 * - Make constant image of ohue
 * - Multiply saturation channel by desired amount
 * - Multiply brightness channel by desired amount
 * - Combine modified hsl channels from previous lines and convert back to RGB
 * - Create binary hue mask for desired fuzz values
 * - Modify sat channel to threshold to 0 low saturation values and to apply 
 *   gain to other saturation values as saturation mask
 * - Composite last two images (hue mask and enhanced saturation mask image)
 * - Delete temporary images
 * - Composite original, modified RGB image and mask image
 * - Write output
 * 
 */
class Command{

    /**
     * @var Array main list of commands
     */
    private $config;

    /**
     * @var String dir of incomming file
     */
    private $image_url;

    /**
     * @var String dir of uploaded file
     */
    private $output_name;

    /**
     * -------------------------------------------------------------------------
     * Lets Start Up
     * -------------------------------------------------------------------------
     * @description Consturct all agruments
     * @method __construct
     * @access public
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public function __construct($config) {

        $this->config = [
            '-i' => $config['input_color'],
            '-o' => $config['output_color'],
            '-f' => $config['fuzzval'],
            '-g' => $config['gain'],
            '-t' => $config['thresh'],
            '-b' => $config['brightness'],
            '-s' => $config['saturation']
        ];

        $this->image_url    = get_attached_file($config['image_box']);
        $this->output_name  = $config['output_image'];
        
    }
    
    /**
     * -------------------------------------------------------------------------
     * Method to run command string
     * -------------------------------------------------------------------------
     * @description Consturct all agruments
     * @method fire
     * @access public
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    public function fire(){

        $command = self::build_command();

        @shell_exec($command);

    }

    /**
     * -------------------------------------------------------------------------
     * Method to build command string
     * -------------------------------------------------------------------------
     * @description Consturct all agruments
     * @method build_command
     * @access public
     * 
     * @return Void
     * @author <panevnyk.roman@gmail.com>
     * @since 1.0
     */
    private function build_command() {
        
        $command = ICR_INC.'replacecolor ';

        foreach($this->config as $flag => $value){

            $s = ($flag == '-i' || $flag == '-o') ? ' " ' : ' ';

            if($value != '')
                $command .= $flag.$s.$value.$s;

        }

        $command .= $this->image_url.' '.ICR_INC.$this->output_name.'.jpg 2>&1';

        return $command;

    }

}