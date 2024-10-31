<?php
/*
Plugin Name: PHP - Printing House Pricer
Plugin URI: http://www.tipografix.ro/plugin-wordpress.html
Description: Calculeaza preturile pentru produse tipogafice bazate pe tiraj
Version: 1.0.2
Author: Gabriel Braila
Author URI: https://plus.google.com/u/0/107467003969505231772/posts
License: GPL2

Copyright 20012  Gabriel Braila  innermond@yahoo.com

*/


// debug mode
// error_reporting(E_ALL);
//TODO: rewrite (next version)a little bit to use WP_Widget class for future compatibility
class Calculator
{
    const MESSAGE_DEFAULT = 'invalid';

    static public
    $default = array
    (
        'title' => 'Calculator Tipar', 

        'papersheet_square' => 0.7,
        'papersheet_square_cutted_on_number' => 4,
        'maximum_print_area' => 'A3', //'35 x 50 cm',
        'papersheet_starting' => 100,
        'papersheet_maxim_formats'  => 28,
        'velocity' => 4000,
        'printing_hour_price' => 30,
        'paper_weights' => array(70, 80, 90, 115, 130, 150, 170, 200, 250, 300),
        'folds_price' => array('0' => 0, '1' => 0.004, '2' => 0.006, '3' => 0.01),  
        'plate_price' => 5,

        'currency' => 'euro',
        'vat' => 24,

        'format' => array(1 => 'A3', 2 => 'A4', 4 => 'A5', 6 => 'A4/3', 8 => 'A6', 16 => 'A7'),

        'thanks_text_line' => 'Energised by',
        'thanks_text_link' => 'the print firms friend',
    ),

     $errors = array
    (
        'formats' => 'formats must be a number up to $1', 
        'copies_number' => 'copies must be a number greater than zero'
    );

    // global only here, primarily to be extracted to the view 
    static protected
    $options = array();

    static function rules()
    {
        return array (
          'title' => Validate::text('title'),
          'currency' => Validate::text('currency'),
          'papersheet_square' => Validate::float(0.0, 1.0, 'papersheet_square'),
          'papersheet_square_cutted_on_number' => Validate::int(1, null, 'papersheet_square_cutted_on_number'),
          'maximum_print_area' => Validate::text('maximum_print_area'),
          'papersheet_starting' => Validate::int(0, null, 'papersheet_starting'),
          'papersheet_maxim_formats' => Validate::int(1, 50, 'papersheet_maxim_formats'),
          'velocity' => Validate::int(1, null, 'velocity'),
          'printing_hour_price' => Validate::price('printing_hour_price'),
          'plate_price' => Validate::price('plate_price'),
          'vat' => Validate::int(1, null, 'vat'),
          'vat_acronym' => Validate::text('vat_acronym'),
          'format_keys' => Validate::int(1, 30, 'format_keys'),
          'format' => Validate::regex('/^[a-z\-\/0-9\s]{1,}$/i', 'format'),
          'paper_weights' => Validate::int(50, 400, 'paper_weights'),
          'folds_price' => Validate::float(0.0, null, 'folds_price'),
          'folds_price_keys' => Validate::int(0, null, 'folds_price_keys'),
        );
    }

    static function messages()
    {
        return array(
            'title' => array('_regex'=> __('":val" incorect', 'calculator-tipar')),
            'currency' => array('_regex'=> __('":val" incorect', 'calculator-tipar')),
            'papersheet_square' => array('_float'=> __('":val" intre $0 si $1 ?', 'calculator-tipar')),
            'papersheet_square_cutted_on_number' => array('_int'=> __('":val" este $0 sau mai mare ?', 'calculator-tipar')),
            'maximum_print_area' => array('_regex'=> __('":val" incorect', 'calculator-tipar')),
            'papersheet_starting' => array('_int'=> __('":val" este $0 sau mai mare ?', 'calculator-tipar')),
            'papersheet_maxim_formats' => array('_int'=> __('":val" intre $0 si $1 ?', 'calculator-tipar')),
            'velocity' => array('_int'=> __('":val" este $0 sau mai mare ?', 'calculator-tipar')),
            'printing_hour_price' => array('_float'=> __('":val" este $0 sau mai mare ?', 'calculator-tipar')),
            'plate_price' => array('_float'=> __('":val" este $0 sau mai mare ?', 'calculator-tipar')),
            'vat' => array('_int'=> __('":val" este $0 sau mai mare ?', 'calculator-tipar')),
            'vat_acronym' => array('_regex'=> __('":val" doar litere ?', 'calculator-tipar')),
            'format_keys' => array('_int'=> __('":val" intre $0 si $1 ?', 'calculator-tipar')),
            'format' => array('_regex'=> __('":val" incorect', 'calculator-tipar')),            
            'paper_weights' => array('_int'=> __('":val" intre $0 si $1 ?', 'calculator-tipar')),            
            'folds_price' => array('_float'=> __('":val" este $0 sau mai mare ?', 'calculator-tipar')),
            'folds_price_keys' => array('_int'=> __('":val" este $0 sau mai mare ?', 'calculator-tipar')),
        );
    }

