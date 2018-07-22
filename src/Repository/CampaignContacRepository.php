<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CampaignContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignContacRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CampaignContact::class);
    }

    public function findByCampaignSlug(string $slug): ? CampaignContact
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->join('o.campaign', 'c');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.slug', $queryBuilder->expr()->literal($slug)));
        $queryBuilder->addOrderBy('o.count', 'ASC');
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }

    public function updateContactUse(CampaignContact $campaignContact)
    {
        $campaignContact->count();

        $this->_em->persist($campaignContact);
        $this->_em->flush();
    }
}
