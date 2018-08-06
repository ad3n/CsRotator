<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Campaign;
use App\Entity\CampaignContact;
use App\Entity\CampaignContactVisit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use PHLAK\Twine\Str;

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

    public function findByCampaign(Campaign $campaign, string $queryString): array
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->join('o.campaign', 'cmp');
        $queryBuilder->join('o.contact', 'cnt');
        $queryBuilder->orWhere($queryBuilder->expr()->like('cnt.name', $queryBuilder->expr()->literal(sprintf('%%%s%%', Str::make($queryString)->uppercase()))));
        $queryBuilder->orWhere($queryBuilder->expr()->like('cnt.whatsAppNumber', $queryBuilder->expr()->literal(sprintf('%%%s%%', Str::make($queryString)))));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('cmp.id', $queryBuilder->expr()->literal($campaign->getId())));
        $queryBuilder->addOrderBy('cnt.name', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true, 3, serialize($query->getParameters()));

        return $this->filterContact($query->getResult());
    }

    public function findByCampaignSlugAndWhatsAppNumber(string $slug, string $whatsAppNumber): ? CampaignContact
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->join('o.campaign', 'c');
        $queryBuilder->join('o.contact', 'ct');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.slug', $queryBuilder->expr()->literal($slug)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('ct.whatsAppNumber', $queryBuilder->expr()->literal($whatsAppNumber)));

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }

    public function visitCampaign(CampaignContact $campaignContact): void
    {
        $campaignContact->count();

        $campaignVisit = new CampaignContactVisit();
        $campaignVisit->setCampaignContact($campaignContact);

        $this->_em->persist($campaignContact);
        $this->_em->persist($campaignVisit);
        $this->_em->flush();
    }

    private function filterContact(array $campaignContacts): array
    {
        $contacts = [];
        /** @var CampaignContact $campaignContact */
        foreach ($campaignContacts as $campaignContact) {
            $contacts[] = $campaignContact->getContact();
        }

        return $contacts;
    }
}