    static function init()
    {

        // insert into admin menus
        if (is_admin())
        {
            add_action( 'admin_menu', 'Calculator::admin_menu');
            add_action('wp_ajax_update_options', 'Calculator::update_options');
        }

        register_activation_hook( __FILE__, 'Calculator::activate');
        register_deactivation_hook( __FILE__, 'Calculator::deactivate');
        // prepare widget
        add_action('widgets_init', 'Calculator::register');

        // translate
        add_action('plugins_loaded', 'Calculator::translate');
    }

    static  function translate()
    {
        load_plugin_textdomain( 'calculator-tipar', false, dirname( plugin_basename( __FILE__ ) ).'/languages' );

    }

    static function admin_menu() 
    {

        add_menu_page(
            'calculator offset','Calculator','manage_options','calculator_tipar_offset', array('Calculator', '_help.php')); 
        add_submenu_page('calculator_tipar_offset', 
            'panou control','Panou control', 'manage_options', 'calculator_tipar_offset_panel','Calculator::view_settings');
    }

    static function __callStatic($func, $args)
    {
        $view = substr($func, 1 + strpos($func, '_'));

        self::view($view);
    }

    static function activate()
    {
        update_option(self::$default['title'] , self::$default);
    }

    static function deactivate()
    {
        delete_option(self::$default['title']);
    }

    static function register()
    {
        // html showed on public
        wp_register_sidebar_widget(self::$default['title'].'_1', self::$default['title'], 'Calculator::widget');

        wp_enqueue_script('jquery');

        if ( ! is_admin())
        {
            // prepare js calculator-tipografic.js
            $url_js = plugins_url(basename(dirname(__FILE__))).'/js/calculator-tipografic.js';
            wp_deregister_script('calculator_tipografic');
            wp_register_script('calculator_tipografic', $url_js);
            wp_enqueue_script('calculator_tipografic', $url_js); 

            // prepare css
            $url_css = plugins_url(basename(dirname(__FILE__))).'/css/style.css';
            wp_deregister_style('calculator_tipografic');
            wp_register_style('calculator_tipografic', $url_css);
            wp_enqueue_style('calculator_tipografic', $url_css);
        }
        else
        {
            // html showed on admin side
            //wp_register_widget_control(self::$default['title'].'_2', self::$default['title'], array('Calculator', 'control')); 
            $img_url = '/wp-content/plugins/'.basename(dirname(__FILE__)).'/img/';
            $url_js = plugins_url(basename(dirname(__FILE__))).'/js/admin.calculator-tipografic.js?'. $img_url ;
            wp_deregister_script('calculator_tipografic');
            wp_register_script('calculator_tipografic', $url_js);
            wp_enqueue_script('calculator_tipografic', $url_js);
            wp_enqueue_script('jquery-ui-draggable');
            wp_enqueue_script('jquery-ui-sortable');

            $url_css = plugins_url(basename(dirname(__FILE__))).'/css/admin.style.css';
            wp_deregister_style('admin.calculator_tipografic');
            wp_register_style('admin.calculator_tipografic', $url_css);
            wp_enqueue_style('admin.calculator_tipografic', $url_css);           
        }
            
    }

    static function control()
    {

        if (isset($_POST['title']))
        {

            // escape POST only on allowed fields and providing default values if case
            //$data = array_map('attribute_escape', array_filter(array_intersect_key($_POST, self::$default))) + self::$default;
            $data = array_map('json_decode', array_filter(array_intersect_key($_POST, self::$default))) + self::$default;
            update_option(self::$default['title'], $data);
        }

        self::options();
        extract(self::$options);

        $format = self::select_options($format);
        $paper_weights = self::select_options($paper_weights);
        $folds_price = self::select_options($folds_price);
        //$error = self::select_options($error);
        // view control
        include dirname( __FILE__ ).'/include/views/control.php';
    }

