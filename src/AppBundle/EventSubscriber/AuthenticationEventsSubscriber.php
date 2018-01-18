<?php
/**
 * Created by PhpStorm.
 * User: wabap2-5
 * Date: 18/01/18
 * Time: 16:15
 */

namespace AppBundle\EventSubscriber;



use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

class AuthenticationEventsSubscriber implements EventSubscriberInterface
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    public static function getSubscribedEvents()
    {
       return [
           //event symfony pas besoin d'instancier une class
         AuthenticationEvents::AUTHENTICATION_FAILURE => 'authenticationFailure'
       ];
    }


    public function authenticationFailure(AuthenticationFailureEvent $event)
    {

        /*session: ne pas utiliser $_SESSION
         *       service : SessionInterface
         *       méthodes :
         *          set(key, value) : créer une entrée dans la sesion
         *          get(key) : récupéré la valeur d'une entrée
     *              remove(key) : supprimer une entrée
         *          has(key) : tester l'existence d'une entrée
         *          clear : = session unset() / vide la session
         *          invalidate() : détruit la session
         *
         *
         */
        //dump('fail');exit;
        if( $this->session->has('authentication_failure'))
        {
            //récuperation de la valeur
            $value = $this->session->get('authentication_failure');
            //si les echec ne sont pas atteints
            if ($value < 3)
            {
                $value += 1;
                $this->session->set('authentication_failure', $value);
            }
            else
            {
                $this->session->set('authentication_failure',1);
            }
        }
    }
}