<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Campaign;
use App\Entity\CampaignContact;
use App\Entity\CampaignContactVisit;
use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;
use PHLAK\Twine\Str;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CampaignContact::class);
    }

    public function countService(Contact $contact): ? int
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('total', 'total');

        $query = $this->_em->createNativeQuery(sprintf("SELECT SUM(count) AS total FROM campaign_contacts WHERE contact_id = '%s';", $contact->getId()), $rsm);

        return (int) $query->getSingleScalarResult();
    }

    public function findByCampaignAndContact(string $campaignId, string $contactId): ? CampaignContact
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->join('o.campaign', 'c');
        $queryBuilder->join('o.contact', 'ct');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.id', $queryBuilder->expr()->literal($campaignId)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('ct.id', $queryBuilder->expr()->literal($contactId)));

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }

    public function findByCampaignSlug(string $slug, string $type = Campaign::CHAT): ? CampaignContact
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->join('o.campaign', 'c');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.slug', $queryBuilder->expr()->literal($slug)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.type', $queryBuilder->expr()->literal($type)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.isActive', $queryBuilder->expr()->literal(true)));
        $queryBuilder->addOrderBy('o.count', 'ASC');
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);

        return $query->getOneOrNullResult();
    }

    public function findByCampaign(Campaign $campaign, string $queryString, string $type = Campaign::CHAT): array
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->join('o.campaign', 'cmp');
        $queryBuilder->join('o.contact', 'cnt');
        $queryBuilder->orWhere($queryBuilder->expr()->like('cnt.name', $queryBuilder->expr()->literal(sprintf('%%%s%%', Str::make($queryString)->uppercase()))));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('cmp.type', $queryBuilder->expr()->literal($type)));
        $queryBuilder->orWhere($queryBuilder->expr()->like('cnt.whatsAppNumber', $queryBuilder->expr()->literal(sprintf('%%%s%%', Str::make($queryString)))));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('cmp.id', $queryBuilder->expr()->literal($campaign->getId())));
        $queryBuilder->addOrderBy('cnt.name', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true, 3, serialize($query->getParameters()));

        return $this->filterContact($query->getResult());
    }

    public function findByCampaignSlugAndWhatsAppNumber(string $slug, string $whatsAppNumber, string $type = Campaign::CHAT): ? CampaignContact
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder->join('o.campaign', 'c');
        $queryBuilder->join('o.contact', 'ct');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.slug', $queryBuilder->expr()->literal($slug)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.type', $queryBuilder->expr()->literal($type)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('ct.whatsAppNumber', $queryBuilder->expr()->literal($whatsAppNumber)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.isActive', $queryBuilder->expr()->literal(true)));

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

    public function resetServiceCount()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->update(CampaignContact::class, 'o');
        $queryBuilder->set('o.count', 0);
        $queryBuilder->getQuery()->execute();
    }

    private function filterContact(array $campaignContacts): array
    {
        $contacts = [];
        /** @var CampaignContact $campaignContact */
        foreach ($campaignContacts as $campaignContact) {
            $contact = $campaignContact->getContact();

            $contacts[] = [
                'id' => $contact->getId(),
                'name' => $contact->getName(),
                'whatsAppNumber' => $contact->getWhatsAppNumber(),
                'status' => $campaignContact->isActive(),
            ];
        }

        return $contacts;
    }
}
