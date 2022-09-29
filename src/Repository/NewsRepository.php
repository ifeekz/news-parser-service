<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 *
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 10;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function add(News $news, bool $flush = false): void
    {
        $this->getEntityManager()->persist($news);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(News $news, bool $flush = false): void
    {
        $this->getEntityManager()->remove($news);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNewsPaginator(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.created_at', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();

        return new Paginator($query);
    }
}
