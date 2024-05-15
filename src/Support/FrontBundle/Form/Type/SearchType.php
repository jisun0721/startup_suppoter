<?php

namespace Support\FrontBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Support\AdminBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
/**
 * Description of SearchType
 *
 * @author Gato
 */
class SearchType extends AbstractType{
    public function bulidForm(FormBuilderInterface $builder) {
        $builder
                ->add('rewardType', 'choice', array('choices'=> Product::$rewardCode, 'label'=>'보상 유형'))
                ->add('state','choice',array('choices'=> Product::$stateCode, 'label'=>'시' ))
                ->add('eventType','choice',array('choices'=> Product::$eventCode ,'label'=>'행사 유형'))
                ->add('mainTarget', 'choice', array('choices'=> Product::$targetCode, 'label'=>'주요 대상'))
                ->add('ageMin', 'number', array('label'=>'나이제한', 'required'=>false))
                ->add('ageMax', 'number', array('label'=>' ', 'required'=>false))
                ->add('careerMin', 'number', array('label'=>'경력제한', 'required'=>false))
                ->add('careerMax', 'number', array('label'=>'', 'required'=>false))
                ->add('keyword','text',array('label'=>'키워드', 'required'=>false))
                ->add('search','submit',array('label'=>'검색'));     
        
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                $form = $event->getForm();
                if($form->has('area')){
                    $form->remove('area');
                }
                $form->add('area','choice', array('choices'=> Product::$areaCode[$data["state"]]));
            }
        );       /*
        $builder->get('state')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $state = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $state);
            }
        );      */  
    }
    
public function getName()
    {
        return 'search';
    }
}