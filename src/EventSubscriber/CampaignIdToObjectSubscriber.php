<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\CampaignContact;
use App\Repository\CampaignRepository;
use App\Repository\ContactRepository;
use KejawenLab\Bima\BimaAdminEvents;
use KejawenLab\Bima\Event\FilterRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignIdToObjectSubscriber implements EventSubscriberInterface
{
    private $campaignRepository;

    private $contactRepository;

    public function __construct(CampaignRepository $campaignRepository, ContactRepository $contactRepository)
    {
        $this->campaignRepository = $campaignRepository;
        $this->contactRepository = $contactRepository;
    }

    public function filterRequest(FilterRequestEvent $event)
    {
        $request = $event->getRequest();
        $campaignContact = $event->getObject();
        if (!$campaignContact instanceof CampaignContact) {
            return;
        }

        $campaignId = $request->request->get('campaign');
        $contactId = $request->request->get('contact');
        $request->request->remove('campaign');
        $request->request->remove('contact');

        if (!($campaignId && $contactId)) {
            return;
        }

        $campaign = $this->campaignRepository->find($campaignId);
        $contact = $this->contactRepository->find($contactId);
        if ($campaign) {
            $campaignContact->setCampaign($campaign);
            $campaignContact->setContact($contact);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            BimaAdminEvents::REQUEST_EVENT => [['filterRequest']],
        ];
    }
}
