<?php

Class Settings_Controller {

    public static $strSettingId = 'products_settings';
    public static $strSettingSlug = 'products_settings';
    public static $strSettingTitle = 'Shop Settings';
    public static $strSettingCapability = 'manage_options';

    public function __construct() {
        // Add the option to the uninstall list
        global $nm_uninstall;
        $nm_uninstall['options'][] = static::$strSettingId;

        // Add the admin menu options
        add_action('admin_menu', array($this, 'createSettingsPage'));
        add_action('init', array($this, 'updateAdminMessages'));
        add_filter('custom_menu_order', '__return_true');

    }

    /**
     * Add the settings page
     *
     */
    public function createSettingsPage() {
        global $menu;

        // Add the VP separator
        $menu[] = array('', 'read', 'separator-donation_settings', '', 'wp-menu-separator');

        // Add the setting page
        add_menu_page(
            static::$strSettingTitle,
            static::$strSettingTitle,
            static::$strSettingCapability,
            static::$strSettingSlug,
            array($this, 'createSettingsHtml'),
            null,
            85
        );
    }

    /**
    * Update Season Dates
    *
    */
    public static function updateAdminMessages(){
        if(isset($_GET['page']) && $_GET['page'] == 'products_settings'){
            if (isset($_POST['customer_email_message'])){
                $strAdminEmailMessage = $_POST['customer_email_message'];
                update_option('customer_email_message', $strAdminEmailMessage);
            }
        }
    }

    /**
     * Outputs the form HTML
     *
     */
    public static function createSettingsHtml(){
        if(isset($_GET['page']) && $_GET['page'] == 'products_settings'){
            $strAdminEmailContent = get_option('customer_email_message');
            
            // Define Empty Strings
            $strHtmlFromStart = '';
            $strHtmlFromEnd = '';
            // Form Start HTML
            $strHtmlFromStart .= '<div class="admin-shop-settings">';
            $strHtmlFromStart .= '<h1>Customer Email Message</h1>';
            $strHtmlFromStart .= '<form method="post">';
            // Form End HTML
            $strHtmlFromEnd .= '<button type="submit" class="button-product-settings button button-primary button-large">Save</button>';
            $strHtmlFromEnd .= '</form>';
            $strHtmlFromEnd .= '</div>';

            echo $strHtmlFromStart;

            wp_editor(
                htmlspecialchars_decode($strAdminEmailContent), 
                'customer_email_message', 
                array(
                    "media_buttons" => false,
                )
            );

            echo $strHtmlFromEnd;

            settings_fields(static::$strSettingId);
            do_settings_sections(static::$strSettingSlug);
        }
    }
}

// Create a new instance
$objSettings = new Settings_Controller();
