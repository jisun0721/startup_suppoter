<?php

namespace Support\FrontBundle\Controller;

use Support\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Support\FrontBundle\Services\NaverAPI;
use Support\FrontBundle\Services\FacebookAPI;
use Support\FrontBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;


class LoginController extends Controller
{   
    /**
     * @Route("/", name="login_page")
     * @Template("")
     */   
    public function indexAction()
    {   
        $user = $this->getUser();
        if(!empty($user)){
            return $this->redirect($this->generateUrl("main_index"));
        }      
       $isMobile = $this->getDevice();     $isApp = $this->getApp();
       $login = $this->loginInformation();
        return array(
            "login"=> $login,
            "isMobile" => $isMobile,"isApp"=>$isApp,
            );  
    }
    
    /**
     * @Route("/loginemail", name="login_email")
     * @Template("")
     */    
    public function loginEmailAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        
        $email =$request->get("username");
        $password = $request->get("password");
        if(!$email || !$password){            
            $error = true;
            $request->request->set("error", $error);

            return $this->redirect($this->generateUrl("main_index", array("error"=>$error)));
       }
        $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($email);
        $error = null;
        if($user!= null){
            $encoderSrv = $this->get("security.encoder_factory");
            $encoder = $encoderSrv->getEncoder($user);
            if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())){
                $token = new UsernamePasswordToken($user, null, "support_front", $user->getRoles());
                $this->get("security.context")->setToken($token); //now the user is logged in
                //now dispatch the login event
                $event = new InteractiveLoginEvent($request, $token);
                $session->set('_security_support_front', serialize($token));
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            }else{
                $request->request->set("error", true);
            }
        }else{
                $request->request->set("error", true);
        }
                
        if(isset($error)){
            $request->set("error", true);
            return $this->redirect($this->generateUrl("main_index"));
        }else{
            return $this->redirect($this->generateUrl("main_index"));
        }
    }

    /**
     * @Route("/naver", name="naver_login")
     */            
    public function loginNaverAction()
    {
	//NaverAPI를 이용하여 ID와 Password를 가져와 로그인을 하고, 새로 왔다면 가져가져온 것으로 회원가입을 시켜놓는다.
        $naver = new NaverAPI();
        $state = $this->getRequest()->get("state");
        $code = $this->getRequest()->get("code");
        $sesState = $this->getRequest()->getSession()->get("state");
                                
        // CSRF 방지를 위한 state token 검증 코드
        // 세션 또는 별도의 스토리지에 저장된 state token 과 callback으로 전달받은 state 값이 일치하여야 함
        if($state !== $sesState){;
            return $this->redirect($this->generateUrl("main_index"));
        }
        $result = $naver->loginProcess($this, $code, $state);                            
 
        if(empty($result)){
            return $result ;//$this->redirect($this->generateUrl("login_page"));
        }
        
        $this->getRequest()->getSession()->set("nid_".$result["enc_id"], $result);                       
        $doctrine = $this->getDoctrine();        
        $nid = explode("@", $result["email"]);
        $user = $doctrine->getRepository("SupportFrontBundle:User")->findOneByEmail($result["email"]);
        
        if(empty($user)){
            $em = $doctrine->getManager();            
            $user = new User();
            $user->setUserName($result["nickname"]);
            $user->setType(User::USER_TYPE_NAVER);
            $user->setFacebookId("");
            $user->setEmail($result["email"]);
            $user->setNaverId($nid[0]);            
            $user->setPassword($state);
            $user->setAge((int)substr($result["age"],0,1));
            $user->setSalt($result["enc_id"]);
            $em->persist($user);
            $em->flush();
        }
        
        $id = $user->getId();
        if($user->getIsEnable() == false)
        {
            return $this->redirect($this->generateUrl("user_agree", array("id"=>$id)));
        }
        
        $user = $this->withoutLogin($user->getEmail());
        
        return $this->redirect($this->generateUrl("main_index"));    
    }//End loginNaverAction

    /**
     * @Route("/facebook", name="facebook_login")
     */
    public function loginFacebookAction(){
        $facebook = new FacebookAPI(FacebookAPI::$config);
        $doctrine = $this->getDoctrine();
        //API를 구버젼화하였다. 헬퍼 그런거 음슴           
        $accessToken = $facebook->getAccessToken();
        $fields = '?fields=email,name,age_range';
        $me = $facebook->api('/me'.$fields, 'GET');
        if($me["email"]==null){
            $email = $me["id"]."@facebook.com";
        }else{
            $email = $me["email"];
        }
        $user = $doctrine->getRepository("SupportFrontBundle:User")->findOneByEmail($email);
        if(empty($user)){
            $em = $doctrine->getManager();
            
            $user = new User();
            $user->setEmail($email);
            $user->setType(User::USER_TYPE_FACEBOOK);
            $user->setFacebookId($email);
            $user->setNaverId("");
            $user->setPassword("");
            $user->setSalt("");
            $user->setAge((int)substr($me["age_range"]["min"],0,1));            
            $user->setUsername($me["name"]);
            $em->persist($user);
            $em->flush();
        }
                
        $id = $user->getId();
        if($user->getIsEnable() == false)
        {
            return $this->redirect($this->generateUrl("user_agree", array("id"=>$id)));
        }
        $user=$this->withoutLogin($user->getEmail());
        return $this->redirect($this->generateUrl("main_index"));
    }//end facebook Login

     /**
     * @Route("/bflogout",name="before_logout")
     */
    public function beforeLogout(){
        $response = new Response();

        $response->headers->addCacheControlDirective( 'no-cache', true );
        $response->headers->addCacheControlDirective( 'max-age', 0 );
        $response->headers->addCacheControlDirective( 'must-revalidate', true );
        $response->headers->addCacheControlDirective( 'no-store', true );        
        $response->headers->clearCookie('isLogined');
        $response->headers->clearCookie('userEmail');
        $response->send();
        return ($this->redirect($this->generateUrl("logout")));
    }
    
    

    /**
     * @Route("/",name="logout")
     */
    public function logout()
    {
	//로그인 된 정보를 로그아웃처리를 하면서 Title 화면으로 보내준다.
    }
    
    /**
     * @Route("/agree/{id}",name="user_agree")
     * @Template("")
     */
    public function agreePolicyAction($id){
        $isMobile = $this->getDevice();
        $isApp = $this->getApp();
        $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->find($id);
        $form = $this->createFormBuilder()
                ->add('agreeSignIn', 'checkbox', array('label'=>"이용약관, 개인정보 수집에 동의합니다.",'required'=>true))
                ->add('agreeOnlyRef', 'checkbox', array('label'=>"주의사항을 확인하였습니다.", 'required'=>true))
                ->add('agree', 'submit', array('label'=>'가입'))
                ->getForm();
        
        $form->handleRequest($this->getRequest());
        
        if($form->isValid())
        {
            $data = $form->getData();
            if($data["agreeSignIn"] == true && $data["agreeOnlyRef"] == true)
            {
               $user->setIsEnable(true);
               $user->setUpdated(time());
               $this->getDoctrine()->getManager()->flush();
               $user=$this->withoutLogin($user->getEmail());
               return $this->redirectToRoute('main_index');
            }
        }

        return array("form"=>$form->createView(),"isMobile"=>$isMobile,"isApp"=>$isApp, "id"=>$id);
    }

    /**
     * @Route("/policy",name="policy")
     * @Template("")
     */
    public function policyPageAction(){
        $isMobile = $this->getDevice();
        $isApp = $this->getApp();

        return array("isMobile"=>$isMobile,"isApp"=>$isApp);
    }

    /**
     * @Route("/defpolicy",name="default_policy")
     * @Template("")
     */
    public function defaultPolicyAction(){

        $form = $this->createFormBuilder()
                ->add('agreeSignIn', 'checkbox', array('label'=>"약관에 동의하시겠습니까?",'required'=>true))
                ->add('agreeOnlyRef', 'checkbox', array('label'=>"이에 동의하시겠습니까?",'required'=>true))
                ->add('agree', 'submit', array('label'=>'다음'))
                ->getForm();
        
        $form->handleRequest($this->getRequest());
        
        if($form->isValid())
        {
            $data = $form->getData();
            if($data["agreeSignIn"] == true && $data["agreeOnlyRef"] == true)
            {
               return $this->redirectToRoute('signin_page');
            }
        }

        return array("form"=>$form->createView());
    }
    
    
    private function withoutLogin($email){
        
        $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($email);
        if (!$user) {
            throw new UsernameNotFoundException("User not found");
        } else {
            $token = new UsernamePasswordToken($user, null, "support_front", $user->getRoles());
            $this->get("security.context")->setToken($token); //now the user is logged in                 
            //now dispatch the login event
            $request = $this->get("request");            
            $event = new InteractiveLoginEvent($request, $token);
            $this->getRequest()->getSession()->set('_security_support_front', serialize($token));            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            $response = new Response();
            $response->headers->setCookie(new Cookie('isLogined', 1, time() + (3600 * 48)));
            $response->send();
        }               
         return $user;
    }
    
}
