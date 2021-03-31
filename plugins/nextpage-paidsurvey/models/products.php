<?php
if(!defined('ABSPATH')) exit;  
class Product{
    public static function getProducts($paged){
        $args = array(  
            'post_type' => 'shop_product',
            'posts_per_page' => -1,
            'post_status' => 'publish'  
        );
        $arrProducts = new WP_Query(
            array(
                'post_type'     =>'shop_product', 
                'post_status'   =>'publish', 
                'posts_per_page'=> 6,
                'paged'         => $paged, 
                'orderby'       => 'date',
                'order'         => 'DESC',
            )
        );
        return !empty($arrProducts) ? $arrProducts : array(); 
    }  
}
?>