    static function select_options($arr = null, $reverse = false)
    {
        $_format = $arr;

        if ( ! is_null($arr) and is_array($arr))
        {
            $_format = '';
            foreach ($arr as $key => $value) {
                (! $reverse)                
                ? $_format .= '<option value="' . $key . '">' . $value . '</option>'
                : $_format .= '<option value="' . $value . '">' . $key . '</option>';
            }

        }        
        
        return  $_format;
    }

    // get/set moderately global self::$options
    static function options($options = null)
    {
        (is_null($options))
        ? self::$options = get_option(self::$default['title']) + self::$default
        : self::$options = array_intersect_key($options, self::$default) + self::$default;
        $opts = self::$options = array('vat_acronym' => __('tva', 'calculator-tipar'), 'price' => __('pret', 'calculator-tipar'),) + self::$options;
        return $opts;
    }

    static function widget($args)
    {
        // DRY??
        self::options();
        self::$options['title'] = $args['before_title'] . self::$options['title'] . $args['after_title'];
        // view start
        echo '<script type="text/javascript"> document.calculator_tipografic_ground = '.json_encode(self::$options + array('error' => self::$errors)).'</script>';
        echo $args['before_widget'];
        self::view('widget.php');
        echo $args['after_widget'];
    }

    static function view($page = null)
    {
        extract(self::$options);

        $file = dirname( __FILE__ ).'/include/views/'.$page;

        if (file_exists($file))
            return include $file;
        else
            return false; 
    }    

    static function view_settings()
    {
        // "false" values will not be tolerated
        $options = array_filter(get_option(self::$default['title']));
        $options = array('vat_acronym' => __('tva', 'calculator-tipar'), 'price' => __('pret', 'calculator-tipar'),) + $options;
        // assure default values for extracted variables
        extract(self::$default);
        extract($options);

        $format = self::select_options($format);
        $paper_weights = self::select_options($paper_weights);
        $folds_price = self::select_options($folds_price);
        // view control
        include dirname( __FILE__ ).'/include/views/control_extended.php';
    }

    /**
    *   opposite of naked function which is similar with empty but 0 or '0' are not considered "empty"
    */
     static function dressed($el)
    {   
        if (is_null($el)) return false;
        if ($el === '') return false;
        if ($el == 0) return true;
        return ! empty($el);
    }   

    static function update_options()
    {

        if ($_POST)
        {
            $data = array_intersect_key($_POST, self::$default) + self::$default;
            $filtered = self::validate_options($data);

            if ( Validate::fail() )
            {
                $errors = Validate::$errors;
                header('HTTP/1.1 500 Internal Server Error');

                // set up messages in case of errors
                $arr = array_merge_recursive(array_intersect_key(self::messages(), $errors), $errors); // array having format message and parameters
                $out = array();
                array_walk
                (
                    $arr,
                    function ($items, $field) use( & $out) // get messages
                    {
                       foreach($items as $key => $item)
                       {
                            if ( ! isset($out[$field]))
                                $out[$field] = array();
                            // collector for extra variable
                            $val = $msg = $extra = array();
                            // $item[0] is translation string the rest are arrays
                            $translate_template = array_shift($item);
                            foreach($item as $_item)
                            {
                                if ( count($_item) > 1 )
                                {

                                    foreach(array_slice($_item, 1) as $index => $value)
                                    {   
                                        $extra['$'.$index] = $value;
                                    }

                                }

                                $msg[] = isset($_item[0])
                                ? strtr(
                                    $translate_template, 
                                    array
                                    (
                                        ':field' => $field,
                                        ':val' => $_item[0]
                                    ) 
                                    + 
                                    $extra
                                )
                                : Calculator::MESSAGE_DEFAULT ;

                                $val[] =  isset($_item[0]) ? $_item[0] : '';

                            }
                            $out[$field][] = compact('msg', 'val');
                            // var_export($msg);
                            // var_export($val);
                            // var_export($out);
                            // exit;
                       }
                    }
                );
                unset($arr);
                /*var_export(array_intersect_key(self::messages(), $out)); */
                // var_export($out); 
                //exit;
                
            }
            else
            {
                $out = update_option(self::$default['title'], $data);
            }

            echo json_encode($out); // modified or unmodified
        }
        
        die(); 
    }

    
    static function validate_options($data = null)
    {
        if (is_null($data))
            // data array for validation
            $data = self::options();

        $original = $data;
  
        $nonfiltered = $data +
        array('format_keys' => array_flip($data['format']),) +
        array('folds_price_keys' => array_flip($data['folds_price']),);

        $filtered = Validate::data($nonfiltered, self::rules()); 
        unset($nonfiltered);
// var_export($filtered['maximum_print_area']);
        if ( Validate::fail())
        {
            // revert to original options if error 
            self::$options = $original;
        }
        else
        {
            self::$options = $data;
        }

        return $filtered;
    }

