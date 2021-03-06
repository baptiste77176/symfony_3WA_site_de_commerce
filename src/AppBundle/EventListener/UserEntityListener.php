<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserEntityListener
{
    /*
     * injecter un service
     *      - créer une propriété
     *      - créer un constructeur
     * */
    private $encoder;

    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /*
     * le nom des méthodes doivent reprendre le nom de l'événement écouté
     * paramètres:
     *      - instance de l'entité écouté
     *      - argument différent selon l'événement écouté
     * */
     public function prePersist(User $user, LifecycleEventArgs $args){
        // récupération du mot de passe en clair
        $plainPassword = $user->getPassword();

        // encodage
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);

        // mise à jour du mot de passe
        $user->setPassword($encodedPassword);

        //dump($user); exit;
     }
    public function preUpdate(User $user,PreUpdateEventArgs $args)
    {
        // récupération du mot de passe en clair
        $plainPassword = $user->getPassword();

        // encodage
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);

        // mise à jour du mot de passe
        $user->setPassword($encodedPassword);
    }

}





















