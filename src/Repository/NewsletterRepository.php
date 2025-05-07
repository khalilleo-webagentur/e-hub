<?php

namespace App\Repository;

use App\Entity\Newsletter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Newsletter>
 *
 * @method Newsletter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Newsletter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Newsletter[]    findAll()
 * @method Newsletter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Newsletter::class);
    }

    public function save(Newsletter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createOrUpdate(Newsletter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Newsletter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Newsletter[]
     */
    public function findAllByUserOrderByDesc(UserInterface $user): array
    {
        return $this->createQueryBuilder('t1')
            ->where('t1.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t1.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Newsletter[]
     */
    public function findAllOrderByDesc(): array
    {
        return $this->createQueryBuilder('t1')
            ->orderBy('t1.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}