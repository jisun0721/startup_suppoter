<?php

namespace Support\FrontBundle\Controller;

use Support\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Support\FrontBundle\Entity\UserBookMark;

class BookmarkController extends Controller
{
    /**
     * @Route("/add",name="add_bookmark")
     */
     public function addAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $productId = $this->getDoctrine()->getRepository("SupportAdminBundle:Product")->findOneByProduct($request->get("id"));
        $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($this->getUser()->getEmail());
        
        if(empty($productId) || empty($user)){
            $request->getSession()->getFlashBag()->set("error", "즐겨찾기에 실패했습니다.");            
        }else{
            $UserBookMark = new UserBookMark();
            $UserBookMark->setUser($user);
            $UserBookMark->setMarkProduct($productId);
            $em->persist($UserBookMark);
            $em->flush();
            $request->getSession()->getFlashBag()->set("success", "즐겨찾기되었습니다.");
        }
        
        $response = new Response(json_encode($productId));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/delete",name="delete_bookmark")
     */
    public function deleteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        
        $productId = $this->getDoctrine()->getRepository("SupportAdminBundle:Product")->findOneByProduct($request->get("id"));
        $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($this->getUser()->getEmail());
        
        if(empty($productId) || empty($user)) {
            $request->getSession()->getFlashBag()->set("error", "즐겨찾기 해제하는데 실패했습니다.");
        }else{
            $id = $this->getDoctrine()->getRepository("SupportFrontBundle:UserBookMark")->loadUserBookMark($productId, $user);
            if($id == NULL){
                $request->getSession()->getFlashBag()->set("error", "즐겨찾기 해제하는데 실패했습니다.");
                $response = new Response(json_encode(null));
            }
            else {
                $em->remove($id);
                $em->flush();
                $response = new Response(json_encode($productId));
            }
        }
        
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/search",name="search_bookmark")
     */
    public function searchAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $productId = $this->getDoctrine()->getRepository("SupportAdminBundle:Product")->find($request->get("id"));
        $user = $this->getDoctrine()->getRepository("SupportFrontBundle:User")->loadUserByUsername($this->getUser()->getEmail());
        
        if(empty($productId) || empty($user)) {
            $request->getSession()->getFlashBag()->set("error", "즐겨찾기 해제하는데 실패했습니다.");
        }else{
            $id = $this->getDoctrine()->getRepository("SupportFrontBundle:UserBookMark")->loadUserBookMark($productId, $user);
            if($id == NULL){
                $request->getSession()->getFlashBag()->set("error", "즐겨찾기 해제하는데 실패했습니다.");
                $response = new Response(json_encode(false));
            }
            else {            
                $response = new Response(json_encode(true));
            }
        }
        
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
