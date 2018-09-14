<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="campaign_contacts")
 * @ORM\Entity(repositoryClass="App\Repository\CampaignContactRepository")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CampaignContact
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campaign", fetch="EAGER")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     **/
    private $campaign;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", fetch="EAGER")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     **/
    private $contact;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="integer")
     */
    private $count;

    public function __construct()
    {
        $this->count = 0;
        $this->isActive = true;
    }

    public function getId(): ? string
    {
        return (string) $this->id;
    }

    public function getCampaign(): ? Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(Campaign $campaign): void
    {
        $this->campaign = $campaign;
    }

    public function getContact(): ? Contact
    {
        return $this->contact;
    }

    public function setContact(Contact $contact): void
    {
        $this->contact = $contact;
    }

    public function isActive(): bool
    {
        return (bool) $this->isActive;
    }

    public function setIsActive(bool $active): void
    {
        $this->isActive = $active;
    }

    public function getCount(): ? int
    {
        return $this->count;
    }

    public function count(): void
    {
        $this->count++;
    }
}
