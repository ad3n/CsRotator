<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Campaign;
use App\Entity\CampaignContact;
use App\Repository\CampaignContacTRepository;
use App\Repository\CampaignRepository;
use App\Repository\ContactRepository;
use App\Security\Permission\Permission;
use KejawenLab\Bima\Controller\CrudController;
use KejawenLab\Bima\Request\RequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/campaigns")
 *
 * @Permission(menu="CAMPAIGN")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignContactController extends CrudController
{
    /**
     * @Route("/contacts/{campaignId}", methods={"GET"}, name="campaign_contacts", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function index(string $campaignId, Request $request, CampaignRepository $campaignRepository, CampaignContacTRepository $campaignContacRepository)
    {
        $campaign = $campaignRepository->find($campaignId);
        if (!$campaign instanceof Campaign) {
            throw new NotFoundHttpException();
        }

        $contacts = $campaignContacRepository->findByCampaign($campaign, $request->query->get('q', ''));

        $table = $this->renderView('campaign_contact/table-content.html.twig', ['contacts' => $contacts]);

        return new JsonResponse([
            'table' => $table,
        ]);
    }

    /**
     * @Route("/unrelated-contacts/{campaignId}", methods={"GET"}, name="campaign_unrelated_contacts", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function getFreeContacts(string $campaignId, CampaignRepository $campaignRepository, ContactRepository $contactRepository, SerializerInterface $serializer)
    {
        $campaign = $campaignRepository->find($campaignId);
        if (!$campaign instanceof Campaign) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($serializer->serialize($contactRepository->findUnrelatedContact($campaign), 'json', ['contacts' => ['read']]));
    }

    /**
     * @Route("/contacts/save", methods={"POST"}, name="campaign_contacts_save", options={"expose"=true})
     *
     * @Permission(actions={Permission::ADD, Permission::EDIT})
     */
    public function save(Request $request, RequestHandler $requestHandler)
    {
        $campaignContacts = new CampaignContact();
        $requestHandler->handle($request, $campaignContacts);
        if (!$requestHandler->isValid()) {
            return new JsonResponse(['status' => 'KO', 'errors' => $requestHandler->getErrors()]);
        }

        $this->commit($campaignContacts);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/contacts/{contactId}/{campaignId}/delete", methods={"POST"}, name="campaign_contacts_remove", options={"expose"=true})
     *
     * @Permission(actions=Permission::DELETE)
     */
    public function delete(string $contactId, string $campaignId, CampaignContacTRepository $repository)
    {
        if (!$campaignContacts = $repository->findByCampaignAndContact($campaignId, $contactId)) {
            return new NotFoundHttpException();
        }

        $this->remove($campaignContacts);

        return new JsonResponse(['status' => 'OK']);
    }
}
