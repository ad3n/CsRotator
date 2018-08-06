<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\GroupRepository;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\FilterRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GroupIdToObjectSubscriber implements EventSubscriberInterface
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function filterRequest(FilterRequestEvent $event)
    {
        $request = $event->getRequest();
        $object = $event->getObject();
        if (!$object instanceof User) {
            return;
        }

        $groupId = $request->request->get('group');
        if (!$groupId) {
            return;
        }

        $object->setGroup($this->groupRepository->find($groupId));

        $request->request->remove('group');
    }

    public static function getSubscribedEvents()
    {
        return [
            BimaAdminEvents::REQUEST_EVENT => [['filterRequest']],
        ];
    }
}
