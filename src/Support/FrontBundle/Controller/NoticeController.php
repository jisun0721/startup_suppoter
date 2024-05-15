<?php

namespace Support\FrontBundle\Controller;

use Support\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class NoticeController extends Controller
{
    /**
     * @Route("/", name="notice_index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
                // ...
            );
    }

}
