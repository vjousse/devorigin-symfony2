<?php

namespace Bundle\NewsBundle\Controller;

use Symfony\Framework\FoundationBundle\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NewsBundle:Default:index');
    }
}
