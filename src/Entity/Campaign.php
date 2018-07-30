<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHLAK\Twine\Str;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="campaigns", indexes={@ORM\Index(name="campaign_search_idx", columns={"name", "slug"})})
 * @ORM\Entity(repositoryClass="App\Repository\CampaignRepository")
 *
 * @UniqueEntity("slug")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Campaign
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", fetch="EAGER")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     **/
    private $client;

    /**
     * @ORM\Column(type="string", length=77)
     *
     * @Assert\Length(max=77)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=77)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=27)
     *
     * @Assert\Length(max=27)
     * @Assert\NotBlank()
     */
    private $facebookPixel;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Length(max=255)
     */
    private $greetingMessage;

    public function getId(): ? string
    {
        return (string) $this->id;
    }

    public function getClient(): ? Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getName(): ? string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = Str::make($name)->uppercase();
        $this->slug = $this->name->replace(' ', '-')->lowercase();
    }

    public function getSlug(): ? string
    {
        return $this->slug;
    }

    public function getFacebookPixel(): ? string
    {
        return (string) $this->facebookPixel;
    }

    public function setFacebookPixel(string $facebookPixel): void
    {
        $this->facebookPixel = $facebookPixel;
    }

    public function getGreetingMessage(): ? string
    {
        return $this->greetingMessage;
    }

    public function setGreetingMessage(string $greetingMessage): void
    {
        $this->greetingMessage = $greetingMessage;
    }
}
