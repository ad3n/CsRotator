<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Campaign;
use App\Entity\CampaignContact;
use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function findUnrelatedContact(Campaign $campaign): array
    {
        $campaignQuery = $this->_em->getRepository(CampaignContact::class)->createQueryBuilder('cc');
        $campaignQuery->select('ct.id');
        $campaignQuery->join('cc.campaign', 'cm');
        $campaignQuery->join('cc.contact', 'ct');
        $campaignQuery->andWhere($campaignQuery->expr()->eq('cm.id', $campaignQuery->expr()->literal($campaign->getId())));

        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->notIn('o.id', $campaignQuery->getQuery()->getDQL()));
        $query = $queryBuilder->getQuery();

        $query->useQueryCache(true);
        $query->useResultCache(true, 3, serialize($query->getParameters()));

        return $query->getResult();
    }
}
