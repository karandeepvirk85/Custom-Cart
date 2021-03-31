<?php 

class Menu_Controller {
  /**
   * Function to get Wordpress Menu By Name 
   * */  
  public static function getMainByMenu($strMenuName){
        $current_menu = wp_get_nav_menu_object($strMenuName);
        $array_menu = wp_get_nav_menu_items($current_menu);
        $menu = array();
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
        $submenu = array();
        foreach ($array_menu as $m) {
            if ($m->menu_item_parent) {
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