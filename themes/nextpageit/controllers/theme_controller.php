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
        $strHtml .= 
            '<div class="animate__animated animate__fadeInDown alert alert-danger alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><i class="fa fa-hand-o-right" aria-hidden="true"></i> Failure! </strong>'.$string.'.
            </div>';
        return $strHtml;
    }

    /**
     * Get Shake Sucess
     */
    public static function getShakeSuccess($string){
        $strHtml = '';
        $strHtml .= 
            '<div class="animate__animated animate__fadeInDown alert alert-success alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><i class="fa fa-hand-o-right" aria-hidden="true"></i> Success! </strong>'.$string.'.
            </div>';
        return $strHtml;
    }

    /**
     * Get Shake Notice
     */
    public static function getShakeNotice($string){
        $strHtml = '';
        $strHtml .= 
            '<div class="animate__animated animate__fadeInDown alert alert-warning alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><i class="fa fa-hand-o-right" aria-hidden="true"></i> '.$string.'.
            </div>';
        return $strHtml;
    }

    /**
     * Get Catefories Link
     */
    public static function getCategoriesLink($arrCategories){
        $strReturn = '';
        $arrCategoryName = array();
        if(!empty($arrCategories)){
            foreach($arrCategories as $objCategory){
                $arrCategoryName[] = '<a href="'.get_category_link($objCategory->term_id).'">'.$objCategory->name.'</a>';
            }
        }
        $strReturn = !empty($arrCategoryName) ? implode(', ', $arrCategoryName) : "" ;
        return $strReturn;
    }
    /**
    * 
    * Function to get Wordpress Menu By Name  
    */   
    public static function getMainByMenu($strMenuName){
        $current_menu = wp_get_nav_menu_object($strMenuName);
        $array_menu = wp_get_nav_menu_items($current_menu);
        $menu = array();
        if(!empty($array_menu)){
            foreach ($array_menu as $m) {
                if (empty($m->menu_item_parent)) {
                    $intPageId = (int) get_post_meta($m->ID, '_menu_item_object_id', true );
                    $menu[$m->ID] = array();
                    $menu[$m->ID]['ID']      	 =   $m->ID;
                    $menu[$m->ID]['title']       =   $m->title;
                    $menu[$m->ID]['url']         =   $m->url;
                    $menu[$m->ID]['children']    =   array();
                    $menu[$m->ID]['page_id']  	 = $intPageId;
                }
            }
        }
        $submenu = array();
        if(!empty($array_menu)){
            foreach ($array_menu as $m) {
                if($m->menu_item_parent) {
                    $submenu[$m->ID] = array();
                    $submenu[$m->ID]['ID']       =   $m->ID;
                    $submenu[$m->ID]['title']    =   $m->title;
                    $submenu[$m->ID]['url']  =   $m->url;
                    $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
                }
            }
            return $menu;
        }
    }
}
?>