<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Security\Permission\Permission;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reports")
 *
 * @Permission(menu="REPORT")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ReportController
{
    /**
     * @Route("/campaign/{slug}", methods={"GET"}, name="report_campaigns", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index(Request $request)
    {

    }
}
