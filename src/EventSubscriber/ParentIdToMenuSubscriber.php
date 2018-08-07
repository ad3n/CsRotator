<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\FilterRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ParentIdToMenuSubscriber implements EventSubscriberInterface
{
    private $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    public function filterRequest(FilterRequestEvent $event)
    {
        $request = $event->getRequest();
        $object = $event->getObject();
        if (!$object instanceof Menu) {
            return;
        }

        $parentId = $request->request->get('parent');
        $request->request->remove('parent');

        if (!$parentId) {
            return;
        }

        $object->setParent($this->menuRepository->find($parentId));
    }

    public static function getSubscribedEvents()
    {
        return [
            BimaAdminEvents::REQUEST_EVENT => [['filterRequest']],
        ];
    }
}
