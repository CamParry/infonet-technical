<?php

namespace App\Repository;

use App\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Character>
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

    /**
     * @return Character[] Returns an array of Character objects
     */
    public function search($value): array
    {
        return $this->createQueryBuilder('c')
            ->where('lower(c.name) LIKE lower(:search)')
            ->setParameter('search', "%{$value}%")
            ->getQuery()
            ->execute();
    }
}
