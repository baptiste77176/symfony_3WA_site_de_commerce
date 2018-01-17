<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 17/01/18
 * Time: 16:13
 */

namespace AppBundle\EventSubscriber;


use AppBundle\Events\AccountCreateEvent;
use AppBundle\Events\AccountEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        /*
         * doit retournée un tableau
         *      - clé : l'évenement ecouter
         *      - valuer: nom du gestionnaire d'événement
         * */
        return [
            AccountEvents::CREATE =>'create'
            /*nom de la classe :: constante dans la classe => function*/
        ];
    }

    // un gestionnaire d'événement recoit l'événement en paramètre
    public function create(AccountCreateEvent $event)
    {
        dump('create');
    }

}