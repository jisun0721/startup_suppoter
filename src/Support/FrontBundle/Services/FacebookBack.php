<?php

namespace Support\FrontBundle\Services;
use Facebook\Facebook as BaseFacebook;
class FacebookBack extends BaseFacebook {
    
    public function __construct() {
        $fb = new Facebook\Facebook([
          'app_id'                => '1265635326786809',
          'app_secret'            => '533773a3d530c3a07f61836c9a33e439',
          'default_graph_version' => 'v2.5',
        ]);
        # Facebook PHP SDK v5: Check Login Status Example

        // Choose your app context helper
        $helper = $fb->getRedirectLoginHelper();
        //$helper = $fb->getCanvasHelper();
        //$helper = $fb->getPageTabHelper();
        //$helper = $fb->getJavaScriptHelper();

        // Grab the signed request entity
        $sr = $helper->getSignedRequest();

        // Get the user ID if signed request exists
        $user = $sr ? $sr->getUserId() : null;

        if ( $user ) {
          try {

            // Get the access token
            $accessToken = $helper->getAccessToken();
          } catch( Facebook\Exceptions\FacebookSDKException $e ) {

            // There was an error communicating with Graph
            echo $e->getMessage();
            exit;
          }
        }
        $permissions = ['email', 'user_posts']; // optional
        $callback    = 'http://211.57.201.86:8087/app_dev.php/';
        $loginUrl    = $helper->getLoginUrl($callback, $permissions);

        echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
        $accessToken = $helper->getAccessToken();
    }
    

}
