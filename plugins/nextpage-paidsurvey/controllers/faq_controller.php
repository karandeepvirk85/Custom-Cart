<?php
// SEE THE README FOR DOCUMENTATION
// Initialize the class objects, and add functionality

Class Faq_Controller{

    public static $objPostType;
    public static $strPostType = 'faq';
    public static $strPostTypePlural = 'faqs';
    public static $arrPostTypeBaseLabels = array(
        'faq', // lowercase
        'Faq', // singular
        'Faqs' // plural
    );

    public function __construct() {
        static::createPostType();
        static::createMetaBoxes();
        add_filter('manage_faq_posts_columns', array(__CLASS__,'faqColumns'));
        add_action('manage_faq_posts_custom_column' ,array(__CLASS__,'faqColumnsContent'), 10, 2);
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
            'menu_icon' => 'dashicons-welcome-add-page',
            'supports' => array(
                'title',
                'editor'
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
        
        $arrFaqs = array(
            array(
                'name' => 'order',
                'label' => 'Custom Order',
                'description' => 'Order of the faqs at the front end',
                'type' => 'number',
            ),
        );
        // Create general info metabox
        static::$objPostType->add_meta_box(
            'meta_faq',
            'Faq Details',
            $arrFaqs,
            'normal',
            'default'
        );
    }
    /**
     * Set Product Columns 
     */
    public static function faqColumns($arrColumns){
        unset($arrColumns['date']);
        $arrColumns['order'] = __('Custom Order', 'your_text_domain');
        return $arrColumns;
    }

    /**
     * Set Order Columns Content 
     */
    public static function faqColumnsContent($arrColumns, $intPostId){        
        if($arrColumns == 'order'){
            $intMeta = (int) get_post_meta($intPostId,'_meta_faq_order',true);
            echo $intMeta;
        }
    }

}

$objMemberController = new Faq_Controller();