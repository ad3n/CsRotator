<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CampaignContactVisit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignContactVisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CampaignContactVisit::class);
    }

    public function getStatistic(string $slug, \DateTime $startDate, \DateTime $endDate): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('tanggal', 'tanggal');
        $rsm->addScalarResult('kunjungan', 'kunjungan');

        $query = $this->_em->createNativeQuery(sprintf("
            SELECT 
                DATE(ccv.visit_time) AS tanggal, 
                COUNT(ccv.visit_time) AS kunjungan 
            FROM 
                campaign_contact_visits ccv
            INNER JOIN
                campaign_contacts cc ON ccv.campaign_contact_id = cc.id
            INNER JOIN
                campaigns c ON cc.campaign_id = c.id
            WHERE
                c.slug = '%s' AND DATE(ccv.visit_time) >= '%s' AND DATE(ccv.visit_time) <= '%s'
            GROUP BY DATE(visit_time);
        ", $slug, $startDate->format('Y-m-d'), $endDate->format('Y-m-d')), $rsm);

        $results = $query->getResult();

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($startDate, $interval, $endDate);

        $output = [];
        foreach ($period as $date) {
            $output[$date->format('Y-m-d')] = 0;
        }

        foreach ($results as $result) {
            if (array_key_exists($result['tanggal'], $output)) {
                $output[$result['tanggal']] = $result['kunjungan'];
            }
        }

        return $output;
    }

    public function getContactStatistic(string $slug, \DateTime $startDate, \DateTime $endDate): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nama', 'nama');
        $rsm->addScalarResult('kunjungan', 'kunjungan');

        $query = $this->_em->createNativeQuery(sprintf("
        SELECT
            c2.name AS nama,
            COUNT(1) AS kunjungan
        FROM 
            campaign_contact_visits ccv
        JOIN 
            campaign_contacts cc ON ccv.campaign_contact_id = cc.id
        JOIN 
            campaigns c ON cc.campaign_id = c.id
        JOIN 
            contacts c2 on cc.contact_id = c2.id
        WHERE 
            c.slug = '%s' AND DATE(ccv.visit_time) >= '%s' AND DATE(ccv.visit_time) <= '%s'
        GROUP BY c2.name;
        ", $slug, $startDate->format('Y-m-d'), $endDate->format('Y-m-d')), $rsm);

        return $query->getResult();
    }

    public function getAllStatistic(\DateTime $startDate, \DateTime $endDate)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('tanggal', 'tanggal');
        $rsm->addScalarResult('kunjungan', 'kunjungan');

        $query = $this->_em->createNativeQuery(sprintf("
            SELECT 
                DATE(ccv.visit_time) AS tanggal, 
                COUNT(ccv.visit_time) AS kunjungan 
            FROM 
                campaign_contact_visits ccv
            INNER JOIN
                campaign_contacts cc ON ccv.campaign_contact_id = cc.id
            INNER JOIN
                campaigns c ON cc.campaign_id = c.id
            WHERE
                DATE(ccv.visit_time) >= '%s' AND DATE(ccv.visit_time) <= '%s'
            GROUP BY DATE(visit_time);
        ", $startDate->format('Y-m-d'), $endDate->format('Y-m-d')), $rsm);

        $results = $query->getResult();

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($startDate, $interval, $endDate);

        $output = [];
        foreach ($period as $date) {
            $output[$date->format('Y-m-d')] = 0;
        }

        foreach ($results as $result) {
            if (array_key_exists($result['tanggal'], $output)) {
                $output[$result['tanggal']] = $result['kunjungan'];
            }
        }

        return $output;
    }

    public function getAllContactStatistic(\DateTime $startDate, \DateTime $endDate)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nama', 'nama');
        $rsm->addScalarResult('kunjungan', 'kunjungan');

        $query = $this->_em->createNativeQuery(sprintf("
        SELECT
            c2.name AS nama,
            COUNT(1) AS kunjungan
        FROM 
            campaign_contact_visits ccv
        JOIN 
            campaign_contacts cc ON ccv.campaign_contact_id = cc.id
        JOIN 
            campaigns c ON cc.campaign_id = c.id
        JOIN 
            contacts c2 on cc.contact_id = c2.id
        WHERE 
            DATE(ccv.visit_time) >= '%s' AND DATE(ccv.visit_time) <= '%s'
        GROUP BY c2.name;
        ", $startDate->format('Y-m-d'), $endDate->format('Y-m-d')), $rsm);

        return $query->getResult();
    }
}
