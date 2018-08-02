<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use PHLAK\Twine\Str;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findAll()
    {
        return $this->findBy([], ['parent' => 'ASC']);
    }

    public function findByCode(string $code): ? Menu
    {
        return $this->findOneBy(['code' => Str::make($code)->uppercase()]);
    }
}
