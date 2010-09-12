<?php

namespace Application\DevoriginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DevoriginController extends Controller
{
    public function homeAction()
    {
        return $this->render('DevoriginBundle:Devorigin:home');
    }

    public function portfolioAction()
    {
        return $this->render('DevoriginBundle:Devorigin:portfolio');
    }

}
