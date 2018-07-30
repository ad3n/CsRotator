<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHLAK\Twine\Str;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="menus", indexes={@ORM\Index(name="menu_search_idx", columns={"code", "name"})})
 * @ORM\Entity(repositoryClass="App\Repository\MenuRepository")
 *
 * @UniqueEntity({"code", "routeName"})
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Menu
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Menu", fetch="EAGER")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     *
     * @Groups({"read"})
     **/
    private $parent;

    /**
     * @ORM\Column(type="string", length=17, unique=true)
     *
     * @Assert\Length(max=17)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=27)
     *
     * @Assert\Length(max=27)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Groups({"read"})
     */
    private $menuOrder;

    /**
     * @ORM\Column(type="string", length=27, nullable=true)
     *
     * @Assert\Length(max=27)
     *
     * @Groups({"read"})
     */
    private $iconClass;

    /**
     * @ORM\Column(type="string", length=77, nullable=true)
     *
     * @Assert\Length(max=77)
     *
     * @Groups({"read"})
     */
    private $routeName;

    public function getId(): ? string
    {
        return (string) $this->id;
    }

    public function getParent(): ? self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): void
    {
        $this->parent = $parent;
    }

    public function getCode(): ? string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = Str::make($code)->uppercase();
    }

    public function getName(): ? string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = Str::make($name)->uppercase();
    }

    public function getMenuOrder(): ? int
    {
        return $this->menuOrder;
    }

    public function setMenuOrder(int $menuOrder): void
    {
        $this->menuOrder = $menuOrder;
    }

    public function getIconClass(): ? string
    {
        return $this->iconClass;
    }

    public function setIconClass(string $iconClass): void
    {
        $this->iconClass = $iconClass;
    }

    public function getRouteName(): ? string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
    }
}
