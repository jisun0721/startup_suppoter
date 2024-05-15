<?php

namespace Support\FrontBundle\Controller;

use Support\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Support\AdminBundle\Entity\Product;
use Support\FrontBundle\Entity\UserBusiness;
use Support\FrontBundle\Entity\UserBusinessType;
use Support\FrontBundle\Entity\UserRegion;
use Support\FrontBundle\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Response;


class MyPageController extends Controller
{
    /**
     * @Route("/",name="mypage_index")
     * @Template()
    */
    public function indexAction()
    {
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirect($this->generateUrl("main_index"));
        }          
        $isMobile = $this->getDevice();     $isApp = $this->getApp();
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
                    Product::$stateCode[$userRegion->getState()],
                    Product::$areaCode[$userRegion->getArea()],
                    $userBusiness->getId(),
                    $userBusinessType->getId(),
                    $userRegion->getId());
            
            $businesses[] = $business;
        }
        return array("businesses"=>$businesses, "isMobile" => $isMobile,"isApp"=>$isApp, "user"=>$user);
    }
    
    /**
     * @Route("/editprofile",name="edit_profile")
     * @Template()
    */
    public function editProfileAction()
    {
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirect($this->generateUrl("main_index"));
        }      
        $isMobile = $this->getDevice();     $isApp = $this->getApp();
        $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($this->getUser()->getEmail());
        
        $form = $this->createFormBuilder()
                ->add('username', 'text', array('label'=>'닉네임', 'data'=>$user->getUsername()))
                ->add('age', 'choice', array('choices'=>array_slice(User::$ageCode, 1), 'label'=>'나이', 'data'=>$user->getAge() - 1))
                ->add('save', 'submit', array('label'=>'수정'))
                ->getForm();
        
        $form->handleRequest($this->getRequest());
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            
            $user->setUsername($data["username"]);
            $user->setAge($data["age"] + 1);
            
            $em->persist($user);
            $em->flush();
            
            return $this->redirect($this->generateUrl("mypage_index"));  
        }
        
        return array("form"=>$form->createView(),"isMobile" => $isMobile,"isApp"=>$isApp);
    }
    
    /**
     * @Route("/addbusiness",name="add_business")
     * @Template()
    */
    public function addBusinessAction()
    {
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirect($this->generateUrl("main_index"));
        }      
        $isMobile = $this->getDevice();     $isApp = $this->getApp();
        $request = $this->getRequest();
        $formBuilder = $this->createFormBuilder()
                ->add('asset_size', 'text', array('label'=>'자산 규모'))
                ->add('business_type', 'choice', array('choices'=>array_slice(Product::$businessTypeCode, 1, null, true), 'label'=>'업종'))
                ->add('state', 'choice', array(
                    'choices'=>Product::$stateCode,
                    'label'=>'시'))
                ->add('area', 'choice', array(
                    'choices'=>array(0=>"전체"),
                    'label'=>'군/구'
                ))
                ->add('save', 'submit', array('label'=>'저장'))
                ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event){
                    $form = $event->getForm();
                    $data = $event->getData();
                    if($data == null)
                    {
                        return;
                    }
                    $state = $data['state'];
                    $area = Product::$areaCode;
                    
                    foreach($area as $col => $val){
                        if((int)($col / 100) != (int)$state)
                            unset($area[$col]);
                    }
                    
                    $form->add('area', 'choice', array('choices'=>$area, 'label'=>'군/구'));
                });
                
        $form = $formBuilder -> getForm();

        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($this->getUser()->getEmail());
            
            $userBusiness = new UserBusiness();
            $userBusinessType = new UserBusinessType();
            $userRegion = new UserRegion();

            $userBusiness->setUser($user);
            
            $asset_size = (string)$form->get('asset_size')->getData();
            if(!ctype_digit($asset_size))
            {
                echo "<script>alert(\"자산 규모에는 정수만 입력해 주세요.\")</script>";
                return $this->redirect($this->generateUrl("add_business"));
            }
            
            $userBusiness->setAssetSize($form->get('asset_size')->getData());
            $em->persist($userBusiness);
            $em->flush();
            
            $userBusinessType->setBusinessType($form->get('business_type')->getData());
            $userBusinessType->setUserBusinessId($userBusiness);
            
            $userRegion->setArea($form->get('area')->getData());
            $userRegion->setState($form->get('state')->getData());
            $userRegion->setUserBusinessId($userBusiness);
            
            $em->persist($userBusinessType);
            $em->persist($userRegion);
            $em->flush();
            
            return $this->redirect($this->generateUrl("mypage_index"));     
        }
        return array("form"=>$form->createView(),"isMobile" => $isMobile,"isApp"=>$isApp);
    }
    
    /**
     * @Route("/delbusiness",name="delete_business")
    */
    public function deleteBusinessAction()
    {
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirect($this->generateUrl("main_index"));
        }              
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        
        $userBusinessId = $request->get("userBusinessId");
        $userBusinessTypeId = $request->get("userBusinessTypeId");
        $userRegionId = $request->get("userRegionId");  
        
        $userBusinessType = $this->getDoctrine()->getRepository("SupportFrontBundle:UserBusinessType")->find($userBusinessTypeId);
        $userRegion = $this->getDoctrine()->getRepository("SupportFrontBundle:UserRegion")->find($userRegionId);
        $userBusiness = $this->getDoctrine()->getRepository("SupportFrontBundle:UserBusiness")->find($userBusinessId);
                
        $em->remove($userBusinessType);
        $em->remove($userRegion);
        $em->remove($userBusiness);
        $em->flush();
        
        $response = new Response(json_encode(true));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/myMark",name="bookmark_page")
     * @Template()
     */
    public function myBookmarkAction()
    {
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirect($this->generateUrl("main_index"));
        }              
        $isMobile = $this->getDevice();     $isApp = $this->getApp();
        $id = $this->getUser()->getId();
        $marks = $this->getDoctrine()->getRepository("SupportFrontBundle:UserBookMark")->findUserForMark($id);

        return array("marks"=> $marks,"isMobile" => $isMobile,"isApp"=>$isApp);       
    }

    public function changeMyInfoAction()
    {
        return $this->render('SupportFrontBundle:MyPage:changeMyInfo.html.twig', array(
                // ...
            ));    
        
    }

}
