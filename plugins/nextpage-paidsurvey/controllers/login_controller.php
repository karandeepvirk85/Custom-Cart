<?php 
// Exit if accessed directly
defined('ABSPATH') OR exit;
header("Access-Control-Allow-Origin: *");

class Login_Controller{
  function __construct(){
    //  add_action('init', function() {
    //     static::getDataAndCreateUser();
    //  }, 100);
  }
  
  /*
  * This function make cUrl post request to get user data 
  */
  public static function getUserData(){
    
    // Set Return 
    $arrReturn = array(
      'email' => '',
      'password' =>''
    );
    // Set Constants
    $strToken = '9da12e9d-76a2-4c55-a4ae-ce97fcfda8c1';
    $strUrl = 'https://survey-api.npit.at/api/User/Me';
    
    // Set Headers
    $arrHeaders = array(
       'Content-Type: application/json',
       sprintf('Authorization: Bearer %s',$strToken)
    );
    
    // Init Curl Request 
    $objCurl = curl_init($strUrl);
    curl_setopt($objCurl, CURLOPT_HTTPHEADER, $arrHeaders);
    curl_setopt($objCurl, CURLOPT_POST, true);
    curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
    $arrResult = curl_exec($objCurl);
    // Decode Result
    $arrResult = json_decode($arrResult);
    $arrResult = (array) $arrResult;
    $arrUser = (array) $arrResult['data'];

    // If we have Email and Password 
    
    if(strlen($arrUser['email'])>0){
      $arrReturn['email'] = $arrUser['email'];
      $arrReturn['password'] = $arrUser['password'];
    }
    return $arrReturn;
  }
  
  /*
  * Function Which is triggered on Init Hook 
  * This function make a POST cUrl Request to get Email and Password
  * This function fucther check if user exists with that particular email
  * If User does exists it login the user else it create new user account and login him  
  */
  
  public static function getDataAndCreateUser(){
      $arrUsers = static::getUserData();
      $strEmail = trim($arrUsers['email']);
      $strPassword = $arrUsers['password'];
      $strUser = $strEmail.'_user';
        if(email_exists($strEmail) == false) {
          $userId = (int) wp_create_user($strUser,$strPassword,$strEmail);
          if($userId>0){
              $objUser = get_user_by('id', $userId);
              $objUser->remove_role('subscriber'); 
              $objUser->add_role('customer'); 
          if($objUser) {
            wp_set_current_user( $userId, $objUser->user_login );
            wp_set_auth_cookie( $userId );
            do_action( 'wp_login', $objUser->user_login, $objUser );
          }
        }
      }
      else{
        // If user exists in the database get his object.
        if (isset($_GET['action'])){
          $strAction = $_GET['action'];
        }
        if($strAction != 'logout'){
          if(!isset($_GET['_wpnonce'])){
            $objUser = get_user_by('email', $strEmail);
            if( $objUser ) {
              wp_set_current_user( $userId, $objUser->user_login );
              wp_set_auth_cookie( $userId );
              do_action( 'wp_login', $objUser->user_login, $objUser );
              }
            }
          }
        }
      }
  }
new Login_Controller;
?>