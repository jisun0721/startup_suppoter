<?php

namespace Support\FrontBundle\Controller;

use Support\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Support\AdminBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class MainPageController extends Controller
{
    /**
     * @Route("/", name="main_index")
     * @Template("")
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        
        if($request->cookies->get("userEmail") == null && $this->getUser() != null){
            $response = new Response();
            $response->headers->setCookie(new Cookie("userEmail", $this->getUser()->getEmail(), time() + (3600 * 48)));
            $response->send();
        }
        
        $isMobile = $this->getDevice();
        $isApp = $this->getApp();
        if($isMobile == TRUE){
            return  $this->redirect($this->generateUrl("all_page"));
        }else{
            return  $this->redirect($this->generateUrl("main_front"));            
        }
        
    }
    /**
     * @Route("/m", name="main_front")
     * @Template("")
     */
    public function frontAction() {
        $request = $this->getRequest();
        
        if($request->cookies->get("userEmail") == null && $this->getUser() != null){
            $response = new Response();
            $response->headers->setCookie(new Cookie("userEmail", $this->getUser()->getEmail(), time() + (3600 * 48)));
            $response->send();
        }
        
        $page = $request->get("page");
        $order;
        $asc = "ASC";
        
        
        if(empty($page)){ $page = 0;}
        if(empty($order)){ $order = "eventName";}
        $products = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')->loadProduct($page * self::DEFAULT_PAGE, $order, $asc);
        $isMobile = $this->getDevice();
        $isApp = $this->getApp();
        if($isMobile == TRUE){
            return  $this->redirect($this->generateUrl("all_page"));
        }

        $aProducts = array_slice($products, 0, self::DEFAULT_PAGE);
        $asc = "DESC";       
        $order = "readCount";
        $products = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')->loadProduct($page * self::DEFAULT_PAGE, $order, $asc);
        $pProducts =  array_slice($products, 0, self::DEFAULT_PAGE);
        $order = "created";
        $products = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')->loadProduct(0, $order, $asc);
        $now = time();
        foreach($products as $key => $product) {
            if($product->getRecCloseTime() - 60*60*24*14 > $now || $product->getRecCloseTime() < $now)
            {
                unset($products[$key]);
            }
        }

        usort($products, function($a, $b){
            return ($a->getRecCloseTime() > $b->getRecCloseTime());
        });            
        $dProducts = array_slice($products, 0, self::DEFAULT_PAGE);
        $user = $this->getUser();
        if($user){
            $order = "eventName";
            $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($this->getUser()->getEmail());
            $userBusinesses=$this->getDoctrine()->getRepository("SupportFrontBundle:UserBusiness")->loadUserBusinessByUser($user);

            $businesses = array();

            foreach($userBusinesses as $userBusiness){
                $business = array();
                $userBusinessType = $this->getDoctrine()->getRepository("SupportFrontBundle:UserBusinessType")
                        ->loadUserBusinessType($userBusiness);
                $userRegion = $this->getDoctrine()->getRepository("SupportFrontBundle:UserRegion")
                        ->loadUserRegion($userBusiness);
                array_push($business,
                        $userBusiness->getAssetSize(),
                        $userBusinessType->getBusinessType(),
                        $userRegion->getState(),
                        $userRegion->getArea(),
                        $userBusiness->getId(),
                        $userBusinessType->getId(),
                        $userRegion->getId());

                $businesses[] = $business;
            }

            $data = array("age"=>null, "businesses"=>null, "areas"=>null);
            $data["age"] = $user->getAge();
            $products = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')
                    ->findCustomProducts($page * self::DEFAULT_PAGE, $data, $order, $asc);
            $userAProducts = array_slice($products, 0, self::DEFAULT_PAGE);
            $data = array("age"=>null, "businesses"=>null, "areas"=>null);
            $data["businesses"] = array();
            foreach($businesses as $business){
                 $data["businesses"][] = $business[1];
            }      
            $products = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')
                    ->findCustomProducts($page * self::DEFAULT_PAGE, $data, $order, $asc);                
            $userBProducts = array_slice($products, 0, self::DEFAULT_PAGE);
            $data = array("age"=>null, "businesses"=>null, "areas"=>null);
            $data["areas"] = array();
            foreach($businesses as $business){
                    $data["areas"][] = $business[3];
            }                         
            $products = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')
                    ->findCustomProducts($page * self::DEFAULT_PAGE, $data, $order, $asc);                 
            $userRProducts = array_slice($products, 0, self::DEFAULT_PAGE);
            return array("aProducts"=>$aProducts,"pProducts"=>$pProducts,"dProducts"=>$dProducts,"userAProducts"=>$userAProducts, "userBProducts"=>$userBProducts,"userRProducts"=>$userRProducts,"isMobile" => $isMobile,"isApp"=>$isApp);
        }//end if($user)
        return array("aProducts"=>$aProducts,"pProducts"=>$pProducts,"dProducts"=>$dProducts,"isMobile" => $isMobile,"isApp"=>$isApp);
            
    }
    /**
     * @Route("/all", name="all_page")
     * @Template("")
     */
    public function allAction()
    {
        $request = $this->getRequest();

        $page = $request->get("page");
        $order = $request->get("order");
        $asc = "ASC";
        $isMobile = $this->getDevice();     $isApp = $this->getApp();       
        if(empty($page)){ $page = 0;}
        if(empty($order)){ $order = "eventName";}
        $products = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')->loadProduct($page * self::DEFAULT_PAGE, $order, $asc);

        $pager = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')->maxPage($page * self::DEFAULT_PAGE, self::DEFAULT_PAGE, $products);
        $products = array_slice($products, 0, self::DEFAULT_PAGE);
        $pagedata = array("page"=>$page, "order"=>$order);            
        return array("products"=> $products, "isMobile" => $isMobile,"isApp"=>$isApp, "pager"=> $pager, "pagedata"=>$pagedata);

    }
    
    /**
     * @Route("/popular", name="popular_page")
     * @Template("")
     */
    public function popularAction(){
        $isMobile = $this->getDevice();     $isApp = $this->getApp();
        $request = $this->getRequest();
        $page = $request->get("page");
        $order = "readCount";
        $asc = "DESC";
        if(empty($page)){ $page = 0;}
        
        $products = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')->loadProduct($page * self::DEFAULT_PAGE, $order, $asc);
        $pager = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')->maxPage($page * self::DEFAULT_PAGE, self::DEFAULT_PAGE, $products);

        $products = array_slice($products, 0, self::DEFAULT_PAGE);
        $pagedata = array("page"=>$page, "order"=>$order);
        
        return array("products"=>$products,"isMobile" => $isMobile,"isApp"=>$isApp, "pager"=>$pager, "pagedata"=>$pagedata);
    }
    
    /**
    * @Route("/duedate", name="duedate_page")
    * @Template("")
    */
    public function duedateAction(){
        $isMobile = $this->getDevice();     $isApp = $this->getApp();        
        $request = $this->getRequest();
        $page = $request->get("page");
        $order = "created";
        $asc = "DESC";
        if(empty($page)){ $page = 0;}
        
        $products = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')->loadProduct(0, $order, $asc);
        $now = time();
        foreach($products as $key => $product) {
            if($product->getRecCloseTime() - 60*60*24*14 > $now || $product->getRecCloseTime() < $now)
            {
                unset($products[$key]);
            }
        }
        
        usort($products, function($a, $b){
            return ($a->getRecCloseTime() > $b->getRecCloseTime());
        });
        
        $pager = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')->maxPage(0, self::DEFAULT_PAGE, $products);

        $products = array_slice($products, 0, self::DEFAULT_PAGE);
        $pagedata = array("page"=>$page, "order"=>$order);
        
        return array("products"=>$products, "isMobile" => $isMobile,"isApp"=>$isApp, "pager"=>$pager, "pagedata"=>$pagedata);
    }
    
}