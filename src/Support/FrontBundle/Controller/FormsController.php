<?php

namespace Support\FrontBundle\Controller;

use Support\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Support\AdminBundle\Entity\Product;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class FormsController extends Controller
{
    /**
     * @Route("/forms", name="forms")
     * @Template()
     */
    public function indexAction()
    {//form 중 eventlistener를 이용한 form을 모아서, html 로딩 시간을 최대한 줄인다.
        $request = $this->getRequest();
        $formBuilder = $this->createFormBuilder()
                ->add('state', 'choice', array(
                    'choices'=>Product::$stateCode,
                    'label'=>'시'))
                ->add('area', 'choice', array(
                    'choices'=>Product::$areaCode,
                    'label'=>'군/구'
                ))
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
        
        return array("form" => $form->createView());
    }
}