<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class Repository extends ServiceEntityRepository {
    
    /**
     On peut surcharger la méthode 'findAll' pour pouvoir choisir l'ordre des résultats en utilisant la 
     méthode 'findBy'
     */
    public function findAll(array $ordre = [])
    {
        return $this->findBy([], $ordre);
    }


}