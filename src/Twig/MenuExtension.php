<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Menu;
use App\Menu\MenuLoader;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuExtension extends \Twig_Extension
{
    private $menuLoader;

    private $urlGenerator;

    public function __construct(MenuLoader $menuLoader, UrlGeneratorInterface $urlGenerator)
    {
        $this->menuLoader = $menuLoader;
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('render_menu', [$this, 'renderMenu']),
            new \Twig_SimpleFunction('code_to_menu', [$this, 'findMenu']),
        ];
    }

    public function renderMenu()
    {
        $html = '';
        $parents = $this->menuLoader->getParentMenu();
        foreach ($parents as $parent) {
            if (!$this->menuLoader->hasChildMenu($parent)) {
                $html .= $this->buildHtml($parent);
            } else {
                $html .= sprintf('<li class="treeview"><a href="#" id="%s"><i class="fa fa-%s"></i> <span>%s</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">', $parent->getId(), $parent->getIconClass() ?: 'circle-o', $parent->getName());

                $childs = $this->menuLoader->getChildMenu($parent);
                foreach ($childs as $child) {
                    $html .= $this->buildHtml($child);
                }

                $html .= '</ul></li>';
            }
        }

        return $html;
    }

    public function findMenu(string $code): ? Menu
    {
        return $this->menuLoader->findMenu($code);
    }

    private function buildHtml(Menu $menu): string
    {
        $href = $menu->getRouteName() ? $this->urlGenerator->generate($menu->getRouteName()) : '#';

        return sprintf('<li><a href="%s" id="%s"><i class="fa fa-%s"></i> <span>%s</span></a></li>', $href, $menu->getId(), $menu->getIconClass() ?: 'circle-o', $menu->getName());
    }
}
