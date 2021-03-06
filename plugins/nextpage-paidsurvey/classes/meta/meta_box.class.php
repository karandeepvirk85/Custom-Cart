<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers the meta boxes
 *
 *
 */
class Cuztom_Meta_Box extends Cuztom_Meta
{
	var $context;
	var $priority;
	var $post_types;

	/**
	 * Constructs the meta box
	 *
	 * @param   string 			$id
	 * @param 	string|array	$title
	 * @param 	array|string	$fields
	 * @param 	string 			$post_type_name
	 * @param 	string 			$context
	 * @param 	string 			$priority
	 *
	 */
	function __construct( $id, $title, $post_type, $data = array(), $context = 'normal', $priority = 'default' )
	{
		if( ! empty( $title ) )
		{
			parent::__construct( $title );

			$this->id 			= $id;
			$this->post_types 	= (array) $post_type;
			$this->context		= $context;
			$this->priority		= $priority;

			// Chack if the class, function or method exist, otherwise use cuztom callback
			if( Cuztom::is_wp_callback( $data ) )
			{
				$this->callback = $data;
			}
			else
			{
				$this->callback = array( &$this, 'callback' );

				// Build the meta box and fields
				$this->data = $this->build( $data );

				foreach( $this->post_types as $post_type )
				{
					add_filter( 'manage_' . $post_type . '_posts_columns', array( &$this, 'add_column' ) );
					add_action( 'manage_' . $post_type . '_posts_custom_column', array( &$this, 'add_column_content' ), 10, 2 );
					add_action( 'manage_edit-' . $post_type . '_sortable_columns', array( &$this, 'add_sortable_column' ), 10, 2 );
				}

				add_action( 'save_post', array( &$this, 'save_post' ) );
				add_action( 'post_edit_form_tag', array( &$this, 'edit_form_tag' ) );
			}

			// Add the meta box
			add_action( 'add_meta_boxes', array( &$this, 'add_meta_box' ) );
		}
	}

	/**
	 * Method that calls the add_meta_box function
	 *
	 */
	function add_meta_box()
	{
		foreach( $this->post_types as $post_type )
		{
			add_meta_box(
				$this->id,
				$this->title,
				$this->callback,
				$post_type,
				$this->context,
				$this->priority
			);
		}
	}

	/**
	 * Hooks into the save hook for the newly registered Post Type
	 *
	 */
	function save_post( $post_id )
	{
		// Deny the wordpress autosave function
		if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) return;

		// Verify nonce
		if( ! ( isset( $_POST['cuztom_nonce'] ) && wp_verify_nonce( $_POST['cuztom_nonce'], 'cuztom_meta' ) ) ) return;

		// Is the post from the given post type?
		if( ! in_array( get_post_type( $post_id ), array_merge( $this->post_types, array( 'revision' ) ) ) ) return;

		// Is the current user capable to edit this post
		foreach( $this->post_types as $post_type )
			if( ! current_user_can( get_post_type_object( $post_type )->cap->edit_post, $post_id ) ) return;

		$values = isset( $_POST['cuztom'] ) ? $_POST['cuztom'] : array();

		if( ! empty( $values ) )
			parent::save( $post_id, $values );
	}

	/**
	 * Normal save method to save all the fields in a metabox
	 *
	 */
	function save( $post_id, $values )
	{
		foreach( $this->fields as $id => $field )
		{
			if( $field->in_bundle ) continue;

			$value = isset( $values[$id] ) ? $values[$id] : '';
			$value = apply_filters( "cuztom_post_meta_save_$field->type", apply_filters( 'cuztom_post_meta_save', $value, $field, $post_id ), $field, $post_id );

			$field->save( $post_id, $value, 'post' );
		}
	}

	/**
	 * Used to add a column head to the Post Type's List Table
	 *
	 * @param 	array 			$columns
	 * @return 	array
	 *
	 */
	function add_column( $columns )
	{
		unset( $columns['date'] );

		foreach( $this->fields as $id_name => $field )
		{
			if( $field->show_admin_column ) $columns[$id_name] = $field->label;
		}

		$columns['date'] = __( 'Date', 'cuztom' );
		return $columns;
	}

	/**
	 * Used to add the column content to the column head
	 *
	 * @param 	string 			$column
	 * @param 	integer 		$post_id
	 * @return 	mixed
	 *
	 */
	function add_column_content( $column, $post_id )
	{
		$meta = get_post_meta( $post_id, $column, true );

		if( $this->fields )
		{
			foreach( $this->fields as $id_name => $field )
			{
				if( $column == $id_name )
				{
					if( $field->repeatable && $field->_supports_repeatable )
					{
						echo implode( $meta, ', ' );
					}
					else
					{
						if( $field instanceof Cuztom_Field_Image )
							echo wp_get_attachment_image( $meta, array( 100, 100 ) );
						else
							echo $meta;
					}

					break;
				}
			}
		}
	}

	/**
	 * Used to make all columns sortable
	 *
	 * @param 	array 			$columns
	 * @return  array
	 *
	 */
	function add_sortable_column( $columns )
	{
		if( $this->fields )
		{
			foreach( $this->fields as $id_name => $field )
				if( $field->admin_column_sortable ) $columns[$id_name] = $field->label;
		}

		return $columns;
	}
}