<?php

namespace Support\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
class BoardController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     * @Template()
     */
    public function indexAction()
    {//Search Controller의 lineup을 호출하여 isEnable이 1을 Product의 정보를 전부 정렬해서 보여준다. Front 페이지에선 볼 수 없는 것들도 DB의 칼럼을 그대로 보여준다. 
        return array();
    }//End listInfoAction
    
    
}
