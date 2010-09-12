<?php

namespace Bundle\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class NewsController extends Controller
{
    public function listAction()
    {
        $news = $this->getRepository('News')->findLatestNewsSortedBy('createdAt');

        return $this->render('NewsBundle:News:list',array('news' => $news));
    }

    /**
     *
     * @param string $class
     * @return Bundle\NewsBundle\Entity\NewsRepository
     */
    protected function getRepository($class)
    {
        return $this->container->getDoctrine_Orm_DefaultEntityManagerService()->getRepository('Bundle\NewsBundle\Entity\\'.$class);
    }
}
