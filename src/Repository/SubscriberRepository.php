<?php

namespace App\Repository;

use App\Entity\Subscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscriber>
 *
 * @method Subscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscriber[]    findAll()
 * @method Subscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscriber::class);
    }

    public function save(Subscriber $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Subscriber $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Subscriber[]
     */
    public function findAllWithOffsetAndLimit(int $offset, int $limit): array
    {
        return $this->createQueryBuilder('t1')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('t1.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Subscriber[]
     */
    public function findInactiveSubscribersBasedOnModifier(string $modifier): array
    {
        return $this->createQueryBuilder('t1')
            ->where('t1.updatedAt <= :modifier')
            ->setParameter('modifier', $modifier)
            ->setMaxResults(15)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Subscriber[]
     */
    public function deleteAllOlderThanSixMonths(string $modifier): array
    {
        return $this->createQueryBuilder('t1')
            ->where('t1.token = :token')
            ->setParameter('token', '')
            ->andWhere('t1.isSubscribed = 0')
            ->andWhere('t1.updatedAt <= :modifier')
            ->setParameter('modifier', $modifier)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Subscriber[]
     */
    public function search(string $keyword): array
    {
        $text = '%' . $keyword . '%';
        $qb = $this->createQueryBuilder('t1');
        $qb
            ->where($qb->expr()->like('t1.email', ':email'))
            ->setParameter('email', $text);

        return $qb->orWhere($qb->expr()->like('t1.name', ':name'))
            ->setParameter('name', $text)
            ->getQuery()
            ->getResult();
    }
}
