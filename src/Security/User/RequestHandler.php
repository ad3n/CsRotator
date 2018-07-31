<?php

declare(strict_types=1);

namespace App\Security\User;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\FilterRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RequestHandler implements EventSubscriberInterface
{
    private $groupRepository;

    private $encoder;

    public function __construct(GroupRepository $groupRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->groupRepository = $groupRepository;
        $this->encoder = $encoder;
    }

    public function filterRequest(FilterRequestEvent $event)
    {
        $request = $event->getRequest();
        $user = $event->getObject();
        if (!$user instanceof User) {
            return;
        }

        /** @var Group $group */
        if ($group = $this->groupRepository->find($request->request->get('group'))) {
            $user->setGroup($group);
        }

        $request->request->remove('group');
    }

    public function setPassword(FilterRequestEvent $event)
    {
        $user = $event->getObject();
        if (!$user instanceof User) {
            return;
        }

        if ($plainPassword = $user->getPlainPassword()) {
            $user->setPassword($this->encoder->encodePassword($user, $plainPassword));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            BimaAdminEvents::REQUEST_EVENT => [['filterRequest']],
            BimaAdminEvents::PRE_VALIDATION_EVENT => [['setPassword']],
        ];
    }
}
