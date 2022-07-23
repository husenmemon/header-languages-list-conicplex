<?php
/*
    Plugin Name:  Header Languages List for FluentU Blog by ConicPlex
    Plugin URI:   https://conicplex.com 
    Description:  Add and Configure Language Links with their respective flags and show in the header section of the Blog, as per the UI/UX shared.
    Version:      1.0
    Author:       Husen Memon (ConicPlex)
    Author URI:   https://conicplex.com
    License:      GPL2
    License URI:  https://www.gnu.org/licenses/gpl-2.0.html
    Text Domain:  hlcp
*/

//Exit if Accessed Directly
if ( ! defined( 'ABSPATH' ) ) {
        die();
}

// Include Style & Scripts
add_action( 'wp_enqueue_scripts', 'hlcp_load_admin_styles' );

function hlcp_load_admin_styles() {
    wp_enqueue_style('hlcp_style', plugins_url('assets/css/style.css?v='.time(),__FILE__ ));
    wp_enqueue_script('jquery');
    wp_enqueue_script('hlcp_script', plugins_url('assets/js/script.js?v='.time(),__FILE__ ),'',false);
}
// End Include Style & Scripts


// Create Custom Post Type
function hlcp_custom_post_type() {
    // Set UI labels for Languages Configurator
        $labels = array(
            'name'                => _x( 'Languages', 'Post Type General Name'),
            'singular_name'       => _x( 'Language', 'Post Type Singular Name'),
            'menu_name'           => __( 'Languages'),
            'parent_item_colon'   => __( 'Parent Languages'),
            'all_items'           => __( 'All Languages'),
            // 'view_item'           => __( 'View Languages'),
            'add_new_item'        => __( 'Add New Language'),
            'add_new'             => __( 'Add New'),
            'edit_item'           => __( 'Edit Language'),
            'update_item'         => __( 'Update Language'),
            'search_items'        => __( 'Search Language'),
            'not_found'           => __( 'Not Found'),
            'not_found_in_trash'  => __( 'Not found in Trash'),
        );
    // Set other options for Languages Configurator
        $args = array(
            'label'               => __( 'Languages'),
            'description'         => __( 'Languages'),
            'labels'              => $labels,  
            'supports'            => array( 'title', 'thumbnail'),     
            'taxonomies'          => array( 'genres' ),     
            'hierarchical'        => false,
            'menu_icon'           => 'dashicons-admin-links',
            'public'              => true,
            'capability_type' => 'post',
					 'capabilities' => array( // allow access to user roles with Administrators only
						'publish_posts' => 'manage_options',
						'edit_posts' => 'manage_options',
						'edit_others_posts' => 'manage_options',
						'delete_posts' => 'manage_options',
						'delete_others_posts' => 'manage_options',
						'read_private_posts' => 'manage_options',
						'edit_post' => 'manage_options',
						'delete_post' => 'manage_options',
						'read_post' => 'manage_options',
					),
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'show_in_rest' => true, 
        );
        // Registering the Languages Configurator
        register_post_type( 'languages', $args );
    }
    add_action( 'init', 'hlcp_custom_post_type', 0 );




    // Create and add Link Field for Languages configurator
    function hlcp_add_language_url_metabox() {
        add_meta_box(
            'language_url_meta_box', // $id
            'Language Url', // $title
            'show_the_language_url_meta_box', // $callback
            'languages', // $screen
            'normal', // $context
            'high' // $priority
        );
    }

    add_action( 'add_meta_boxes', 'hlcp_add_language_url_metabox' );
        
    function show_the_language_url_meta_box() {
        global $post;  
        $hlcp_language_url = get_post_meta( $post->ID, 'hlcp_language_url', true ); 
    ?>

        <input type="hidden" name="language_url_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">
    
        <!-- All fields will go here -->
        <p>
            <input type="text" name="hlcp_language_url" id="hlcp_language_url" style="width: 100%" placeholder="Enter Language Url" value="<?php if (isset($hlcp_language_url)) { echo $hlcp_language_url; } else { echo 'dddd';} ?>">
        </p>
    
    <?php
    }
    // End Create custom field for language link
        

    // Save custom field for language link
    function hlcp_save_language_url_meta( $post_id ) {
        
        // verify nonce
        if ( !wp_verify_nonce( $_POST['language_url_meta_box_nonce'], basename(__FILE__) ) ) {
            return $post_id;
        }
        // check autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // check permissions
        if ( 'page' === $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
    
        $old = get_post_meta( $post_id, 'hlcp_language_url', true );
        $new = $_POST['hlcp_language_url'];

        if ( $new && $new !== $old ) {
            update_post_meta( $post_id, 'hlcp_language_url', $new );
        } elseif ( '' === $new && $old ) {
            delete_post_meta( $post_id, 'hlcp_language_url', $old );
        }
    }
    add_action( 'save_post', 'hlcp_save_language_url_meta' );
    
    // End save custom field for language link    

    // Append data in header
    function hlcp_js_appned()
    {   
        // Get Custom Languages List
        $get_hlcp_lang = get_posts([
            'post_type' => 'languages',
            'post_status' => 'publish',
            'numberposts' => -1,
            'order'    => 'ASC'
        ]);

    ?>
        <script>
    
            var hlcp_html_output = '<div class="hlcp-main"><div class="hlcp-headings"><div class="hlcp-heading"> <span class="hlcp-main-heading">Ready to learn?</span> <span class="hlcp-sub-heading">Pick a language to get started!</span> </div> <div class="hlcp-heading-on-scroll"><span>Pick a language to speak...</span></div></div><div class="hlcp-lang">';
    <?php

            $count_lang = 1;
            $is_show = "";
            
            foreach($get_hlcp_lang as $hlcp_lang)
            {
                if($count_lang==6){ ?>
                    hlcp_html_output+= '<a class="hlcp-lang-link hlcp-lang-more" href="https://www.fluentu.com/language/"><div class="hlcp-lang-item"><div class="hlcp-country-flag"><img src="https://cdn-icons-png.flaticon.com/512/1828/1828817.png"></div><div class="hlcp-lang-name">More...</div></div></a>';  
                    <?php
                }
                if($count_lang>=6){
                    $is_show = "hlcp-lang-show";
                }
                $hlcp_language_url = get_post_meta( $hlcp_lang->ID, 'hlcp_language_url',true);
            ?>
                hlcp_html_output+= '<a class="hlcp-lang-link <?php echo $is_show; ?>" href="<?php echo $hlcp_language_url; ?>"><div class="hlcp-lang-item"><div class="hlcp-country-flag"><img src="<?php echo get_the_post_thumbnail_url($hlcp_lang->ID, "thumbnail"); ?>"></div><div class="hlcp-lang-name"><?php echo $hlcp_lang->post_title; ?></div></div></a>';

            <?php          
            
            $count_lang++;

            }
            ?>
    
            hlcp_html_output +='</div></div>';

        </script>

    <?php
    }
    add_action( 'wp_head', 'hlcp_js_appned');
    // End Append data in header