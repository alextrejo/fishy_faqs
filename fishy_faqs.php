<?php
/**
 * Plugin Name: Fishy FAQs
 * Description: Frequently Ask Questions
 * Author: Alexander Trejo
 * Version: 1.0
 */
 
 class fishyFAQ{ 
    function __construct(){
      //Create FAQ Content Type
      add_action( 'init', array($this, 'make_post_type') );
      
      //Flush Permalinks
      register_activation_hook( __FILE__, array( $this, 'install' ) );
      
      //Add shortcode
      add_shortcode('fishy-faqs', array($this, 'get_faqs'));
    }
    
      //On Plugin activation
    function install() {
      	$this->make_post_type();
      	flush_rewrite_rules();
    }
    
    //FAQs Content type
    function make_post_type(){
      $args = array('label'              => 'FAQ',
                    'labels'             => 'FAQs',
                    'description'        => 'Frequenty Ask Questions',
                		'public'             => true,
                		'publicly_queryable' => true,
                		'show_ui'            => true,
                		'show_in_menu'       => true,
                		'query_var'          => true,
                		'rewrite'            => array( 'slug' => 'faq' ),
                		'capability_type'    => 'post',
                		'has_archive'        => true,
                		'hierarchical'       => false,
                		'menu_position'      => null,
                		'supports'           => array( 'title', 'editor', 'author'),
                    'menu_icon'          => 'dashicons-editor-help',
                    );
      
      $post_type = 'faq';
      
      register_post_type($post_type, $args);
    }
    
    function get_faqs(){
       global $wp_query;
       
       wp_enqueue_script('jquery-ui-accordion');

       $args = array('post_type'=>'faq', 'post_status'=>'publish', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page'   => -1);
       //add paged value to query
       $args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
       $faqs = new WP_Query($args);

       // Pagination fix
       $temp_query = $wp_query;
       $wp_query   = NULL;
       $wp_query   = $faqs;

       $items = array();
       if($faqs->have_posts()){
           while($faqs->have_posts()){
             $faqs->the_post();
             
             $items[] = array('ID' => get_the_ID(), 'title' => get_the_title(), 'content' => get_the_content());
          }
       }

       $pagination = get_posts_nav_link();

       // Reset main query object
       $wp_query = NULL;
       $wp_query = $temp_query;

       return mvcView::render('faqs.php', array('items' => $items, 'html' => $html, 'pagination' => $pagination));
    }
 }

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

$f_faq = new fishyFAQ();