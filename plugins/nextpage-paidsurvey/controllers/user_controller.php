<?php
// SEE THE README FOR DOCUMENTATION
// Initialize the class objects, and add functionality

Class User_Controller {

    public static $objPostType;
    public static $strPostType = 'shop_user';
    public static $strPostTypePlural = 'shop_users';
    public static $arrPostTypeBaseLabels = array(
        'shop user', // lowercase
        'Shop User', // singular
        'Shop Users' // plural
    );

    public function __construct() {
        static::createPostType();
        static::createMetaBoxes();
    }

    /**
     * Registers the post type
     *
     */
    public static function createPostType() {
        // Arguments for the post type
        $arrPostTypeArgs = array(
            'public' => true,
            'has_archive' => true,
            'menu_position' => 34,
            'menu_icon' => 'dashicons-image-filter',
            'supports' => array(
                'thumbnail',
            ),
        );

        // Get the base labels
        $arrBaseLabels = static::$arrPostTypeBaseLabels;

        // Create the wordpress labels
        $arrPostLabels = array(
            'name'                  => sprintf( _x( '%s', 'taxonomy general name', 'cuztom' ), $arrBaseLabels[2] ),
            'singular_name'         => sprintf( _x( '%s', 'taxonomy singular name', 'cuztom' ), $arrBaseLabels[1] ),
            'search_items'          => sprintf( __( 'Search %s', 'cuztom' ), $arrBaseLabels[2] ),
            'all_items'             => sprintf( __( 'All %s', 'cuztom' ), $arrBaseLabels[2] ),
            'parent_item'           => sprintf( __( 'Parent %s', 'cuztom' ), $arrBaseLabels[1] ),
            'parent_item_colon'     => sprintf( __( 'Parent %s:', 'cuztom' ), $arrBaseLabels[1] ),
            'edit_item'             => sprintf( __( 'Edit %s', 'cuztom' ), $arrBaseLabels[1] ),
            'update_item'           => sprintf( __( 'Update %s', 'cuztom' ), $arrBaseLabels[1] ),
            'add_new_item'          => sprintf( __( 'Add New %s', 'cuztom' ), $arrBaseLabels[1] ),
            'new_item_name'         => sprintf( __( 'New %s Name', 'cuztom' ), $arrBaseLabels[1] ),
            'menu_name'             => sprintf( __( '%s', 'cuztom' ), $arrBaseLabels[2] )
        );

        // Post type object is created here
        static::$objPostType = new Cuztom_Post_Type(static::$strPostType, $arrPostTypeArgs, $arrPostLabels);
    }

    /**
     * Setup the Metaboxes
     *
     */
    public static function createMetaBoxes() {
        
        $arrUsers = array(
            array(
                'name' => 'name',
                'label' => 'User Name',
                'description' => 'User Name',
                'type' => 'text',
            ),
            array(
                'name' => 'email',
                'label' => 'Email',
                'description' => 'User Email',
                'type' => 'text',
            ),
            array(
                'name' => 'password',
                'label' => 'User Password',
                'description' => 'User Password',
                'type' => 'text',
            ),
        );
        // Create general info metabox
        static::$objPostType->add_meta_box(
            'meta_information',
            'User Details',
            $arrUsers,
            'normal',
            'default'
        );
    }
}

$objUserController = new User_Controller();
