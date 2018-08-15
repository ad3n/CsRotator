<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHLAK\Twine\Str;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users", indexes={@ORM\Index(name="user_search_idx", columns={"username"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @UniqueEntity("username")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class User implements UserInterface, \Serializable
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $client;

    /**
     * @ORM\Column(type="string", length=12)
     *
     * @Assert\Length(max=17)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $username;

    /**
     * @ORM\Column(name="kata_sandi", type="string")
     */
    private $password;

    private $plainPassword;

    public function getId(): ? string
    {
        return (string) $this->id;
    }

    public function getGroup(): ? Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): void
    {
        $this->group = $group;
    }

    public function getClient(): ? Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = Str::make($username)->lowercase();
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword(): ? string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->group,
            $this->client
        ]);
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->username, $this->password, $this->group, $this->client) = unserialize($serialized);
    }

    public function getSalt(): ? string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
