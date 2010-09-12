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

    /**
     * Renders the anchor tag for this menu item.
     *
     * If no uri is specified, or if the uri fails to generate, the
     * label will be output.
     *
     * @return string
     */
    public function renderLink()
    {


        $label = $this->renderLabel();
        $uri = $this->getUri();
        if (!$uri) {
            return $label;
        }

        return '<span></span>' . parent::renderLink();
    }
}