<?php

declare(strict_types=1);

namespace App\Security\Permission;

use App\Entity\Group;
use App\Entity\Menu;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PermissionVoter extends Voter
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    protected function supports($attribute, $subject): bool
    {
        if ($subject instanceof Menu) {
            return true;
        }

        return false;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        $group = $user->getGroup();
        if (!$group instanceof Group) {
            return false;
        }

        $role = $this->roleRepository->findRole($group, $subject);
        if (!$role instanceof Role) {
            return false;
        }

        switch ($attribute) {
            case Permission::ADD:
            case Permission::EDIT:
                return $role->getAddable() || $role->getEditable();
                break;
            case Permission::VIEW:
                return $role->getViewable();
                break;
            case Permission::DELETE:
                return $role->getDeletable();
                break;
        }

        throw new \LogicException('This code should not be reached!');
    }
}
