<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHLAK\Twine\Str;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
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
    const DIRECT = 'direct';
    const FORM = 'form';
    const CHAT = 'chat';

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", fetch="EAGER")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $client;

    /**
     * @ORM\Column(type="string", length=7)
     *
     * @Assert\Length(max=7)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=77)
     *
     * @Assert\Length(max=77)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
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
     *
     * @Groups({"read"})
     */
    private $facebookPixel;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Length(max=255)
     *
     * @Groups({"read"})
     */
    private $greetingMessage;

    public function __construct()
    {
        $this->type = self::CHAT;
    }

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

    public function getType(): ? string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        if (in_array($type, [self::CHAT, self::DIRECT, self::FORM])) {
            $this->type = $type;
        }
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
