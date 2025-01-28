<?php

namespace App\Repository;

use App\Entity\Paste;
use App\Enum\PasteAccessType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

/**
 * @extends ServiceEntityRepository<Paste>
 */
class PasteRepository extends ServiceEntityRepository
{
    private const DEFAULT_PAGE_SIZE = 10;
    private const MAX_PAGE_SIZE = 100;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paste::class);
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
        $currentDateTime = new \DateTime();

        $parameters = new ArrayCollection([
            new Parameter('currentDateTime', $currentDateTime),
            new Parameter('uuid', $uuid)
        ]);

        return $this->createQueryBuilder('p')
            ->where('p.expiration IS NULL OR p.expiration > :currentDateTime')
            ->andWhere('p.uuid = :uuid')
            ->setParameters($parameters)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLatestPublicPastes(int $limit = 10): array
    {
        $this->validateLimit($limit);

        return $this->buildPublicPastesQueryBuilder()
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPublicPastesPaginated(int $page = 1, int $limit = self::DEFAULT_PAGE_SIZE): Paginator
    {
        $this->validatePaginationParameters($page, $limit);

        $queryBuilder = $this->buildPublicPastesQueryBuilder()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($queryBuilder);
    }



    private function buildPublicPastesQueryBuilder():QueryBuilder
    {
        $currentDateTime = new \DateTime();

        $parameters = new ArrayCollection([
            new Parameter('currentDateTime', $currentDateTime),
            new Parameter('access', PasteAccessType::PUBLIC->value)
        ]);

        return $this->createQueryBuilder('p')
            ->where('p.expiration IS NULL OR p.expiration > :currentDateTime')
            ->andWhere('p.access = :access')
            ->setParameters($parameters)
            ->orderBy('p.createdAt', 'DESC');
    }

    private function validateLimit(int $limit): void
    {
        if ($limit < 1 || $limit > self::MAX_PAGE_SIZE) {
            throw new \InvalidArgumentException(
                sprintf('Limit must be between 1 and %d', self::MAX_PAGE_SIZE)
            );
        }
    }

    private function validatePaginationParameters(int $page, int $limit): void
    {
        if ($page < 1) {
            throw new \InvalidArgumentException('Page number must be greater than 0');
        }

        $this->validateLimit($limit);
    }
}
