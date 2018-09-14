<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\CampaignContact;
use App\Repository\CampaignContactRepository;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\EntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ResetCountSubscriber implements EventSubscriberInterface
{
    private $repository;

    public function __construct(CampaignContactRepository $repository)
    {
        $this->repository = $repository;
    }

    public function resetCount(EntityEvent $event): void
    {
        $entity = $event->getEntity();
        if (!$entity instanceof CampaignContact) {
            return;
        }

        $this->repository->resetServiceCount();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BimaAdminEvents::PRE_COMMIT_EVENT => [['resetCount']],
        ];
    }
}
