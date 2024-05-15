<?php

namespace Support\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Security\Core\SecurityContext;
use Support\AdminBundle\Services\GMemcached;
use Support\AdminBundle\Entity\Product;
use Support\FrontBundle\Services\NaverAPI;
use Support\FrontBundle\Services\GoogleAPI;
use Support\FrontBundle\Services\FacebookAPI;
use Support\FrontBundle\Entity\User;

class Controller extends BaseController
{
    const DEFAULT_PAGE = 5;

//    public function userCheck(){
//        $user = $this->getUser();
//        if(!empty($user)){
//            return $this->redirect($this->generateUrl("main_index"));
//        }
//        return ;
//    }
//    
//    public function anonCheck() {
//        $user = $this->getUser();
//        if(empty($user)){
//            return $this->redirect($this->generateUrl("main_index"));
//        }        
//    }

    public function getDevice()
    {
        if( preg_match( '/(iPod|iPhone|iPad|Android|Mobile)/', $_SERVER[ 'HTTP_USER_AGENT' ] ) ) {
            return true;
        } else {
            return false;
        }

    }

    public function getProducts($page){
        $products = GMemcached::get(GMemcached::PREFIX_PAGE.$page);
        if(empty($products)){
            $products = $this->findProductForDisplay($page);
            GMemcached::set(GMemcached::PREFIX_PAGE.$page, $products);            
        }
        return $products;
    }

    private function findProductForDisplay($page){
        $start = $page*Product::PAGE_INFOS;
//        $rankings = GMemcached::get(GMemcached::PREFIX_RANKING);
//        if(empty($rankings)){
//            $rankings =$this->getDoctrine()->getRepository("PristoAdminBundle:Product")->buyRanking($start, Product::PAGE_ITEMS);
//            GMemcached::set(GMemcached::PREFIX_RANKING, $rankings);
//        }                    
//        $ids = array();        
//        $totalBuyed = array();
//        foreach($rankings as $ranking){
//            $ids[] = $ranking["id"];
//        }
//        foreach($rankings as $ranking){
//            $totalBuyed[$ranking["id"]] = $ranking["totalBuyed"];
//        }
        $products = GMemcached::get(GMemcached::PREFIX_INFOS.$page);
        if(empty($products)){
            $products = $this->getDoctrine()->getRepository("SupportAdminBundle:Product")->findAll();            
            GMemcached::set(GMemcached::PREFIX_INFOS.$page, $products);
        }        
        return $products;
    }    
    
    public function findProductForAll(){
        $products = GMemcached::get(GMemcached::PREFIX_ALL);
        if(empty($products)){
            $products = $this->getDoctrine()->getRepository("SupportAdminBundle:Product")->findAll();
            $ids = array();
            foreach($products as $product){
                $ids[] = $product->getId();
            }
            $products = $this->getDoctrine()->getRepository("SupportAdminBundle:Product")->findWithProducts($ids);
            GMemcached::set(GMemcached::PREFIX_ALL, $products);
        }     
        
        return $products;
    }
    public function loginInformation()
    {                   
        $error = null;
        $request = $this->getRequest();
        $session = $request->getSession();
                
        $msg =null;
        if(isset($error)){
            $msg = $request->get("error");
        }
        
//        $google = new GoogleAPI(GoogleAPI::$config);
//        $loginUrl = $google->getLoginUrl(GoogleAPI::$param);
//        $session = $this->getRequest()->getSession();
        
        $naver = new NaverAPI();
        $naverState = $naver->generateState();
        $session->set("state", $naverState);
//       $products =$this->loadProducts();
                
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        // Sessionにエラ-情報があるか確認
        } elseif ($session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            // Sessionからエラ-情報を取得
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            // 一度表示したらSessionからは削除する
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        $fb = new FacebookAPI(FacebookAPI::$config);
        $fbLoginUrl = $fb->getLoginUrl(FacebookAPI::$param);
        
        return array(
 //           "products"=> $products,
//            "googleUrl"=>$loginUrl,
            'naverState' => $naverState,
            'naverAppId' => $naver->getConfig("client"),
            'facebookUrl' => $fbLoginUrl,
            "msg" => $msg,
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error,            
            );               
    }
    
    public function getApp(){
        $cookies = $this->getRequest()->cookies;
        
        if($cookies->get('isApp') == null)
            return false;
        else if($cookies->get('isApp') == "true")
            return true;
        
        return false;
    }
}
