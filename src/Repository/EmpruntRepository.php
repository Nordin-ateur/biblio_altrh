<?php

namespace App\Repository;

use App\Entity\Emprunt;
use App\Repository\Repository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Emprunt>
 *
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }


   /**
    * @return Emprunt[] Returns an array of Emprunt objects
    SELECT e.* 
    FROM emprunt e
    WHERE e.date_retour IS NULL
    ORDER BY e.date_emprunt
    */
   public function findByDateRetourNull(): array
   {
       return $this->createQueryBuilder('e')
           ->where('e.dateRetour IS NULL')
           ->orderBy('e.dateEmprunt', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   /**
    SELECT count(e.id) FROM emprunt e WHERE e.livre_id = 113 
    */

    public function nbEmprunts(int $livreId)
    {
        /* 
        Quand on fait une requête avec DQL (Doctrine Query Language), les champs de la requête correspondent aux propriétés
        des entités et pas au champ de la table. 
        Par exemple, dans la table Emprunt, la clé étrangère s'appelle 'livre_id'
        mais dans l'entité Emprunt, la propriété s'appelle 'livre'.
        */
        return $this->createQueryBuilder('e')
                    ->where("e.livre = :var")
                    ->setParameter("var", $livreId)
                    ->select("COUNT(e.id)")
                    ->getQuery()
                    ->getOneOrNullResult()[1]
        ;
    }

//    public function findOneBySomeField($value): ?Emprunt
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
