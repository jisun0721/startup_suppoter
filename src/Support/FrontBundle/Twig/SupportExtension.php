<?php
namespace Support\FrontBundle\Twig;

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
    
    public function getFunctions(){
        return array(
            'getUrl'=>new \Twig_Function_Method($this, 'getUrl', array('is_safe'=> array('html'))),
            'getCurrentSubNavId'=>new \Twig_Function_Method($this, 'getCurrentSubNavId', array('is_safe'=> array('html'))),
            'getControllerName' => new \Twig_Function_Method($this, 'getControllerName', array('is_safe'=> array('html'))),
            'getActionName' => new \Twig_Function_Method($this, 'getActionName', array('is_safe'=> array('html'))),
            'thumbFile' => new \Twig_Function_Method($this, 'thumbFile', array('is_safe'=> array('html'))),            
        );
    }    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("stateCode",array($this, 'stateCode')),
            new \Twig_SimpleFilter("eventCode",array($this, 'eventCode')),            
            new \Twig_SimpleFilter("areaCode",array($this, 'areaCode')),        
            new \Twig_SimpleFilter("rewardCode",array($this, 'rewardCode')),
            new \Twig_SimpleFilter("limitCode",array($this, 'limitCode')),
            new \Twig_SimpleFilter("timeToDate", array($this, 'timeToDate')),
            new \Twig_SimpleFilter("detail2br", array($this, 'detail2br'))
        );
        
    }
     
    public function thumbFile($authorId, $num, $category){
        global $kernel;
        $baseUrl = $this->request->getScheme()."://".$this->request->getHttpHost().$this->request->getBasePath();
        $itemPath = "/bundles/Kingdom/image/items/";
        $dir = sprintf("%05d", $authorId)."/".$num."/".$category;        
        
        
        
        if(file_exists($kernel->getRootDir()."/../web/".$itemPath.$dir."/thumb.jpg")){
            $thumb =$baseUrl.$itemPath.$dir."/thumb.jpg";
        }else{
            $thumb =$baseUrl.$itemPath."not.png";
        }
        return $thumb;
        
    }

    public function detail2br($string) {
        $string = nl2br($string);
        
        return $string; 
    }
    public function eventCode($id)
    {
       return Product::$eventCode[$id];
    }
    
    public function stateCode($id)
    {
        return Product::$stateCode[$id];
    }
    public function areaCode($id)
    {
        return  Product::$areaCode[$id];        
    }
    public function rewardCode($id)
    {
        return Product::$rewardCode[$id];
    }
    public function limitCode($id)
    {
        return Product::$limitCode[$id];
    }
    
    public function timeToDate($time){
        
        $date = date($time, "y/M/D H:i");
        
        return $date;
    }
    
   // コントローラー名取得    
    public function getControllerName()
    {
        if(null !== $this->request)
        {
            $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
            $matches = array();
            preg_match($pattern, $this->request->get('_controller'), $matches);

            return strtolower($matches[1]);
        }

    }

    // アクション名取得    
    public function getActionName()
    {
        if(null !== $this->request)
        {
            $pattern = "#::([a-zA-Z]*)Action#";
            $matches = array();
            preg_match($pattern, $this->request->get('_controller'), $matches);

            return $matches[1];
        }
    }    
    public function getCurrentSubNavId() {
        return substr($_SERVER['REQUEST_URI'], -1);
    }    
    
    public function getUrl() {
        return $_SERVER['REQUEST_URI'];        
    }
}
