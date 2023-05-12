<?php

/**
 * This file is a part of the fablab portal package.
 * 
 * © Claude Migne <monkey3d@wanadoo.fr>
 * 
 * file : Menu/MenuBuilder.php - date : 15 sept. 2021
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


// src/Menu/MenuBuilder.php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    private $factory;
    
    /**
     * Add any other dependency you need...
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }
    
    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'navbar-nav me-auto mb-2 mb-lg-0']);
        
        //$menu->addChild('app.menu.homepage', ['route' => 'homepage']); // - 04/02/2022
        // ... add more children;
        $menu->addChild('app.menu.news', ['route' => 'news'])
            //->setAttributes(['class' => 'text-success'])
            //->getParent()
        ;
        //-------------------------------------------------------------------------------------------
        /*
        $menu->addChild('app.menu.contact', ['uri' => '#'])
            //->setAttributes(['dropdown' => 'true'])
            ->setAttributes(['id' => 'id-menu-contact', 'icon' => 'bi bi-geo'])
            ->getParent()
        ;
        $menu['app.menu.contact']->addChild('menu.contact.address', ['route' => 'homepage'])
            ->setAttributes(['id' => 'modal-address', 'icon' => 'bi bi-geo'])
            ->getParent();
        */
        //-------------------------------------------------------------------------------------------
        $menu->addChild('app.menu.introduce', ['uri' => '#'])
            ->setAttributes(['dropdown' => 'true'])
            ->getParent()
        ;
        $menu['app.menu.introduce']->addChild('menu.introduce.team', ['route' => 'team'])
            //->setAttributes(['dropdown' => 'true'])
            ->setAttributes(['icon' => 'bi bi-diagram-2-fill'])
            ->getParent()
        ;
        $menu['app.menu.introduce']->addChild('menu.introduce.equipment', ['route' => 'equipment'])
            //->setAttributes(['dropdown' => 'true'])
            ->setAttributes(['icon' => 'bi bi-gear-fill'])
            ->getParent()
        ;
        $menu['app.menu.introduce']->addChild('menu.introduce.get_member', ['route' => 'get-member'])
            //->setAttributes(['dropdown' => 'true'])
            ->setAttributes(['icon' => 'bi bi-person-badge'])
            ->getParent()
            ;
        //-------------------------------------------------------------------------------------------
        $menu->addChild('app.menu.documentary', ['uri' => '#'])
            ->setAttributes(['dropdown' => 'true'])
            ->getParent()
            ;
        /*
        $menu['app.menu.documentary']->addChild('menu.documentary.official', ['route' => 'documentary', 'routeParameters' => ['type' => 1]])
            ->setAttributes(['dropdown' => 'true'])
            ->setAttributes(['icon' => 'bi bi-file-text-fill'])
            ->getParent()
        ;
            $menu['app.menu.documentary']->addChild('menu.documentary.meeting', ['route' => 'documentary', 'routeParameters' => ['type' => 2]])
            ->setAttributes(['dropdown' => 'true'])
            ->setAttributes(['icon' => 'bi bi-file-post-fill'])
            ->getParent()
            ;
        */
        $menu['app.menu.documentary']->addChild('menu.documentary.home', ['route' => 'document_management_home'])
            ->setAttributes(['dropdown' => 'true'])
            ->setAttributes(['icon' => 'bi bi-file-text-fill'])
            ->getParent()
        ;
        $menu['app.menu.documentary']->addChild('menu.documentary.pricing', ['route' => 'document_management_pricing'])
            ->setAttributes(['dropdown' => 'true'])
            ->setAttributes(['icon' => 'bi bi-currency-euro'])
            ->getParent()
            ;
        //-------------------------------------------------------------------------------------------
        $menu->addChild('app.menu.blog', ['route' => 'blog_home'])
            //->setAttributes(['class' => 'text-success'])
            //->getParent()
        ;
        //-------------------------------------------------------------------------------------------
        $menu->addChild('app.menu.about', ['route' => 'about']);
        return $menu;
    }
    
    public function createMemberMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'navbar-nav ms-auto']);
        
        $menu->addChild('app.menu.member')
            ->setAttributes(['dropdown' => 'true'
        ]);
        $menu['app.menu.member']->addChild('menu.member.login', ['route' => 'app_login'])
            ->setAttributes(['icon' => 'bi bi-box-arrow-in-right'])
            ->getParent();
        
        return $menu;
    }
}
