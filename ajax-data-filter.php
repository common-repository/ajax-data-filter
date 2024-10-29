<?php
/**
 * 
 * Plugin Name: Ajax Data Filter
 * Plugin URI: https://demo.www.yasin.com/
 * Description: Make your filter. no limited and easily
 * Version: 1.0
 * Author: Yasin Ti
 * Author URI: https://profiles.wordpress.org/yasintechnology
 *
 * @package   Ajax Data Filter
 * @author    Yasin Ti <yasin.coding@gmail.com>
 * @copyright Copyright (c) 2018.
 *
 */


if ( ! defined( 'ABSPATH' ) ) {
	die( 'dont access!' );
}

yd_ajax_filter::yd_init();

CLASS yd_ajax_filter {
	
	
		public static function yd_init(){
		
        // to Create field  in database
        register_activation_hook(__FILE__, array(__CLASS__, 'yd_install'));
		//to group field
        add_action('admin_init', array(__CLASS__, 'admin_init'));
		
		// add widget
		add_action('widgets_init',function(){register_widget('y_data_filter_widget');});

		add_action('wp_enqueue_scripts',array(__CLASS__,'add_js_files'));
	
		add_action('wp_ajax_yf_data_filter',array(__CLASS__,'y_data_functions'));
		add_action('wp_ajax_nopriv_yf_data_filter',array(__CLASS__,'y_data_functions'));

        // link for General Style on plugin
        add_action('wp_enqueue_scripts', array(__CLASS__, 'plugin_file'));

		// insert style in to admin panel
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_style_scripts'));
		
		// add admin menu
        add_action('admin_menu', array(__CLASS__, 'admin_menu'));
		
		//hook into the init action and call create_book_taxonomies when it fires
		add_action( 'init', array(__CLASS__,'create_yd_filter_hierarchical_taxonomy'), 0 );
		
		}
	
		 public static function yd_install() 
		 {
		
			// Create option field
			add_option('yd_div', '.content-area');
			add_option('yd_loading_img', '');
			add_option('yd_posts_per_page', '4');

		}

		public static function admin_init()
		{

			// to group field in database
			register_setting('yd_filter', 'yd_div');
			register_setting('yd_filter', 'yd_loading_img');
			register_setting('yd_filter', 'yd_posts_per_page');

		}
		
		
	 
		public static function create_yd_filter_hierarchical_taxonomy() {
		 
		// Add new taxonomy, make it hierarchical like categories
		 
		  $labels = array(
			'name' => _x( 'yd_filter', 'yd_filter' ),
			'singular_name' => _x( 'yd_filter', 'yd_filter' ),
			'search_items' =>  __( 'Search yd_filter' ),
			'all_items' => __( 'All yd_filter' ),
			'parent_item' => __( 'Parent yd_filter' ),
			'parent_item_colon' => __( 'Parent yd_filter:' ),
			'edit_item' => __( 'Edit yd_filter' ), 
			'update_item' => __( 'Update yd_filter' ),
			'add_new_item' => __( 'Add New yd_filter' ),
			'new_item_name' => __( 'New yd_filter' ),
			'menu_name' => __( 'YD_Filter' ),
		  );    
	 
		// Now register the taxonomy
		 
		  register_taxonomy('yd_filter',array('post'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'yd_filter' ),
		  ));
		 
		}

	

		public static function add_js_files() {
		

		wp_enqueue_script('y_filter_ajax',plugins_url('/js/ajax.js',__FILE__),array('jquery'),null,false);
		wp_localize_script( 'y_filter_ajax', 'yf_data', array(
		'd_ajax_url' => admin_url( 'admin-ajax.php' ),
        'y_filter_nonce' => wp_create_nonce('y-filter')
		));
	
		}


	
		 /**
		 * Enqueue public-facing style sheet.
		 */
		public static function plugin_file()
		{

			wp_enqueue_style('yf-user-style', plugins_url('/style/style.css' ,__FILE__));
			wp_enqueue_style('yf-grids', plugins_url('/style/grids-responsive.css' ,__FILE__));

		}

		/**
		 * Enqueue admin side scripts and styles
		 */
		public static function admin_style_scripts()
		{

			wp_enqueue_style('yf-admin-style', plugins_url('/style/admin.css',__FILE__));
		}


		// add admin menu
		public static function admin_menu()
		{

			add_menu_page('y-data filter', 'y-data filter', 'manage_options', 'yd-setting', array(__CLASS__, 'y_data_filter'));
			add_submenu_page('yd-setting', 'Setting', 'Setting', 'manage_options', 'yd-setting', array(__CLASS__, 'y_data_filter'));
			add_submenu_page('yd-setting', 'Contact Us', 'Contact Us', 'manage_options', 'yd-about-us', array(__CLASS__, 'about'));
		}
		
	

		/**
		 * include setting for edite elements
		 */
		public static function y_data_filter()
		{
				//must check that the user has the required capability 
			if (!current_user_can('manage_options'))
			{
			  wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			
			include 'setting.php';

		}	
		
		 /**
		 * about me
		 * user support
		 */

		public static function about()
		{

			echo __(' <div class="yd_contact">	
				<div id="yd-header"> <p> Contact Me </p> </div>
				<p> Thank you for your download </p>
				<p> to contact myself with any questions regarding this Ajax Data Filter : yasin.coding@gmail.com</p>
				</div>');

		}
		
		public static function y_data_functions() {

		    // check_ajax_referer dies if nonce can not been verified
		    check_ajax_referer( 'y-filter', 'yf_nonce' );

			$paging = intval($_POST['page']);
			$per = get_option( 'yd_posts_per_page' ) ; 
			
			if($paging){
			
				$offset =  ($paging - 1) * $per;
			}
			
	

			$yd_datas = $_POST['yd_data'];
			$yd_data = array();
			foreach($yd_datas as $data){
				$yd_data[] = sanitize_text_field($data);
			}
			
		

		
		   $filter_args = array(

			   'post_type' => array('post'),
			   'offset' => $offset,
			   'posts_per_page' => $per,
				'tax_query' =>array(
					array(
						'taxonomy' => 'yd_filter',
						'field'    => 'slug',
						'terms'    =>  $yd_data,
					)
				)
			   

		   );


			$filter_load = new WP_Query($filter_args);
			$result='';
			if($filter_load->have_posts()):
			while($filter_load->have_posts()): $filter_load->the_post();

				ob_start();
				require '/inc/post.php';
				$result .= ob_get_clean(); 

			endwhile;
			endif;
	
			wp_reset_postdata();
			
			$end = $filter_load->found_posts;
			
			if( $end > $per ) {
				$page_num = 1;
			} else {
				$page_num = 0;
			}
			
			
			$a = array();
			$a['content'] = $result;
			$a['end'] = $end;
			$a['page_num'] = $page_num;

			wp_die(json_encode($a));



		}

		
			
	}
		
	require_once('widget.php');
		
	


