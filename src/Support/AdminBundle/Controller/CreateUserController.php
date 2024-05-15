<?php

namespace Support\AdminBundle\Controller;

use Support\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Support\FrontBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class CreateUserController extends Controller
{
    /**
     * @Route("/userlist", name="admin_userlist")
     * @Template()
     */
    public function userAction()
    {
        $em = $this->getDoctrine();
        $user_list = $em->getRepository('SupportFrontBundle:User')
                ->findByIsenabled(1);

        return array('users' => $user_list,);
    }

    /**
     * @Route("/create", name="user_create")
     * @Template()
     */
    public function createAction()
    {
        return $this->onEdit(null);
    }    

    /**
     * @Route("/edit/{id}", name="user_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if(empty($id)){
            $id = null;
        }
        
        return $this->onEdit($id);
    }    

    public function onEdit($id = null){
         $em = $this->getDoctrine()->getManager();
         $request = $this->getRequest();
         
         if(isset($id)){
             $user = $em->getRepository('SupportFrontBundle:User')->find($id);
         } else {
             $user = new User();             
             $user->setId(0);
         }
         
         $form = $this->createFormBuilder($user)                 
                 ->add('username', 'text', array("required"=> true))
                 ->add('password', 'password', array("required"=> true))
                 ->add('email', 'email', array("required"=> true))
                 ->add('가입','submit')
                 ->getForm();
         $form->handleRequest($request);
         
         if($form->isValid() ){
            $data = $form->getData();
            $duflicate = $em->getRepository('SupportFrontBundle:User')->findOneByUsername($data->getEmail());
            if(isset($duflicate)){
                $form->addError(new FormError("ID가 이미 존재합니다."));                    
            }else if(strlen($data->getUsername()) < 1){
                $form->addError(new FormError("이름을 입력하세요."));                
            }else if(strlen($data->getPassword()) < 8){
                $form->addError(new FormError("패스워드는 8자 이상입니다."));                
            }else if(!strpos($data->getEmail(), "@")){
                $form->addError(new FormError("메일 주소를 입력하세요"));                
            }else{
                 $user->setUsername($data->getEmail());
                 $user->setEmail($data->getEmail());             
                 $user->setGoogleId("");
                 $user->setNaverId("");             
                 $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
                 $pw_raw = $data->getPassword();
                 $user->setSalt($salt);
                 $user->setIsEnable(false);
                 $ef = $this->get('security.encoder_factory');
                 $password = $ef->getEncoder($user)->encodePassword($pw_raw, $salt);
                 $user->setPassword($password);
                 $em->persist($user);
                 $em->flush();
                 return $this->redirect($this->generateUrl("user_send", array("id"=>$user->getId())));
           }

        }
         return $this->render('SupportFrontBundle:Login:edit.html.twig',
                 array('user' => $user, 'form' => $form->createView(),));
    }    
    
    /**
     * @Route("/new", name="user_new")
     * @Template()
     */    
    public function newUserAction()
    {
        //회원가입 창을 띄워준다. 간단하게 email과 password를 이용한 가입이며, email과 password를 우선적으로 DB에 isEnable를 1로 추가.
        $em = $this->getDoctrine()->getManager();
//        $flashbag = $this->getRequest()->getSession()->getBag($name)
        $email = $this->getRequest()->get("email");
        $pass = $this->getRequest()->get("password");
        $username = $this->getRequest()->get("username");
                
        $duflicate = $em->getRepository('SupportFrontBundle:User')->findOneByEmail($email);
        if(isset($duflicate)){
            $status = false;
            $msg = "이미 존재하는 메일입니다.";
            $response = new Response(json_encode(array("msg"=> $msg, "status"=>$status)));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $new = new User();
        $new->setEmail($email);
        $new->setType("1");
        $new->setGoogleId("");
        $new->setNaverId("");
        $new->setIsEnable(true);
        $new->setUsername($username);        
        $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $new->setSalt($salt);
        $ef = $this->get('security.encoder_factory');
        $password = $ef->getEncoder($new)->encodePassword($pass, $salt);
        $new->setPassword($password);
        $em->persist($new);
        $em->flush();
        
        $status = true;
        $msg = "메일을 발송했습니다.";
        $response = new Response(json_encode(array("msg"=> $msg, "status"=>$status)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
    }//End newUserAction

    public function sendPasswordAction()
    {
        //비밀번호를 변경할 수 있는 메일(changePasswordAction URL)을 보내준다. 
    }//End sendPasswordAction

    public function changePasswordAction()
    {
         //메일에서 포함된 아이디를 User 테이블에서 찾아서 입력된 비밀번호로 비밀번호를 변경.
    }

}
