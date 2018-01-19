<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 17/01/18
 * Time: 16:03
 */

namespace AppBundle\Events;


use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class AccountCreateEvent extends Event
{
    /*
     * l'evenement sert d'interface entre le declencheur et le subscripteur
     * */

    private $user;

    /**
     * @return mixed
     */
    public function getUser():User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    //important de typer le User pour etre sure que lon recoit un utilisateur
    public function setUser(User $user)
    {
        $this->user = $user;
    }

}