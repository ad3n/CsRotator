<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\FilterRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class StringToBooleanSubscriber implements EventSubscriberInterface
{
    public function filterRequest(FilterRequestEvent $event)
    {
        $request = $event->getRequest();
        $requests = $request->request->all();
        foreach ($requests as $key => $value) {
            if ('false' === $value) {
                $request->request->set($key, false);
            }

            if ('true' === $value) {
                $request->request->set($key, true);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            BimaAdminEvents::REQUEST_EVENT => [['filterRequest', 255]],
        ];
    }
}
