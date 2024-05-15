<?php

namespace Support\AdminBundle\Controller;

use Support\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class InfoController extends Controller
{

    public function listInfoAction()
    {//Search Controller의 lineup을 호출하여 isEnable이 1을 Product의 정보를 전부 정렬해서 보여준다. Front 페이지에선 볼 수 없는 것들도 DB의 칼럼을 그대로 보여준다. 

    }//End listInfoAction
    
    public function addInfoAction()
    {
            //Admin 페이지에서 Production 의 정보를 추가하는 액션
    }//End addInfo Action

    public function editInfoAction()
    {
    //	Admin페이지에서 이미 있는 Product 테이블의 아이디를 찾아 정보를 수정하는 액션. 아이디는 수정할 수 없다.
    }//End editInfo Action

    public function deleteInfoAction()
    {
            //Admin페이지에서 이미 있는 Product 테이블의 아이디를 찾아 isEnable을 0으로 바꿔준다.
    }//End deleteInfo Action
}
