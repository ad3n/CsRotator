<?php

declare(strict_types=1);

namespace App\Security\Role;

use App\Entity\User;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\FilterPaginationEvent;
use PHLAK\Twine\Str;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class FilterPagination implements EventSubscriberInterface
{
    private $token;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage->getToken();
    }

    public function filterPagination(FilterPaginationEvent $event)
    {
        if (!$this->token) {
            return;
        }

        $user = $this->token->getUser();
        if (!$user instanceof User) {
            return;
        }

        if (Permission::class !== $event->getEntityClass()) {
            return;
        }

        $queryBuilder = $event->getQueryBuilder();
        $queryBuilder->join('o.group', 'g');
        $queryBuilder->join('o.menu', 'm');

        if ($queryString = $event->getRequest()->query->get('q', '')) {
            $queryBuilder->orWhere($queryBuilder->expr()->like('m.name', $queryBuilder->expr()->literal(sprintf('%%%s%%', Str::make($queryString)->uppercase()))));
        }

        $queryBuilder->andWhere($queryBuilder->expr()->eq('g.id', $queryBuilder->expr()->literal($user->getGroup()->getId())));
        $queryBuilder->addOrderBy('m.menuOrder', 'ASC');
    }

    public static function getSubscribedEvents()
    {
        return [
            BimaAdminEvents::PAGINATION_EVENT => [['filterPagination']],
        ];
    }
}
