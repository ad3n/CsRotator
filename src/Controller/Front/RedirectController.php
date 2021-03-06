<?php

declare(strict_types=1);

namespace App\Controller\Front;

use App\Entity\Campaign;
use App\Repository\CampaignContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RedirectController extends Controller
{
    /**
     * @Route("/promosi/{slug}", methods={"GET"}, name="lead")
     */
    public function lead(string $slug, CampaignContactRepository $campaignContacRepository)
    {
        $campaignContact = $campaignContacRepository->findByCampaignSlug($slug, Campaign::FORM);
        if (!$campaignContact) {
            throw new NotFoundHttpException();
        }

        return $this->render('whatsapp.html.twig', [
            'campaign' => $campaignContact->getCampaign(),
            'contact' => $campaignContact->getContact(),
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/kontak/{slug}/{whatsAppNumber}", methods={"GET"}, name="contact")
     */
    public function contact(string $slug, string $whatsAppNumber, CampaignContactRepository $campaignContacRepository)
    {
        $campaignContact = $campaignContacRepository->findByCampaignSlugAndWhatsAppNumber($slug, $whatsAppNumber, Campaign::FORM);
        if (!$campaignContact) {
            throw new NotFoundHttpException();
        }

        $campaignContacRepository->visitCampaign($campaignContact);

        return $this->render('index.html.twig', [
            'campaign' => $campaignContact->getCampaign(),
            'whatsAppNumber' => $whatsAppNumber,
        ]);
    }

    /**
     * @Route("/chat/{slug}", name="chat")
     */
    public function chat(string $slug, CampaignContactRepository $campaignContacRepository)
    {
        $campaignContact = $campaignContacRepository->findByCampaignSlug($slug, Campaign::CHAT);
        if (!$campaignContact) {
            throw new NotFoundHttpException();
        }

        $campaignContacRepository->visitCampaign($campaignContact);

        return $this->render('chat.html.twig', [
            'campaign' => $campaignContact->getCampaign(),
            'contact' => $campaignContact->getContact(),
        ]);
    }

    /**
     * @Route("/hubungi-cs/{slug}", name="direct")
     */
    public function direct(string $slug, CampaignContactRepository $campaignContacRepository)
    {
        $campaignContact = $campaignContacRepository->findByCampaignSlug($slug, Campaign::DIRECT);
        if (!$campaignContact) {
            throw new NotFoundHttpException();
        }

        $campaignContacRepository->visitCampaign($campaignContact);

        return new RedirectResponse(sprintf('https://api.whatsapp.com/send?phone=%s&text=%s', $campaignContact->getContact()->getWhatsAppNumber(), $campaignContact->getCampaign()->getGreetingMessage()));
    }
}
