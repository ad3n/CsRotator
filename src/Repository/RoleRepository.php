<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Group;
use App\Entity\Menu;
use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use PHLAK\Twine\Str;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    public function findRole(Group $group, Menu $menu): ? Role
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->leftJoin('o.group', 'g');
        $queryBuilder->leftJoin('o.menu', 'm');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('g.id', $queryBuilder->expr()->literal($group->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('m.id', $queryBuilder->expr()->literal($menu->getId())));

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true, 1, serialize($query->getParameters()));

        return $query->getOneOrNullResult();
    }

    public function getParentMenuByGroup(Group $group): array
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->select('o');
        $queryBuilder->leftJoin('o.group', 'g');
        $queryBuilder->leftJoin('o.menu', 'm');
        $queryBuilder->leftJoin('m.parent', 'p');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('g.id', $queryBuilder->expr()->literal($group->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.viewable', $queryBuilder->expr()->literal(true)));
        $queryBuilder->andWhere($queryBuilder->expr()->isNull('p'));
        $queryBuilder->addOrderBy('m.menuOrder', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true, 7, serialize($query->getParameters()));

        return $this->filterMenu($query->getResult());
    }

    public function getChildMenuByGroup(Group $group, Menu $menu): array
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->select('o');
        $queryBuilder->leftJoin('o.group', 'g');
        $queryBuilder->leftJoin('o.menu', 'm');
        $queryBuilder->leftJoin('m.parent', 'p');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.group', $queryBuilder->expr()->literal($group->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.viewable', $queryBuilder->expr()->literal(true)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('p', $queryBuilder->expr()->literal($menu->getId())));
        $queryBuilder->addOrderBy('m.menuOrder', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true, 7, serialize($query->getParameters()));

        return $this->filterMenu($query->getResult());
    }

    public function getRolesByGroup(Group $group, string $queryString)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->join('o.group', 'g');
        $queryBuilder->join('o.menu', 'm');
        $queryBuilder->orWhere($queryBuilder->expr()->like('m.name', $queryBuilder->expr()->literal(sprintf('%%%s%%', Str::make($queryString)->uppercase()))));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.group', $queryBuilder->expr()->literal($group->getId())));
        $queryBuilder->addOrderBy('m.menuOrder', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true, 3, serialize($query->getParameters()));

        return $query->getResult();
    }

    public function persist(Role $role)
    {
        $this->_em->persist($role);
    }

    public function commit()
    {
        $this->_em->flush();
    }

    private function filterMenu(array $roles): array
    {
        $menus = [];
        /** @var Role $role */
        foreach ($roles as $role) {
            $menus[] = $role->getMenu();
        }

        return $menus;
    }
}
