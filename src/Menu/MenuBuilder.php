<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker)
    {
        $this->factory = $factory;
        $this->authChecker = $authChecker;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav mr-auto mt-2 mt-md-0');

        if ($this->authChecker->isGranted('ROLE_ADMIN')) {
        }

        if ($this->authChecker->isGranted('ROLE_USER')) {
            $menu->addChild('User', array('route' => 'user_index'));
        }

        return $menu;
    }

    
    public function createSideMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills flex-column');

        if ($this->authChecker->isGranted('ROLE_USER')) {
            $menu->addChild('Navigation');
            $menu->addChild('Dashboard', array('route' => 'index'));
        }
        
        // menu items for the admin
        if ($this->authChecker->isGranted('ROLE_ADMIN')) {
            $menu->addChild('Manage');
            $menu->addChild('Room Types', array('route' => 'room_type_manage'));
            $menu->addChild('Rooms', array('route' => 'room_manage'));
            $menu->addChild('Bookings', array('route' => 'booking_manage'));
            $menu->addChild('Booking Types', array('route' => 'booking_type_manage'));
            $menu->addChild('Guests', array('route' => 'guest_manage'));
            $menu->addChild('Users', array('route' => 'user_manage'));

            $menu->addChild('New User', array('route' => 'registration'));
        }

        // menu items for the user
        if ($this->authChecker->isGranted('ROLE_USER')) {
            $menu->addChild('Account');
            $menu->addChild('Show Details', array('route' => 'user_index'));
            $menu->addChild('Change Password', array('route' => 'user_password'));
        }

        return $menu;
    }
}

?>