<?php

namespace Support\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sponsor')
            ->add('eventName')
            ->add('eventType')
            ->add('recStartTime')
            ->add('recCloseTime')
            ->add('mainTarget')
            ->add('limitType')
            ->add('limitMin')
            ->add('limitMax')
            ->add('summary')
            ->add('detail')
            ->add('ctiy')
            ->add('businessType')
            ->add('assetSize')
            ->add('requirement')
            ->add('enquiry')
            ->add('oriUrl')
            ->add('readCount')
            ->add('created')
            ->add('updated')
            ->add('isEnable')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Support\AdminBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'support_adminbundle_product';
    }
}
