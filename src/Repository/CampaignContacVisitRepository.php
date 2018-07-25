<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CampaignContactVisit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignContacVisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CampaignContactVisit::class);
    }
}
