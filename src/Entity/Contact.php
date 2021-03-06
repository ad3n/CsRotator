<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHLAK\Twine\Str;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="contacts", indexes={@ORM\Index(name="contact_search_idx", columns={"name", "whats_app_number"})})
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 *
 * @UniqueEntity("name")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Contact
{
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
     * @ORM\Column(type="string", length=77)
     *
     * @Assert\Length(max=77)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=17)
     *
     * @Assert\Length(max=17)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $whatsAppNumber;

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
    }

    public function getWhatsAppNumber(): ? string
    {
        return (string) $this->whatsAppNumber;
    }

    public function setWhatsAppNumber(string $whatsAppNumber): void
    {
        $this->whatsAppNumber = $whatsAppNumber;
    }
}
