<?php
namespace Application\DevoriginBundle\Menu;
use Bundle\MenuBundle\MenuItem;

class DevoriginMenuItem extends MenuItem
{
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

        return sprintf('<a href="%s"><span></span>%s</a>', $uri, $label);
    }
}