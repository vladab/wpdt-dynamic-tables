<?php
/*
Plugin Name: wpDataTables Dynamic Tables
Plugin URI: http://wpdatatables.com
Description: Add interactive tables easily from any input source
Version: 1.6.0
Author: Vladica Bibeskovic
Author URI: http://github.com/vladab
*/

add_filter( 'template_filter', 'dt_load_template_file', 10, 1);
add_action( 'wpdatatables_admin_before_edit', 'dt_admin_settings_template');
add_action( 'wp_ajax_dt_get_table', 'dt_get_table_callback' );
add_action( 'wp_ajax_nopriv_dt_get_table', 'dt_get_table_callback' );
add_action( 'wp_ajax_dt_save_dt_table_settings', 'dt_save_dt_table_settings_callback' );
add_action( 'wp_ajax_nopriv_dt_save_dt_table_settings', 'dt_save_dt_table_settings_callback' );

if( !function_exists('dt_load_template_file') ) {
    function dt_load_template_file( $file_title ) {
        if( $file_title == WDT_TEMPLATE_PATH . 'wpdatatables_table_main.inc.php' ) {
            include( __DIR__ . '/tpl/actions.php');
            return __DIR__ . '/tpl/new_template.php';
        } else {
            return $file_title;
        }
    }
}

if( !function_exists('dt_get_table_callback') ) {
    function dt_get_table_callback() {
        if( isset( $_REQUEST['table_id'] ) && $_REQUEST['table_id'] != '') {
            $table_id = intval( $_REQUEST['table_id'] );
            $configuration = (array)json_decode(get_option('dt_configuration', array()), true);
            if( !empty( $configuration ) && isset($configuration[$table_id]) ) {
                $corresponding_table_id = (isset($configuration[$table_id]['child_table_id']))? $configuration[$table_id]['child_table_id'] : exit() ;
                $variable_parameters = '';
                if( isset( $configuration[$table_id]['var1'] ) && $configuration[$table_id]['var1'] != '' ) {
                    $variable_parameters .= (isset($_REQUEST['variable1']) && $_REQUEST['variable1'] != '') ? " var1='{$_REQUEST['variable1']}'" : '';
                }
                if( isset( $configuration[$table_id]['var2'] ) && $configuration[$table_id]['var2'] != '' ) {
                    $variable_parameters .= (isset($_REQUEST['variable2']) && $_REQUEST['variable2'] != '') ? " var2='{$_REQUEST['variable2']}'" : '';
                }
                if( isset( $configuration[$table_id]['var3'] ) && $configuration[$table_id]['var3'] != '' ) {
                    $variable_parameters .= (isset($_REQUEST['variable3']) && $_REQUEST['variable3'] != '') ? " var3='{$_REQUEST['variable3']}'" : '';
                }
                echo do_shortcode("[wpdatatable id={$corresponding_table_id} {$variable_parameters}]");
                }
            exit();
        }
    }
}
if( !function_exists('td_get_table_td_conf') ) {
    function td_get_table_td_conf( $table_id, $value ) {
        $configuration = (array)json_decode(get_option('dt_configuration', array()), true);
        $variable_parameters = '';
        if( isset( $configuration[$table_id]['var1'] ) && $configuration[$table_id]['var1'] != '' ) {
            if($configuration[$table_id]['var1'] == 'TDVALUE') {
                $variable_parameters .= " var1='{$value}' ";
            } else {
                $variable_parameters .= " var1='{$configuration[$table_id]['var1']}' ";
            }
        }
        if( isset( $configuration[$table_id]['var2'] ) && $configuration[$table_id]['var2'] != '' ) {
            if($configuration[$table_id]['var2'] == 'TDVALUE') {
                $variable_parameters .= " var2='{$value}' ";
            } else {
                $variable_parameters .= " var2='{$configuration[$table_id]['var2']}' ";
            }
        }
        if( isset( $configuration[$table_id]['var3'] ) && $configuration[$table_id]['var3'] != '' ) {
            if($configuration[$table_id]['var3'] == 'TDVALUE') {
                $variable_parameters .= " var3='{$value}' ";
            } else {
                $variable_parameters .= " var3='{$configuration[$table_id]['var3']}' ";
            }
        }

        return $variable_parameters;
    }
}
if( !function_exists('dt_admin_settings_template') ) {
    function dt_admin_settings_template() {
        if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) {
            include( __DIR__ . '/tpl/admin_settings.php' );
        }
    }
}
if( !function_exists('dt_save_dt_table_settings_callback') ) {
    function dt_save_dt_table_settings_callback() {
        if( isset($_REQUEST['table_id']) && $_REQUEST['table_id'] != '' ) {
            $configuration = (array)json_decode(get_option('dt_configuration', array()), true);
            $table_id = intval( $_REQUEST['table_id'] );
            if (isset($_REQUEST['child_id']) && $_REQUEST['child_id'] != '') {
                $configuration[$table_id]['child_table_id'] = intval($_REQUEST['child_id']);
            }
            if( isset($_REQUEST['variable1'])) {
                $configuration[$table_id]['var1'] = $_REQUEST['variable1'];
            }
            if( isset($_REQUEST['variable2'])) {
                $configuration[$table_id]['var2'] = $_REQUEST['variable2'];
            }
            if( isset($_REQUEST['variable3'])) {
                $configuration[$table_id]['var3'] = $_REQUEST['variable3'];
            }
            update_option('dt_configuration', json_encode( $configuration) );
        }
    }
}