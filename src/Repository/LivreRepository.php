<?php

namespace App\Repository;

use App\Entity\Emprunt;
use App\Entity\Livre;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    /**
        * @return Livre[] Retourne les livres dont le titre contient le mot recherché
        SELECT l.* FROM livre l 
        WHERE l.titre LIKE '%$value%' OR l.resume LIKE '%$value%'
        */
    public function findByRecherche($value): array
    {
        return $this->createQueryBuilder('l')
            ->where('l.titre LIKE :val')
            ->orWhere('l.resume LIKE :val')
            ->setParameter('val', "%$value%")
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /**
        * @return Livre[] Retourne les livres indisponibles (qui n'ont pas été rendu)
            SELECT l.*
            FROM livre l JOIN emprunt e ON l.id = e.livre_id
            WHERE e.date_retour IS NULL     
        */
    public function livresIndisponibles(): array
    {
        return $this->createQueryBuilder('l')
            ->join(Emprunt::class, "e", "WITH", "l.id = e.livre")
            ->andWhere('e.dateRetour IS NULL')
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
        SELECT l.*
        FROM livre l 
        WHERE l.id NOT IN (
            SELECT l.id
            FROM emprunt e JOIN livre l ON e.livre_id = l.id
            WHERE e.date_retour IS NULL )
     * @return array
     */
    public function livresDisponibles(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT l
             FROM App\Entity\Livre l 
             WHERE l.id NOT IN (
                SELECT liv.id
                FROM App\Entity\Emprunt e 
                JOIN App\Entity\Livre liv WITH e.livre = liv.id
                WHERE e.dateRetour IS NULL )
            ORDER BY l.titre"
        );
        return $query->getResult();
    }

//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
