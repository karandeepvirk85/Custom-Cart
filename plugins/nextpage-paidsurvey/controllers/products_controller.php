<?php
// SEE THE README FOR DOCUMENTATION
// Initialize the class objects, and add functionality

Class Products_Controller {

    public static $objPostType;
    public static $strPostType = 'shop_product';
    public static $strPostTypePlural = 'shop_products';
    public static $arrPostTypeBaseLabels = array(
        'product', // lowercase
        'Product', // singular
        'Products' // plural
    );

    public function __construct() {
        static::createPostType();
        static::createMetaBoxes();
        add_action('wp_ajax_add-product-to-cart', array(__CLASS__, 'AddToCart'));
	    add_action('wp_ajax_nopriv_add-product-to-cart', array(__CLASS__, 'AddToCart'));	
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
        
        $arrMetaMembers = array(
            array(
                'name' => 'points_price',
                'label' => 'Number of Points',
                'description' => 'Price of product in points',
                'type' => 'number',
            ),
            array(
                'name' => 'points_qty',
                'label' => 'Quantity',
                'description' => 'Quantity of products',
                'type' => 'number',
            ),
            array(
                'name' => 'short_description',
                'label' => 'Short Description',
                'description' => 'Short Description of the product',
                'type' => 'textarea',
            ),
        );
        // Create general info metabox
        static::$objPostType->add_meta_box(
            'meta_information',
            'Products Details',
            $arrMetaMembers,
            'normal',
            'default'
        );
    }
    /**
     * Function to return product points
     * @param Post ID
     */
    public static function getPoints($intPostId){
        $intReturn = 0;
        if(empty($intPostId)){
            return;
        }
        $intReturn = get_post_meta($intPostId,'_meta_information_points_price',true);
        return $intReturn;
    }

    /**
     * Function to return available qty
     * @param Post ID
     */
    public static function getAvailableProducts($intPostId){
        $intReturn = 0;
        if(empty($intPostId)){
            return;
        }
        $intReturn = (int) get_post_meta($intPostId,'_meta_information_points_qty',true);
        return $intReturn;
    }

    /**
     * Function to return short description
     * @param Post ID
     */
    public static function getShortDescription($intPostId){
        $strReturn = '';
        if(empty($intPostId)){
            return;
        }
        $strReturn = (string) trim(get_post_meta($intPostId,'_meta_information_short_description',true));
        return $strReturn;
    }

    /**
     * Function get parameters from AJAX
     * @param Post ID and Quantity
     */
    public static function AddToCart(){
        $arrReturn = array(
            'error' => false,
            'errors_messages' => array(),
            'success_message' => '',
            'product_id' => '',
            'quantity' => ''
        );
        if(isset($_GET['post_id']) AND isset($_GET['quantity'])) {
            $intProductId      = (int) $_GET['post_id'];
            $intQuantity    = (int) $_GET['quantity'];
            if($intProductId<=0){
                $arrReturn['error'] = true;
                $arrReturn['errors_messages'][] = 'Post id is missing';
            }
            if($intQuantity<=0){
                $arrReturn['error'] = true;
                $arrReturn['errors_messages'][] = 'Quantity must be greater then zero';
            }
            if(!is_user_logged_in()){
                $arrReturn['error'] = true;
                $arrReturn['errors_messages'][] = 'User must be logged In tp purchace items';
            }
            if($arrReturn['error'] === false){
                if($intProductId>0 AND $intQuantity>0){
                    $intUserId = get_current_user_id();
                    $arrCallBack = self::addProductToCart($intProductId,$intQuantity);
                    $arrReturn['product_id'] = $arrCallBack['product_id']; 
                    $arrReturn['quantity'] =  $arrCallBack['quantity'];
                    $arrReturn['success_message'] = $arrCallBack['success_message'];
                }
            }
        }
        echo json_encode($arrReturn);
        die;
    }

    /**
     * 
     * @param Product ID, Quantity
     * This Function fills session
     */
    public static function addProductToCart($intProductId, $intQuantity){
        $arrReturn = array(
            'product_id' => '',
            'quantity' => '',
            'success_message'
        );

        if((array_key_exists($intProductId,$_SESSION['cart']['products']))) {
            $intOldQuantity = $_SESSION['cart']['products'][$intProductId];
            $intTotalQuantity = $intOldQuantity+$intQuantity;
            $_SESSION['cart']['products'][$intProductId] = $intTotalQuantity;
            $arrReturn['success_message'] = 'Your cart has been updated. you have '.$intTotalQuantity.' items in your cart.';
        }else{
            $_SESSION['cart']['products'][$intProductId] = $intQuantity;
            $arrReturn['success_message'] = $intQuantity.' item has been added to the cart.';
        }

        $arrReturn['product_id'] = $intProductId;
        $arrReturn['quantity'] = $intTotalQuantity;
        return $arrReturn;
    }
    /**
     * Get Cart From Session
     */
    public static function getCartFromSession(){
        if(!empty($_SESSION['cart'])){
            return $_SESSION['cart']['products'];
        }
    }
}

$objMemberController = new Products_Controller();