    // validate en-masse using filter extensions

}

class Validate
{

    public static 
    $errors = array();

    function fail()
    {
        return ! empty(self::$errors);
    }

    function data($nonfiltered, $rules)
    {
        return filter_var_array($nonfiltered, $rules);
    }

    function clap($min = null, $max = null, $_ = PHP_INT_MAX)
    {

      if (is_null($min)) $min = -1 * $_;
      if (is_null($max)) $max =  $_;
      $_max = $max;
      $max = max($max, $min);
      // $max was lower then $min
      if ($max == $min)
        $min = $_max;
      unset($_max);

      return array($min, $max);
    }

    function int($min_range, $max_range, $field)
    {
        return array
        (
            'filter' => FILTER_CALLBACK, 
            'options' => 
                function($val) use($min_range, $max_range, $field)
                {
                    return Validate::_int($val, $min_range, $max_range, $field);
                }
        );
    }

    function _int($val, $min_range, $max_range, $field)
    { 
      $errors = & self::$errors;
      // normalize range ends
      list($min_range, $max_range) = self::clap($min_range, $max_range);


      $options = compact('min_range', 'max_range');
      // filter type
      $filtered = filter_var($val . '', FILTER_VALIDATE_INT, compact('options'));
      if ( $filtered === false )
      { 
        // error message
        $errors[$field][__FUNCTION__][] = array($val . '' /* valuefy reference */, $min_range, $max_range);

        return false;
      }

      return $filtered;
    }

    function _float($val, $min, $max, $field, $options = null, $flags = null)
    { 

      $errors = & self::$errors;
      // normalize range ends
      // list($min, $max) = self::clap($min, $max, INF);
      list($min, $max) = self::clap($min, $max, PHP_INT_MAX);
      // filter type
        if (is_null($options) || is_null($flags))
            $options = (array) $options + array('flags' => (int) $flags);
      $filtered = filter_var($val . '', FILTER_VALIDATE_FLOAT, $options);
      // check range
      $range =  ( ($filtered !== false) && ($filtered >= $min) && ($filtered <= $max)) ;
      if ( ! $range)
      { 
        // error message
        $errors[$field][__FUNCTION__][] = array($val . '' /* valuefy reference */, $min, $max);

        return false;
      }

      return $filtered;
    }

    function float($min, $max, $field, $options = null, $flags = null)
    {
        return
        array
        (
            'filter' => FILTER_CALLBACK, 
            'options' => 
                function($val) use ($min, $max, $field, $options, $flags) 
                {
                    return Validate::_float($val, $min, $max, $field, $options, $flags);
                }
        );
    }

    function price($min = null, $max = null, $field = null)
    {
        if (is_string($min))
        {
            $field = $min;
            $min = 0.0;
            $max = 1 * PHP_INT_MAX;
        }

        return
        array
        (
            'filter' => FILTER_CALLBACK, 
            'options' => 
                function($val) 
                use ($min, $max, $field) 
                {
                    return Validate::_float($val, $min, $max, $field, array('decimal' => '.'), FILTER_FLAG_ALLOW_THOUSAND);
                }
        );
    }

    function _regex($val, $regex, $field)
    {
        $errors = & self::$errors;   
        $filtered = filter_var($val, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $regex)));

      if ( ($filtered === false) )
      { 
        // error message
        $errors[$field][__FUNCTION__][] = array($val.'' /* valuefy reference */);

        return false;
      }

      return $filtered;
    }

    function regex($regex, $field)
    {
        return 
        array('filter' => FILTER_CALLBACK, 'options' => function($val) use($regex, $field) {return Validate::_regex($val, $regex, $field);});
    }

    public function text($field) 
    {
        return 
        self::regex('/^[a-z\-0-9\s]{1,}$/i', $field);
    }

}

Calculator::init();