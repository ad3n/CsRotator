<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Campaign;
use App\Entity\Contact;
use App\Repository\ClientRepository;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\FilterRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ClientIdToObjectSubscriber implements EventSubscriberInterface
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function filterRequest(FilterRequestEvent $event)
    {
        $request = $event->getRequest();
        $object = $event->getObject();
        if (!($object instanceof Contact ||$object instanceof Campaign)) {
            return;
        }

        $clientId = $request->request->get('client');
        if (!$clientId) {
            return;
        }

        $client = $this->clientRepository->find($clientId);
        if (!$client) {
            return;
        }

        $object->setClient($client);

        $request->request->remove('client');
    }

    public static function getSubscribedEvents()
    {
        return [
            BimaAdminEvents::REQUEST_EVENT => [['filterRequest']],
        ];
    }
}
