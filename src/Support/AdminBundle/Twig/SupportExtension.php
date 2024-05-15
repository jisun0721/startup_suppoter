<?php
namespace Support\AdminBundle\Twig;

use Symfony\Component\HttpFoundation\Request;
use Support\AdminBundle\Entity\Product;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SupportExtension
 *
 * @author Administrator
 */
class SupportExtension extends \Twig_Extension
{
    protected $request;
    protected $environment;
    
    public function setRequest(Request $request = null){
        $this->request = $request;
    }
    public function initRuntime(\Twig_Environment $environment){
        $this->environment = $environment;
    }
    
    public function getName()
    {
        return 'support_extension';
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("state",array($this, 'state')),
            new \Twig_SimpleFilter("eventType",array($this, 'eventType')),            
            new \Twig_SimpleFilter("area",array($this, 'area')),        
            new \Twig_SimpleFilter("rewardType",array($this, 'rewardType')),
            new \Twig_SimpleFilter("limitType",array($this, 'limitType')),                         
        );
        
    }
    
    public function eventType($id)
    {
       return Product::$eventType[$id];
    }
    
    public function state($id)
    {
        return Product::$state[$id];
    }
    public function area($id)
    {
        return Product::$area[$id];
    }
    public function rewardType($id)
    {
        return Product::$rewardType[$id];
    }
    public function limitType($id)
    {
        return Product::$limitType[$id];
    }
    
        
}
