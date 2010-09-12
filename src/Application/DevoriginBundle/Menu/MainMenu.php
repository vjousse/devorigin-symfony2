<?php
namespace Application\DevoriginBundle\Menu;
use Bundle\MenuBundle\Menu;
use Symfony\Component\Routing\Router;

class MainMenu extends Menu
{
    public function __construct(Router $router)
    {
        parent::__construct();

        $this->addChild(new DevoriginMenuItem('Accueil', $router->generate('homepage')));
        $this->addChild(new DevoriginMenuItem('Réalisations', $router->generate('portfolio')));

    }


}