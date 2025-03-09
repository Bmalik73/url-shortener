<?php

namespace App\Repository;

use App\Entity\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Url>
 */
class UrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Url::class);
    }

    /**
     * Find URL by its code
     */
    public function findByCode(string $code): ?Url
    {
        return $this->findOneBy(['code' => $code]);
    }

    /**
     * Find URL by original URL
     */
    public function findByOriginalUrl(string $originalUrl): ?Url
    {
        return $this->findOneBy(['originalUrl' => $originalUrl]);
    }

    /**
     * Save URL entity
     */
    public function save(Url $url, bool $flush = true): void
    {
        $this->getEntityManager()->persist($url);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Delete expired URLs
     */
    public function deleteExpired(): int
    {
        $now = new \DateTimeImmutable();
        
        $qb = $this->createQueryBuilder('u')
            ->delete()
            ->where('u.expiresAt IS NOT NULL')
            ->andWhere('u.expiresAt < :now')
            ->setParameter('now', $now);
        
        return $qb->getQuery()->execute();
    }

    /**
     * Cleanup old URLs that haven't been accessed in a long time
     */
    public function cleanupOldUrls(\DateTimeImmutable $olderThan): int
    {
        $qb = $this->createQueryBuilder('u')
            ->delete()
            ->where('u.lastVisitedAt < :olderThan OR (u.lastVisitedAt IS NULL AND u.createdAt < :olderThan)')
            ->setParameter('olderThan', $olderThan);
        
        return $qb->getQuery()->execute();
    }
}