<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CampaignContacVisitRepository;
use App\Security\Permission\Permission;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reports")
 *
 * @Permission(menu="CAMPAIGN")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ReportController extends Controller
{
    /**
     * @Route("/campaign/{slug}", methods={"GET"}, name="report_campaigns", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(string $slug, Request $request, CampaignContacVisitRepository $repository)
    {
        $startDate = \DateTime::createFromFormat('Y-m-d', $request->query->get('startDate', date('Y-m-01')));
        $endDate = \DateTime::createFromFormat('Y-m-d', $request->query->get('endDate', date('Y-m-t')));

        $statistic = $repository->getStatistic($slug, $startDate, $endDate);

        return $this->render('report/campaign.html.twig', ['title' => 'Laporan Program/Campaign', 'data' => $statistic]);
    }
}
