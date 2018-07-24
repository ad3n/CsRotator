<?php

declare(strict_types=1);

namespace KejawenLab\Bima\Pagination;

use Doctrine\ORM\EntityRepository;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\FilterPaginationEvent;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Paginator
{
    const PER_PAGE = 17;

    private $doctrine;

    private $paginator;

    private $request;

    private $eventDispatcher;

    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator, RequestStack $requestStack, EventDispatcherInterface $eventDispatcher)
    {
        $this->doctrine = $registry;
        $this->paginator = $paginator;
        $this->request = $requestStack->getCurrentRequest();
        $this->eventDispatcher = $eventDispatcher;
    }

    public function paginate(string $entityClass, int $limit, int $page = 1, $orderBy = NULL)
    {
        /** @var EntityRepository $repository */
        $repository = $this->doctrine->getRepository($entityClass);
        $queryBuilder = $repository->createQueryBuilder('o');

        $event = new FilterPaginationEvent();
        $event->setQueryBuilder($queryBuilder);
        $event->setRequest($this->request);
        $event->setEntityClass($entityClass);

        $this->eventDispatcher->dispatch(BimaAdminEvents::PAGINATION_EVENT, $event);

        if($orderBy === NULL) {
            $queryBuilder->addOrderBy('o.createdAt', 'DESC');
        } else {
            if(is_array($orderBy['order_by'])) {
                $order_by = $orderBy['order_by'];
                $order_type = $orderBy['order_type'];
                foreach ($order_by as $key => $value) {
                    $queryBuilder->addOrderBy($value, $order_type[$key]);
                }
            } else {
                $order_by = $orderBy['order_by'];
                $order_type = $orderBy['order_type'];
                $queryBuilder->addOrderBy($order_by, $order_type);
            }
        }

        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }
}
