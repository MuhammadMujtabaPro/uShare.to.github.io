<?php

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    $parenthandle = 'parent-style'; // This is parent Astra style theme.
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
	

	
}

// Custom Function to Include
function my_favicon_link() {
    echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />' . "\n";
}
add_action( 'wp_head', 'my_favicon_link' );



	   // Disable Gutenberg on the back end.
	   //add_filter( 'use_block_editor_for_post', '__return_false' );
	   // Disable Gutenberg for widgets.
	   //*******************************************************************
       //Disable  disable the new wordpress widget-block-editor --Gutenberg-disable-->
		//add_filter('use_widgets_block_editor', '__return_false');	

?> 

