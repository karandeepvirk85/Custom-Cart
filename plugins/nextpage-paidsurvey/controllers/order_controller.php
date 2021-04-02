 <?php
// SEE THE README FOR DOCUMENTATION
// Initialize the class objects, and add functionality

Class Order_Controller {

    public static $objPostType;
    public static $emailNotification = 'abc@yahoo.com';
    public static $strPostType = 'shop_order';
    public static $strPostTypePlural = 'shop_orders';
    public static $arrPostTypeBaseLabels = array(
        'orders', // lowercase
        'Orders', // singular
        'Orders'  // plural
    );

    public function __construct() {
        static::createPostType();
        static::createMetaBoxes();
        add_filter('manage_shop_order_posts_columns', array(__CLASS__,'orderColumns'));
        add_action('manage_shop_order_posts_custom_column' ,array(__CLASS__,'orderColumnsContent'), 10, 2);
        // add_filter('manage_edit-shop_order_sortable_columns',array(__CLASS__ ,'ManageSortableColumn'));
        add_action('wp_ajax_check_out_user', array(__CLASS__, 'checkOutUser'));
	    add_action('wp_ajax_nopriv_check_out_user', array(__CLASS__, 'checkOutUser'));	
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
            'menu_icon' => 'dashicons-cart',
            'supports' => array(
                'title',
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
        
        $arrMetaOrder = array(
            array(
                'name' =>'car_info',
                'label'=>'Cart Info',
                'description' => 'Cart Information',
                'type' => 'readonly',
                'options' => array(
                    'type' => 'raw_output',
                    'output' => ''.self::getCartInformation().'',
                )
            ),
            array(
                'name' =>'status',
                'label'=>'Order Status',
                'description' => 'Order status for admin reference.',
                'type' => 'select',
                'options'=> array(
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'failed' => 'Failed',
                )
            ),
        );
        // Create general info metabox
        static::$objPostType->add_meta_box(
            'meta_information',
            'Order Details',
            $arrMetaOrder,
            'normal',
            'default'
        );
    }

    /**
     * Set Order Columns 
     */
    public static function orderColumns($arrColumns){
        unset($arrColumns['date']);
        $arrColumns['total_products'] = __('No. of Products', 'your_text_domain');
        $arrColumns['total_points'] = __('No. of Points', 'your_text_domain');
        $arrColumns['order_date'] = __('Order Date', 'your_text_domain');
        
        return $arrColumns;
    }

    /**
     * Set Order Columns Content 
     */
    public static function orderColumnsContent($arrColumns, $intPostId){        
        
        if($arrColumns == 'total_products'){
            $intMeta = get_post_meta($intPostId,'_meta_order_total_products',true);
            echo $intMeta;
        }

        if($arrColumns == 'total_points'){
            $intMeta = get_post_meta($intPostId,'_meta_order_total_points',true);
            echo $intMeta;
        }

        if($arrColumns == 'order_date'){
            $intMeta = get_the_time('d F Y (H:i A)',$intPostId);
            echo $intMeta;
        }
    }

    public static function checkOutUser(){
        $arrReturn = array(
            'order_id' => '',
            'points' => '',
            'products' => '',
            'email' => '',
            'success' => false
        );
        $arrCart                = Products_Controller::getCartFromSession();
        $intTotalPoints         = Products_Controller::getCartTotalPoints();
        $intTotalProducts       = Products_Controller::getCartTotalProducts();
        
        // Insert Order Post
        $arrOrder = array(
            'post_type' => 'shop_order',
            'post_status' => 'publish',
            'post_content'=> 'Wordpress Order'
        );
        $intOrderId = wp_insert_post($arrOrder);
        
        // Update Order Post
        if($intOrderId>0){
            wp_update_post(
                array(
                    'post_title' => 'Order# '.$intOrderId,
                    'ID' => $intOrderId
                )
            );
            // Update Order Meta
            update_post_meta($intOrderId,'_meta_order_information', $arrCart);
            update_post_meta($intOrderId,'_meta_order_total_points', $intTotalPoints);
            update_post_meta($intOrderId,'_meta_order_total_products', $intTotalProducts);
            update_post_meta($intOrderId,'_meta_order_order_status','completed');
            // update_post_meta($intOrderId,'_meta_order_order_email','Completed');
            // update_post_meta($intOrderId,'_meta_order_order_name','Completed');
            
            self::updateInventory();
            self::sendEmailToAdmin($intOrderId);
            self::sendEMailToUser($intOrderId);
            $arrReturn = self::getReturnArray(
                $intOrderId,
                $intTotalPoints,
                $intTotalProducts
            );                
            self::destroyCart();
            echo json_encode($arrReturn);
            die;
        }
    }

    /**
     * Get Return Object
     * 
     */
    public static function getReturnArray($intOrderId, $intTotalPoints, $intTotalProducts){
        $arrReturn = array(
            'order_id' => $intOrderId,
            'points' => $intTotalPoints,
            'products' => $intTotalProducts,
            'email' => 'static@gmail.com',
            'success' => true
        );

        return $arrReturn;
    }

    /**
     * Update Inventory After Order 
     */
    public static function updateInventory(){
        $arrCart = Products_Controller::getCartFromSession();
        if(!empty($arrCart)){
            foreach($arrCart as $key => $strValue){
                $intAvailableProducts   = (int) Products_Controller::getAvailableProducts($key);
                $intUpdatedProducts     = $intAvailableProducts - $strValue;
                update_post_meta($key ,'_meta_information_points_qty', (int) $intUpdatedProducts);
            }
        }
    }

    /**
     * This function empty the user cart after saving order post type 
     */
    public static function destroyCart(){
        $_SESSION['cart'] = array();
    }

    /**
     * Send Email to Admin 
     */
    public static function sendEmailToAdmin($intOrderId){
        $strToEmail = static::$emailNotification; 
    }

    /**
     * Send Email to User 
     */
    public static function sendEmailToUser($intOrderId){

    }

    /**
     * Get Cart Information
     */
    public static function getCartInformation(){
        $strHtml = '';

        if(isset($_GET['post'])){
            $intOrderId = (int)$_GET['post'];
            $arrCart = get_post_meta($intOrderId,'_meta_order_information',true);
        }

        $strHtml .= '<table id="cart-table" class="table table-stripped">';
        $strHtml .= '<thead>';
        $strHtml .= '<tr>';
        $strHtml .= '<th>Image</th>';
        $strHtml .= '<th>Product</th>';
        $strHtml .= '<th>Quantity</th>';
        $strHtml .= '<th>Points</th>';
        $strHtml .= '</tr>';
        $strHtml .= '</thead>';
        $strHtml .= '<tbody>'; 
        if(!empty($arrCart)){
            foreach($arrCart as $key => $strValue){
                $strCartString = '';
                $strCartString .= '<tr>';
                $strCartString .= '<td><img class="order-image" src="'.getPostImage($key,'thumbnail').'"></td>';
                $strCartString .= '<td><a href="'.admin_url($key).'post.php?post='.$key.'&action=edit">'.get_the_title($key).'</a></td>';
                $strCartString .= '<td>'.$strValue.'</td>';
                $strCartString .= '<td>'.$strValue * get_post_meta($key,'_meta_information_points_price',true).'</td>';
                $strCartString .= '</tr>';
                $strHtml .= $strCartString;
            }
        }
        $strHtml .= '</tbody>';
        $strHtml .=  '</table>';
        return $strHtml;
    }
}

$objMemberController = new Order_Controller();
?>