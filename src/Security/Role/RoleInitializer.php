<?php

declare(strict_types=1);

namespace App\Security\Role;

use App\Entity\Group;
use App\Entity\Menu;
use App\Entity\Role;
use App\Repository\GroupRepository;
use App\Repository\MenuRepository;
use App\Repository\RoleRepository;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\EntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleInitializer implements EventSubscriberInterface
{
    private $menuRepository;

    private $groupRepository;

    private $roleRepository;

    public function __construct(MenuRepository $menuRepository, GroupRepository $groupRepository, RoleRepository $roleRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->groupRepository = $groupRepository;
        $this->roleRepository = $roleRepository;
    }

    public function initRole(EntityEvent $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof Group && !$entity->getId()) {
            $this->initByGroup($entity);
        }

        if ($entity instanceof Menu && !$entity->getId()) {
            $this->initByMenu($entity);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            BimaAdminEvents::PRE_COMMIT_EVENT => [['initRole']],
        ];
    }

    private function initByGroup(Group $group)
    {
        $menus = $this->menuRepository->findAll();
        /** @var Menu $menu */
        foreach ($menus as $key => $menu) {
            $role = new Role();
            $role->setGroup($group);
            $role->setMenu($menu);

            $this->roleRepository->persist($role);

            if ($key > 0 && 0 === 17 % $key) {
                $this->roleRepository->commit();
            }
        }

        $this->roleRepository->commit();
    }

    private function initByMenu(Menu $menu)
    {
        $groups = $this->groupRepository->findAll();
        /** @var Group $group */
        foreach ($groups as $key => $group) {
            $role = new Role();
            $role->setGroup($group);
            $role->setMenu($menu);

            $this->roleRepository->persist($role);

            if ($key > 0 && 0 === 17 % $key) {
                $this->roleRepository->commit();
            }
        }

        $this->roleRepository->commit();
    }
}
