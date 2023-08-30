<?php 

namespace App\Entity;

class Entity {

    /**
     * La méthode magique __toString est exécutée 
     * lorsqu'on essaye de convertir un objet de 
     * la classe en string.
     */
    public function __toString()
    {
        $className = get_called_class();
        $className = str_replace("App\Entity\\", "", $className);
        return strtolower($className);
    }    
}