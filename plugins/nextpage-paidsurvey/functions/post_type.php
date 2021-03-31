<?php

if( ! defined( 'ABSPATH' ) ) exit;

function register_cuztom_post_type( $name, $args = array(), $labels = array() ){
	$post_type = new Cuztom_Post_Type( $name, $args, $labels );
	return $post_type;
}