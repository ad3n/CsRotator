<?php

declare(strict_types=1);

namespace KejawenLab\Bima\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use KejawenLab\Bima\Annotation\Crud;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\FilterPaginationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SearchSubscriber implements EventSubscriberInterface
{
    private $annotationReader;

    public function __construct(Reader $reader)
    {
        $this->annotationReader = $reader;
    }

    public function search(FilterPaginationEvent $event)
    {
        $queryBuilder = $event->getQueryBuilder();
        if ('' !== $queryString = $event->getRequest()->query->get('q', '')) {
            $annotations = $this->annotationReader->getClassAnnotations(new \ReflectionClass($event->getEntityClass()));
            foreach ($annotations as $annotation) {
                if ($annotation instanceof Crud) {
                    $searchable = $annotation->getSearchableFields();
                    foreach ($searchable as $value) {
                        $queryBuilder->orWhere($queryBuilder->expr()->like(sprintf('o.%s', $value), $queryBuilder->expr()->literal(sprintf('%%%s%%', $queryString))));
                    }
                }
            }
        }

        $queryBuilder->andWhere($queryBuilder->expr()->isNull('o.deletedAt'));
    }

    public static function getSubscribedEvents()
    {
        return [
            BimaAdminEvents::PAGINATION_EVENT => [['search']],
        ];
    }
}
