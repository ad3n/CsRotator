<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CampaignContacRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RedirectController extends Controller
{
    /**
     * @Route("/promosi/{slug}", name="lead")
     */
    public function lead(string $slug, CampaignContacRepository $campaignContacRepository)
    {
        $campaignContact = $campaignContacRepository->findByCampaignSlug($slug);

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
     * @Route("/kontak/{slug}/{whatsAppNumber}", name="contact")
     */
    public function contact(string $slug, string $whatsAppNumber, CampaignContacRepository $campaignContacRepository)
    {
        $campaignContact = $campaignContacRepository->findByCampaignSlug($slug);

        if (!$campaignContact) {
            throw new NotFoundHttpException();
        }

        $campaignContacRepository->visitCampaign($campaignContact);

        return $this->render('index.html.twig', [
            'campaign' => $campaignContact->getCampaign(),
            'whatsAppNumber' => $whatsAppNumber,
        ]);
    }
}
