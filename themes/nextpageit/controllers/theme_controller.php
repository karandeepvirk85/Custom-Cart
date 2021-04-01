<?php
if(!defined('ABSPATH')) exit; 
class Theme_Controller{
    public static $constantUserNotLoggedIn = 'User must be logged In.';
    public static $constantCartEmpty = 'Your cart is empty. Please go back to products page and add some items into your cart.';
    public function __construct() {

    }
    
    /**
     * Get Paged Query
     */
    public static function getPagedQuery(){
        if (get_query_var('paged')){
            $paged = get_query_var('paged');
        } 
        elseif (get_query_var('page')){
            $paged = get_query_var('page');
        } 
        else {
            $paged = 1;
        }
        return $paged;
    }

    /**
     * Get Post Date
     */
    public static function getPostDate($strPostDate){
        $strReturn = '';
        $strPostDate = strtotime($strPostDate);
        $strPostDate = date('d M, Y',$strPostDate);
        $strReturn = $strPostDate; 
        return $strPostDate;
    }

    /**
     * Get Post Image
     */
    public static function getPostImage($intPostId, $strSIze){
        $intAttachmentId = get_post_thumbnail_id($intPostId);
        $strImageUrl = wp_get_attachment_image_src($intAttachmentId, $strSIze);
        $strImageUrl = $strImageUrl[0];
        return $strImageUrl;
    }

    /**
     * Filter WP Content
     */
    public static function contentFilter($strcontent,$bolStripTags,$intLength=300){
        if($bolStripTags){
            $strcontent = strip_tags($strcontent); 
            $strcontent = substr($strcontent, 0, $intLength);
        }
        else{
            $strcontent = nl2br($strcontent);
        }
        return $strcontent;
    }

    /**
     * Get Posts
     */
    public static function getAllPosts($paged, $strPostType){
        $allPostsWPQuery = new WP_Query(
            array(
                'post_type'     => $strPostType, 
                'post_status'   =>'publish', 
                'posts_per_page'=> 8,
                'paged'         => $paged,
                'orderby'       => 'date',
                'order'         => 'DESC',
            )
        );
        return $allPostsWPQuery;
    }

    /**
     * Get Args For Pagination
     */
    public static function getArgsForPagination($paged,$intMaxPages){
        $arrReturn  = array(
            'paged' => $paged,
            'max_num_pages' => $intMaxPages
        );
        return $arrReturn;
    }

    /**
     * Get Shake Error
     */
    public static function getShakeError($string){
        $strHtml = '';
        $strHtml .= '<div class="animate__animated animate__shakeX alert alert-danger empty-cart-container">'.$string.'</div>';
        return $strHtml;
    }
}
?>