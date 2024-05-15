<?php

namespace Support\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Support\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Support\FrontBundle\Entity\User;

class PushController extends Controller {
    
    /**
     * @Route("/getuser",name="get_user")
     */
    public function getUserAction(){
        $request = $this->getRequest();
        $content = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        
        $gcmToken = $content["token"];
        $deviceId = $content["deviceId"];
        $userEmail = $content["userEmail"];
        $userEmail = str_replace("%40", "@", $userEmail);
        
        //$user는 지금 현재 접속한 유저, $originalUser는 현재의 디바이스로 접속했던 기존 유저
        if(!empty($userEmail))
            $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($userEmail);
        else
            $user = null;
        $originalUser = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->findOneBy(array("device_id"=>$deviceId));
        
        if($user == null)
        {
            $response =json_encode(false);
            return new Response($response, 200, array('Content-Type'=>'application/json'));
        }
        else{
            //현재 디바이스 아이디와 바인딩된 유저가 이미 있으면,
            //이전의 바인딩을 없앤다.
            if($originalUser != $user && $originalUser != null)
            {
                $originalUser->setDeviceId(null);
                $originalUser->setGcmToken(null);
            }
            //기존 유저가 기록된 디바이스 아이디와 다른 디바이스로 접속한 경우,
            //마지막으로 접속한 디바이스와 바인딩한다.
            if($user->getDeviceId() != $deviceId)
            {
                $user->setDeviceId($deviceId);
                $user->setGcmToken($gcmToken);
            //gcm 토큰이 갱신된 경우, 이를 바꿔준다.
            }else if($user->getGcmToken() != $gcmToken){
                $user->setGcmToken($gcmToken);
            }
            
            $em->flush();
        }
        
        $response = json_encode(true);
        return new Response($response, 200, array('Content-Type'=>'application/json'));
        
    }
    
    /**
     * @Route("/removebind",name="remove_bind")
     */
    public function removeBindAction(){
        $request = $this->getRequest();
        $content = json_decode($request->getContent(), true);
        $userEmail = $content["userEmail"];
        $userEmail = str_replace("%40", "@", $userEmail);
        $em = $this->getDoctrine()->getManager();
        
        if(!empty($userEmail))
            $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($userEmail);
        else
            $user = null;
        
        if($user == null){
            $response =json_encode(false);
            return new Response($response, 200, array('Content-Type'=>'application/json'));
        }
        
        $user->setGcmToken(null);
        $em->flush();
        
        $response = json_encode(true);
        return new Response($response, 200, array('Content-Type'=>'application/json'));
    }
}