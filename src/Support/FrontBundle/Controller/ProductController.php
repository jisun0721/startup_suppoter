<?php

namespace Support\FrontBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Support\AdminBundle\Services\GMemcached;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Support\FrontBundle\Form\Type\SearchType;
use Support\AdminBundle\Entity\Product;
use Support\FrontBundle\Entity\Reply;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Support\FrontBundle\Controller\Controller;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ProductController extends Controller
{

    /**
     * @Route("/search",name="search_page")
     * @Template()
     */
    public function searchAction()
    {
        $cookies = $this->getRequest()->cookies;
        $isMobile = $this->getDevice();     $isApp = $this->getApp();
        $request = $this->getRequest();
        $repository = $this->getDoctrine()->getRepository('SupportAdminBundle:Product');
        $page = (int)$request->get("page");
        $order;
        $keyword = $request->get("keyword");
        $asc = "ASC";
        
        if(empty($page)){ $page = 0;}
        if(empty($order)){ $order = "eventName";}
        if(empty($keyword)){$keyword=null;}
        
        $form = $this ->createFormBuilder()
                ->add('keyword','text',array('label'=>'키워드', 'data'=>$keyword))
                ->add('search','submit',array('label'=>'검색'))        
                ->getForm();
    
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
         //   $order = $data["order"];
        //    unset($data["order"]);
            $page = 0;
            if($order != "eventName"){
            $asc = "DESC";}
            
            $products = $repository-> findForProducts($page*self::DEFAULT_PAGE, $data, $order, $asc);
            $pager = $repository->maxPage($page*self::DEFAULT_PAGE,self::DEFAULT_PAGE, $products);
            $products = array_slice($products, 0, self::DEFAULT_PAGE);
            $pagedata = array("page"=>$page, "order"=>$order, "keyword"=>$data["keyword"]);
                        
            return array("products"=>$products, "isMobile"=>$isMobile,"isApp"=>$isApp,"pager"=>$pager, "pagedata"=>$pagedata, "form"=>$form->createView());
        }

        $products = $repository-> findForProducts($page*self::DEFAULT_PAGE, array("keyword"=>$keyword), $order, $asc);
        $pager = $repository->maxPage($page*self::DEFAULT_PAGE,self::DEFAULT_PAGE, $products);
        $products = array_slice($products, 0, self::DEFAULT_PAGE);
        $pagedata = array("page"=>$page, "order"=>$order, "keyword"=>$keyword);

        return array("products"=>$products, "isMobile" => $isMobile,"isApp"=>$isApp, "pager"=>$pager,"pagedata"=>$pagedata, "form"=>$form->createView());        
    }
    
    /**
     * @Route("/csearch/{id}",name="custom_search")
     * @Template()
     */
    public function customSearchAction($id){
        $isMobile = $this->getDevice();     
        $isApp = $this->getApp();
        if($id < 0 || $id > 3){
           return $this->redirect($this->generateUrl("main_index"));
        }
        
        $request = $this->getRequest();
        $page = (int)$request->get("page");
        $order = $request->get("order");
        $asc = "ASC";
        
        if(empty($page)){ $page = 0;}
        if(empty($order)){ $order = "eventName";}
        
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
        $search = null;
        // id가 0 : 전체, 1 : 나이, 2 : 업종, 3 : 지역
        if($id == 1 || $id == 0)
        {
            $search = "나이";
            $data["age"] = $user->getAge();
        }
        if($id == 2 || $id == 0)
        {
            $search = "업종";
            $data["businesses"] = array();
            
            foreach($businesses as $business){
             $data["businesses"][] = $business[1];
            }
        }
        if($id == 3 || $id == 0)
        {
            $search = "지역";
            $data["areas"] = array();
            
            foreach($businesses as $business){
                $data["areas"][] = $business[3];
            }
        }
        if($id == 0){$search = "전체";}
        $products = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')
                ->findCustomProducts($page * self::DEFAULT_PAGE, $data, $order, $asc);
        
        $pager = $this->getDoctrine()->getRepository('SupportAdminBundle:Product')
                ->maxPage($page * self::DEFAULT_PAGE, self::DEFAULT_PAGE, $products);
        
        $products = array_slice($products, 0, self::DEFAULT_PAGE);
        
        $pagedata = array("page"=>$page, "order"=>$order);
        return array("products"=> $products, "isMobile" => $isMobile,"isApp"=>$isApp, "pager"=> $pager, "pagedata"=>$pagedata,"search"=>$search, "userBus"=>$userBusinesses );
    }

    /**
     * @Route("/detail/{id}", name="product_detail")
     * @Template()
     */
    public function detailAction($id)
    {   
        $isMobile = $this->getDevice();
        $isApp = $this->getApp();        
        if(empty($id)){
           return $this->redirect($this->generateUrl("main_index"));
        }

        $product = $this->getDoctrine()->getRepository("SupportAdminBundle:Product")->find($id);
    
        if(empty($product)) {
            return $this->redirect($this->generateUrl("main_index"));
        }

        $product->setReadCount($product->getReadCount() + 1);
        $this->getDoctrine()->getManager()->flush();

        $replies = $this->getDoctrine()->getRepository("SupportFrontBundle:Reply")->loadReplyByProduct($product->getId());
        
        return array("item"=>$product, "isMobile" => $isMobile,"isApp"=>$isApp, "replies"=>$replies);
    }

    /**
     * @Route("/add", name="add_reply")
     */
    public function addReplyAction()
    {
        $request = $this->getRequest();
        $product = $this->getDoctrine()->getRepository("SupportAdminBundle:Product")->find($request->get("productId"));
        $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($this->getUser()->getEmail());
        
       $em = $this->getDoctrine()->getManager();
           
       $reply = new Reply();
       $reply->setProductid($product);
       $reply->setUserid($user);
       $reply->setTitle("");
       $reply->setContent($request->get("content"));

       $em->persist($reply);
       $em->flush();

       $response = new Response(json_encode(array(true)));
       $response->headers->set('Content-Type', 'application/json');
       return $response;
    }
    
    
    /**
     * @Route("/delete", name="delete_reply")
     */
   public function deleteReplyAction(){
        $em = $this->getDoctrine()->getManager();
        
        $reply = $this->getDoctrine()->getRepository("SupportFrontBundle:Reply")->find($this->getRequest()->get("replyId"));
        $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($this->getUser()->getEmail());
        
        if(($user->getId()) == ($reply->getUserid()->getId()))
        {
            $em->remove($reply);
            $em->flush();
            $response = new JsonResponse();
            $response->setData(true);
        }
        else
        {
            $response = new JsonResponse();
            $response->setData(false);
        }

        return $response;
    }

    public function allFree(){
        
    }

}
