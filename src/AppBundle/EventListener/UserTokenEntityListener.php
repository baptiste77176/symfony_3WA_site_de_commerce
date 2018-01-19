<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\userToken;
use AppBundle\Services\RandomToken;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;


class UserTokenEntityListener
{
    /*
     * injecter un service
     *      - créer une propriété
     *      - créer un constructeur
     * */
    private $token;

    public function __construct()
    {
        $this->token = new RandomToken();
    }

    /*
     * le nom des méthodes doivent reprendre le nom de l'événement écouté
     * paramètres:
     *      - instance de l'entité écouté
     *      - argument différent selon l'événement écouté
     * */
     public function prePersist( userToken $userToken, LifecycleEventArgs $args)
     {
        $generateToken = $this->token->generateToken();
        $userToken->setToken($generateToken);

        $now = new \DateTime('+1 day');
        $userToken->setExpirationDate($now);
     }

}





















