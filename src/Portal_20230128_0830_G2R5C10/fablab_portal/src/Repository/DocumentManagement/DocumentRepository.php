<?php

namespace App\Repository\DocumentManagement;

use App\Entity\DocumentManagement\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Document>
 *
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function save(Document $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Document $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function getObjectsByDate($firstResult, $maxResults)
    {
        $qb = $this->createQueryBuilder('document');
        $qb
            ->select('document')
            ->orderBy('document.releaseDate', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
        ;
        $page = new Paginator($qb);
        return $page;
    }
    
    public function search($keyword)
    {
        $qb = $this->createQueryBuilder('document');
        $qb
        ->select('document')
        ->where("document.title LIKE :keyword")
        ->orderBy('document.title', 'ASC')
        ->setParameter('keyword', '%'.$keyword.'%')
        ;
        $query = $qb->getQuery();
        $objects = $query->getResult();
        return $objects;
    }

//    /**
//     * @return Document[] Returns an array of Document objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Document
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
