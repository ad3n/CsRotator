<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHLAK\Twine\Str;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="groups", indexes={@ORM\Index(name="group_search_idx", columns={"name"})})
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 *
 * @UniqueEntity("name")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Group
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=77)
     *
     * @Assert\Length(max=77)
     * @Assert\NotBlank()
     */
    private $name;

    public function getId(): ? string
    {
        return (string) $this->id;
    }

    public function getName(): ? string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = Str::make($name)->uppercase();
    }
}
