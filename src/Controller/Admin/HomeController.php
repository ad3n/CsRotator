<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CampaignContactVisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="home_index")
     */
    public function index(Request $request, CampaignContactVisitRepository $campaignContacVisitRepository)
    {
        $startDate = \DateTime::createFromFormat('Y-m-d', $request->query->get('startDate', date('Y-m-01')));
        $endDate = \DateTime::createFromFormat('Y-m-d', $request->query->get('endDate', date('Y-m-t')));

        $statistic = $campaignContacVisitRepository->getAllStatistic($startDate, $endDate);
        $contactStatistic = $campaignContacVisitRepository->getAllContactStatistic($startDate, $endDate);

        return $this->render('report/campaign.html.twig', [
            'title' => 'Laporan Keseluruhan',
            'data' => $statistic,
            'contact' => $contactStatistic,
        ]);
    }
}
