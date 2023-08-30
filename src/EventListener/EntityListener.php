<?php 

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Livre;

class EntityListener {

    /* cette méthode est déclenchée pour chaque évènement lié à une entité */
    // public function onEntityLifecycle(LifecycleEventArgs $lifeCycle)

    /**
     * L'évènement 'postLoad' est déclenché après le chargement d'un objet Entité à partir de la bdd.
     */
    public function postLoad($livre, LifecycleEventArgs $lifeCycle)
    {
        $livreRepository = $lifeCycle->getObjectManager()->getRepository(Livre::class);
        /**
         * @var \App\Repository\LivreRepository $livreRepository
         * 
         * cette annotation n'est utile que pour VS Code. Cela permet à VSCode de considérer
         * la variable $livreRepository comme un objet de la classe \App\Repository\LivreRepository
         */
        $livre->setDisponible( in_array($livre, $livreRepository->livresDisponibles()) );
    }
}