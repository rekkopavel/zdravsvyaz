<?php

namespace App\Repository;

use App\Entity\Paste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Paste>
 */
class PasteRepository extends ServiceEntityRepository
{
    private \DateTime $currentDateTime;

    private const  PUBLIC_ACCESS = 1;
    private const UNLISTED_ACCESS = 0;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paste::class);

        $this->currentDateTime = new \DateTime();
    }

    public function save(Paste $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByUuidNotExpired(string $uuid): ?Paste
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.expired IS NULL OR p.expired > :currentDateTime')
            ->andWhere('p.uuid = :uuid')
            ->setParameter('currentDateTime', $this->currentDateTime)
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findLatestPublicNotExpired(int $limit): array
    {


        return $this->createQueryBuilder('p')
            ->andWhere('p.expired IS NULL OR p.expired > :currentDateTime')
            ->andWhere('p.access = :access')
            ->setParameter('currentDateTime', $this->currentDateTime)
            ->setParameter('access', self::PUBLIC_ACCESS)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    public function findAllWithPaginationPublicNotExpired(int $page, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.expired IS NULL OR p.expired > :currentDateTime')
            ->andWhere('p.access = :access')
            ->setParameter('currentDateTime', $this->currentDateTime)
            ->setParameter('access', self::PUBLIC_ACCESS)
            ->orderBy('p.createdAt', 'DESC') // Сортировка по дате создания
            ->setFirstResult(($page - 1) * $limit) // Пропуск записей для пагинации
            ->setMaxResults($limit) // Лимит записей на страницу
            ->getQuery();

        return new Paginator($query); // Возвращаем объект Paginator
    }


}
