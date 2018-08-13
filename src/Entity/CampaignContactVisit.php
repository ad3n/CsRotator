<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="campaign_contact_visits")
 * @ORM\Entity(repositoryClass="App\Repository\CampaignContactVisitRepository")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignContactVisit
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CampaignContact", fetch="EAGER")
     * @ORM\JoinColumn(name="campaign_contact_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     **/
    private $campaignContact;

    /**
     * @ORM\Column(type="datetime")
     */
    private $visitTime;

    public function __construct()
    {
        $this->visitTime = new \DateTime();
    }

    public function getId(): ? string
    {
        return (string) $this->id;
    }

    public function getCampaignContact(): ? CampaignContact
    {
        return $this->campaignContact;
    }

    public function setCampaignContact(CampaignContact $campaignContact): void
    {
        $this->campaignContact = $campaignContact;
    }
}
