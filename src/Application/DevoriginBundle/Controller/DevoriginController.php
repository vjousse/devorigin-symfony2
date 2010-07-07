<?php

namespace Application\DevoriginBundle\Controller;

use Symfony\Framework\FoundationBundle\Controller;

class DevoriginController extends Controller
{
    public function homeAction()
    {
        return $this->render('DevoriginBundle:Devorigin:home');
    }

    public function indexAction($name)
    {
        return $this->render('DevoriginBundle:Devorigin:index', array('name' => $name));
    }
}
