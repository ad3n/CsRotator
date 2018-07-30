<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="roles")
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Menu", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $menu;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $addable;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $editable;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $viewable;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $deletable;

    public function __construct()
    {
        $this->addable = false;
        $this->editable = false;
        $this->viewable = false;
        $this->deletable = false;
    }

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

    public function getMenu(): ? Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): void
    {
        $this->menu = $menu;
    }

    public function getAddable(): bool
    {
        return $this->addable;
    }

    public function setAddable(bool $addable): void
    {
        $this->addable = (bool) $addable;
    }

    public function getEditable(): ? bool
    {
        return $this->editable;
    }

    public function setEditable(bool $editable): void
    {
        $this->editable = (bool) $editable;
    }

    public function getViewable(): ? bool
    {
        return $this->viewable;
    }

    public function setViewable(bool $viewable): void
    {
        $this->viewable = (bool) $viewable;
    }

    public function getDeletable(): ? bool
    {
        return $this->deletable;
    }

    public function setDeletable(bool $deletable): void
    {
        $this->deletable = (bool) $deletable;
    }
}
